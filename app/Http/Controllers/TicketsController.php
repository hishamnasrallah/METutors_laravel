<?php
namespace App\Http\Controllers;
use App\Models\TicketCategory;
use App\Models\TicketPriorities;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;
use App\Mailers\AppMailer;
use DB;
use Illuminate\Http\Request;
use App\User;

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use Hash;
use Illuminate\Support\Str;
use JWTAuth;
class TicketsController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */



    public function ticket_priorities(){

        $ticket_priorities=TicketPriorities::all();

         return response()->json([
            'success' => 'true',
            'ticket_priorities' => $ticket_priorities
        ]);
    }

    public function ticket_categories(){

        $ticket_categories=TicketCategory::all();

         return response()->json([
            'success' => 'true',
            'ticket_categories' => $ticket_categories
        ]);
    }

    public function index(Request $request)
    {

        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);

       
        $user = $token_user;
        if ($user->role_name != 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'access denied',
            ],401);
        }
        $tickets = Ticket::orderBy('created_at', 'desc')->get();
        foreach ($tickets as $ticket) {
            $ticket->user = $ticket->user;
            unset($ticket->user->industry);
            unset($ticket->user->no_of_hires);
            unset($ticket->user->company_logo);
            unset($ticket->user->email_verified_at);
            unset($ticket->user->company_phone);
            unset($ticket->user->country);
            unset($ticket->user->city);
            unset($ticket->user->address);
            unset($ticket->user->zipcode);
            unset($ticket->user->website_link);
            unset($ticket->user->linkedin_profile_link);
            unset($ticket->user->profile_bio);
            unset($ticket->user->language);
            unset($ticket->user->availability);
            unset($ticket->user->expertise);
            unset($ticket->user->expertise_level);
            unset($ticket->user->acceptTerms);
            unset($ticket->user->vat);
            unset($ticket->user->profile_completed);
            unset($ticket->user->stripe_id);
            unset($ticket->user->card_brand);
            unset($ticket->user->trial_ends_at);
            unset($ticket->user->pm_type);
            unset($ticket->user->pm_last_four);
        }
        return response()->json([
            'success' => 'true',
            'tickets' => $tickets
        ]);
        return view('tickets.index', compact('tickets'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return 'hello';
        $categories = TicketCategory::all();
        return view('tickets.create', compact('categories'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store_new(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'title' => 'required',
            'category' => 'required',
            'priority' => 'required',
            'message' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 400);
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
        $ticket = new Ticket([
            'subject' => $request->title,
            'user_id' =>  $user->id,
            'ticket_id' => strtoupper(Str::random(10)),
            'category_id' => $request->category,
            'priority' => $request->priority,
            'message' => $request->message,
            'status' => "Open",
            'file' => $file,
        ]);
        $ticket->save();
        return response()->json([
            'message' => 'success',
            'status' => "A ticket with ID: #$ticket->ticket_id has been opened",
        ]);
    }
    public function store(Request $request, AppMailer $mailer)
    {
        $this->validate($request, [
            'title' => 'required',
            'category' => 'required',
            'priority' => 'required',
            'message' => 'required'
        ]);
        $ticket = new Ticket([
            'title' => $request->input('title'),
            'user_id' => Auth::user()->id,
            'ticket_id' => strtoupper(str_random(10)),
            'category_id' => $request->input('category'),
            'priority' => $request->input('priority'),
            'message' => $request->input('message'),
            'status' => "Open"
        ]);
        $ticket->save();
        $mailer->sendTicketInformation(Auth::user(), $ticket);
        return redirect()->back()->with("status", "A ticket with ID: #$ticket->ticket_id has been opened.");
    }
    public function userTickets(Request $request)
    {
        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);

        $user = $token_user;
        // return $user->id;
        $tickets = Ticket::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
        // return json_encode($tickets);
        return response()->json([
            'message' => 'success',
            'tickets' => $tickets,
        ]);
        return view('tickets.user_tickets', compact('tickets'));
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($ticket_id)
    {
        
        $ticket = Ticket::where('ticket_id', $ticket_id)->firstOrFail();
        $ticket->user = $ticket->user;
            unset($ticket->user->industry);
            unset($ticket->user->no_of_hires);
            unset($ticket->user->company_logo);
            unset($ticket->user->email_verified_at);
            unset($ticket->user->company_phone);
            unset($ticket->user->country);
            unset($ticket->user->city);
            unset($ticket->user->address);
            unset($ticket->user->zipcode);
            unset($ticket->user->website_link);
            unset($ticket->user->linkedin_profile_link);
            unset($ticket->user->profile_bio);
            unset($ticket->user->language);
            unset($ticket->user->availability);
            unset($ticket->user->expertise);
            unset($ticket->user->expertise_level);
            unset($ticket->user->acceptTerms);
            unset($ticket->user->vat);
            unset($ticket->user->profile_completed);
            unset($ticket->user->stripe_id);
            unset($ticket->user->card_brand);
            unset($ticket->user->trial_ends_at);
            unset($ticket->user->pm_type);
            unset($ticket->user->pm_last_four);
        $ticket->comments = $ticket->comments;
        foreach ($ticket->comments as $comment) {
            $comment->user = $comment->user;
            unset($comment->user->industry);
            unset($comment->user->no_of_hires);
            unset($comment->user->company_logo);
            unset($comment->user->email_verified_at);
            unset($comment->user->company_phone);
            unset($comment->user->country);
            unset($comment->user->city);
            unset($comment->user->address);
            unset($comment->user->zipcode);
            unset($comment->user->website_link);
            unset($comment->user->linkedin_profile_link);
            unset($comment->user->profile_bio);
            unset($comment->user->language);
            unset($comment->user->availability);
            unset($comment->user->expertise);
            unset($comment->user->expertise_level);
            unset($comment->user->acceptTerms);
            unset($comment->user->vat);
            unset($comment->user->profile_completed);
            unset($comment->user->stripe_id);
            unset($comment->user->card_brand);
            unset($comment->user->trial_ends_at);
            unset($comment->user->pm_type);
            unset($comment->user->pm_last_four);
        }
        return response()->json([
            'message' => 'success',
            'ticket' => $ticket,
        ]);
        return view('tickets.show', compact('ticket'));
    }
    public function close(Request $request, $ticket_id)
    {
        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);

        
        $user = $token_user;
        if ($user->role_name != 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'access denied',
            ],401);
        }
        $ticket = Ticket::where('ticket_id', $ticket_id)->firstOrFail();
        $ticket->status = "Closed";
        $ticket->save();
        $ticketOwner = $ticket->user;
        return response()->json([
            'message' => 'success',
            'status' => 'Status changed successfully',
        ]);
        return redirect()->back()->with("status", "The ticket has been closed.");
    }
}
