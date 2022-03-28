<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Twilio\Jwt\AccessToken;
use Twilio\Jwt\Grants\VideoGrant;

class TwilioController extends Controller
{
    public function generate_token()
    {
        // Substitute your Twilio Account SID and API Key details
       $accountSid = "ACc35599df71b2eea1fdafa8a956fc5c34";
        $apiKeySid = "SKe84e3bd783b9259fea55a18a7d15f40e";
        $apiKeySecret = "c4wokZi00A3RdFVDDYOWdsGuTfU0EcRY";


        // return $accountSid;

        $identity = uniqid();

         
            $email = 'mabdulrehman14713@gmail.com';
            $name = 'abdulrehman';
            $role= 'teacher';

       
        

        // Create an Access Token
        $token = new AccessToken(
            $accountSid,
            $apiKeySid,
            $apiKeySecret,
            3600,
            $identity,
           
        );
       $room_name=$email.'#'.$name.'#'.$role;
 
        // Grant access to Video
        $grant = new VideoGrant();
        $grant->setRoom($room_name);
        $token->addGrant($grant);

        // Serialize the token as a JWT

               return response()->json([

                'status' => true,
                'token' => $token->toJWT()
                ]);
         
    }
}
