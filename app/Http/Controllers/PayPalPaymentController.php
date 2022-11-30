<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Omnipay\Omnipay;
use App\Models\Payment;
use Exception;

class PayPalPaymentController extends Controller
{
    private $gateway;

    public function __construct()
    {
        $this->gateway = Omnipay::create('PayPal_Rest');
        $this->gateway->setClientId('AQ-WLlFPLwYZLo3l2DEsLv76r7_vUGhHNs_duvKL14ppwDrQbWaeCOmRalYI0z_IHT2OnqyTjsxAVFxS');
        $this->gateway->setSecret('EM59BWZAAYoM0MJZrJU9GniiHjJPlDOf0-L0wlIqp3WXoLHoPqhxmp13Y6AAZ6Pj-fjxP8F_hh5g9pnD');
        $this->gateway->setTestMode(true); //set it to 'false' when go live
    }

    /**
     * Call a view.
     */


    /**
     * Initiate a payment on PayPal.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function charge(Request $request)
    {

        try {
            $response = $this->gateway->purchase(array(
                'amount' => $request->amount,
                'currency' => 'USD',
                'returnUrl' => url('paypal-success'),
                'cancelUrl' => url('paypal-error'),
            ))->send();

            // return 'xcjkbfkj';

            if ($response->isRedirect()) {
                // $response->redirect(); // this will automatically forward the customer
                return response()->json([
                    'status' => true,
                    'message' => "Redirect URL",
                    // 'redirect_url' => $data['contextualLogin']['experienceMetaData']['merchantData']->paymentToken,
                    'response' => $response->redirect(),
                    // 'response' => $response['paypal_link'],
                ]);
            } else {
                // not successful
                return response()->json([
                    'status' => false,
                    'message' => $response->getMessage(),

                ], 400);
            }
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),

            ], 400);
        }
    }

    /**
     * Charge a payment and store the transaction.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function success(Request $request)
    {
        // Once the transaction has been approved, we need to complete it.
        if ($request->input('paymentId') && $request->input('PayerID')) {
            $transaction = $this->gateway->completePurchase(array(
                'payer_id'             => $request->input('PayerID'),
                'transactionReference' => $request->input('paymentId'),
            ));
            $response = $transaction->send();

            if ($response->isSuccessful()) {
                // The customer has successfully paid.
                $arr_body = $response->getData();


                // // Insert transaction data into the database
                // $payment = new Payment;
                // $payment->payment_id = $arr_body['id'];
                // $payment->payer_id = $arr_body['payer']['payer_info']['payer_id'];
                // $payment->payer_email = $arr_body['payer']['payer_info']['email'];
                // $payment->amount = $arr_body['transactions'][0]['amount']['total'];
                // $payment->currency = env('PAYPAL_CURRENCY');
                // $payment->payment_status = $arr_body['state'];
                // $payment->save();

                return "Payment is successful. Your transaction id is: " . $arr_body['id'];
            } else {
                
                return $response->getMessage();
            }
        } else {
            return 'Transaction is declined';
        }
    }

    /**
     * Error Handling.
     */
    public function error()
    {
        return 'User cancelled the payment.';
    }

   
}
