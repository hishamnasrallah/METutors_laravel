<?php
namespace App\Http\Controllers;
use App\Models\Comment;
use App\Mailers\AppMailer;
use Illuminate\Support\Facades\Auth;
use App\Models\TicketCategory;
use App\Models\Ticket;
use DB;

use App\User;

use Illuminate\Http\Request;

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

use Hash;
use JWTAuth;

class CommentsController extends Controller
{
    public function postComment(Request $request, AppMailer $mailer)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'ticket_id' => 'required',
            'comment' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 400);
        }
        $tick = Ticket::find($request->ticket_id);
        if ($tick->status == "Closed") {
            return response()->json([
                'status' => 'false',
                'message' => "Ticket is closed you can not add comment on it",
            ],400);
        }

         $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);
        
        $user = $token_user;
        if ($request->hasFile('file')) {
            $imageName = time() . '.' . $request->file->getClientOriginalExtension();
            $request->file->move(public_path('uploads/images'), $imageName);
            $file = $imageName;
        } else {
            $file = null;
        }
        $comment = Comment::create([
            'ticket_id' => $request->ticket_id,
            'user_id' => $user->id,
            'comment' => $request->comment,
            'file' => $file,
        ]);
        return response()->json([
            'message' => 'success',
            'status' => "Reply has been submitted",
        ]);
        return redirect()->back()->with("status", "Your comment has be submitted.");
    }
}
