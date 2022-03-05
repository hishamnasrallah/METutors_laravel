<?php

namespace App\Http\Controllers;

use App\Models\ContactUs;
use App\Faq;
use App\FaqTopic;
use App\Models\Newsletter;
use Illuminate\Http\Request;
use Validator;

class FaqController extends Controller
{
    //************* Contact Us function **********\\
    public function contact_us(Request $request)
    {
        $rules = [
            'name' => 'required|string|min:1|max:50',
            'email' => 'required|email|max:100',
            // 'company' => 'required|string',
            'subject' => 'required|string',
            'message' => 'required|string',
            // 'files' => 'mimes:jpg,png,pdf |max:2048',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $messages = $validator->messages();
            $errors = $messages->all();

            return response()->json([
                'status' => 'false',
                'errors' => $errors,
            ],400);
        }

        $contact = new ContactUs();
        $contact->name = $request->name;
        $contact->email = $request->email;
        $contact->company = $request->company;
        $contact->subject = $request->subject;
        $contact->message = $request->message;
        $contact->files = null;

        if ($request->hasFile('files')) {
            //************* Contact images **********\\
            $images = array();
            $files = $request->file('files');
            // if ($files) {
            foreach ($files as $file) {
                $imageName = date('YmdHis') . random_int(10, 100) . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('assets/images/contact_images'), $imageName);
                $images[] = $imageName;
            }
            // }
            $contact->files = implode("|", $images);
            //************* Contact images ends **********\\
        }
        $contact->save();
        return response()->json([
            'success' => true,
            'message' => "Your message has been submitted we will contact you soon",

        ]);
    }

    //************* FAQ function **********\\
    public function add_faq(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
        ]);

        $faq = new Faq();
        $faq->title = $request->title;
        $faq->description = $request->description;
        $faq->save();

        return response()->json([
            'success' => true,
            'faq' => $faq,
        ]);
    }

    public function faqs(Request $request)
    {

        if (isset($request->topic_id)) {

            $faqs = Faq::where('topic_id', $request->topic_id)->get();
            return response()->json([
                'success' => true,
                'faqs' => $faqs,
            ]);
        }

        $faqs = Faq::all();
        return response()->json([
            'success' => true,
            'faqs' => $faqs,
        ]);
    }


    public function faq_topics()
    {

        $faq_topics = FaqTopic::all();
        return response()->json([
            'success' => true,
            'faq_topics' => $faq_topics,
        ]);
    }

    public function search_faq(Request $request)
    {
        $request->validate([
            "search_query" => 'required'
        ]);

        $query = $request->search_query;
        $result = Faq::where('title', 'LIKE', "%$query%")->orWhere('description', 'LIKE', "%$query%")->get();

        return response()->json([
            'success' => true,
            'result' => $result,
        ]);
    }

    public function newsletter(Request $request){

        $rules = [
            'email' => 'required|email|unique:newsletters',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $messages = $validator->messages();
            $errors = $messages->all();

            return response()->json([
                'status' => 'false',
                'errors' => $errors,
            ]);
        }
        $newsletter = new Newsletter();
        $newsletter->email = $request->email;
        $newsletter->save();

        return response()->json([
            'status' => true,
            'message' => "Newsletter Submitted Successfully",
        ]);
    }
}
