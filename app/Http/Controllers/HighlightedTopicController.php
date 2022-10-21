<?php

namespace App\Http\Controllers;

use App\Models\HighlightedTopic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HighlightedTopicController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string',
            'confidence_scale' => 'required|string',
            'course_id' => 'required|integer',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $messages = $validator->messages();
            $errors = $messages->all();

            return response()->json([

                'status' => 'false',
                'errors' => $errors,
            ], 400);
        }

        $topic = new HighlightedTopic();
        $topic->name = $request->name;
        $topic->confidence_scale = $request->confidence_scale;
        $topic->course_id = $request->course_id;
        $topic->save();

        return response()->json([
            'status' => true,
            'messaege' => 'Highlighted topic successfully added',
            'highlighted_topic' => $topic,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($course_id)
    {
        $topics = HighlightedTopic::where('course_id', $course_id)->get();
        if(count( $topics) == 0){
            return response()->json([
                'status' => true,
                'message' => 'Highlighted topics not found for this course',
            ]);
        }
        return response()->json([
            'status' => true,
            'message' => 'Highlighted topics of course',
            'highlighted_topics' => $topics,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rules = [
            'name' => 'required|string',
            'confidence_scale' => 'required|string',
            'course_id' => 'required|integer',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $messages = $validator->messages();
            $errors = $messages->all();

            return response()->json([

                'status' => 'false',
                'errors' => $errors,
            ], 400);
        }

        $topic = HighlightedTopic::find($id);
        if ($topic) {
            $topic->name = $request->name;
            $topic->confidence_scale = $request->confidence_scale;
            $topic->course_id = $request->course_id;
            $topic->update();
        } else {
            return response()->json([
                'status' => false,
                'message' => 'No topic found',
            ]);
        }


        return response()->json([
            'status' => true,
            'message' => 'Highlighted topic updated successfully',
            'highlighted_topic' => $topic,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $topic = HighlightedTopic::find($id);
        if ($topic) {
            $topic->delete();
        } else {
            return response()->json([
                'status' => false,
                'messaege' => 'No topic found',
            ]);
        }


        return response()->json([
            'status' => true,
            'message' => 'Highlighted topic deleted successfully',
            'highlighted_topic' => $topic,
        ]);
    }
}
