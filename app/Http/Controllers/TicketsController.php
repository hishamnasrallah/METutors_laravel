<?php

namespace App\Http\Controllers;

use App\Events\CloseTicketEvent;
use App\Events\OpenTicketEvent;
use App\Jobs\CloseTicketJob;
use App\Jobs\OpenTicketJob;
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
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;

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



    public function ticket_priorities()
    {

        $ticket_priorities = TicketPriorities::all();

        return response()->json([
            'success' => 'true',
            'ticket_priorities' => $ticket_priorities
        ]);
    }

    public function ticket_categories()
    {

        $ticket_categories = TicketCategory::all();

        return response()->json([
            'success' => 'true',
            'ticket_categories' => $ticket_categories
        ]);
    }

    public function index(Request $request)
    {

        // $token_1 = JWTAuth::getToken();
        // $token_user = JWTAuth::toUser($token_1);


        // $user = $token_user;
        // if ($user->role_name != 'admin') {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'access denied',
        //     ], 401);
        // }
        //**************** Filters Start ****************
        //Priority Filter
        if ($request->has('priority')) {
            $tickets = Ticket::with('category', 'user', 'priority')->orderBy('created_at', 'desc')
                ->orWhereHas('priority', function ($q) use ($request) {
                    $q->where('name', 'LIKE', "%$request->priority%");
                })
                ->orWhereHas('category', function ($q) use ($request) {
                    $q->where('name', 'LIKE', "%$request->priority%");
                });
        }
        //Category Filter
        if ($request->has('category')) {
            $tickets = Ticket::with('category', 'user', 'priority')->orderBy('created_at', 'desc')
                ->orWhereHas('category', function ($q) use ($request) {
                    $q->where('name', 'LIKE', "%$request->priority%")
                        ->orWhere('id', $request->search);
                })
                ->orWhereHas('priority', function ($q) use ($request) {
                    $q->where('name', 'LIKE', "%$request->priority%")
                        ->orWhere('id', $request->search);
                });
        }
        //Search Bar
        if ($request->has('search')) {

            $tickets = Ticket::with('category', 'user', 'priority')->orderBy('created_at', 'desc')
                ->where(function ($query) use ($request) {
                    $query->where('subject', 'LIKE', "%$request->search%")
                        ->orWhere('message', 'LIKE', "%$request->search%")
                        ->orWhere('ticket_id', 'LIKE', $request->search)
                        ->orWhere('status', 'LIKE', "%$request->search%")
                        ->orWhereHas('priority', function ($q) use ($request) {
                            $q->where('name', 'LIKE', "%$request->search%")
                                ->orWhere('id', $request->search);
                        })
                        ->orWhereHas('category', function ($q) use ($request) {
                            $q->where('name', 'LIKE', "%$request->search%")
                                ->orWhere('id', $request->search);
                        });
                })->get();
        } else {

            $tickets = Ticket::with('category', 'user', 'priority')->orderBy('created_at', 'desc')->get();
        }
        //**************** Filters Ends ****************


        $total_tickets = Ticket::count();
        $open_tickets = Ticket::where('status', 'open')->count();
        $closed_tickets = Ticket::where('status', 'closed')->count();
        $urgent_tickets = Ticket::where('status', 'open')->where('priority', 1)->count();

        if (isset($request->status)) {

            if ($request->has('search')) {

                $tickets = Ticket::with('category', 'user', 'priority')->where('status', $request->status)->orderBy('created_at', 'desc')
                    ->where(function ($query) use ($request) {
                        $query->where('subject', 'LIKE', "%$request->search%")
                            ->orWhere('message', 'LIKE', "%$request->search%")
                            ->orWhere('ticket_id', 'LIKE', $request->search)
                            ->orWhere('status', 'LIKE', "%$request->search%");
                    })->get();
            } else {

                $tickets = Ticket::with('category', 'user', 'priority')->where('status', $request->status)->orderBy('created_at', 'desc')->get();
            }
        } elseif (isset($request->status) && $request->status == 'urgent') {

            if ($request->has('search')) {

                $tickets = Ticket::with('category', 'user', 'priority')->where('status', 'open')->where('priority', 1)->orderBy('created_at', 'desc')
                    ->where(function ($query) use ($request) {
                        $query->where('subject', 'LIKE', "%$request->search%")
                            ->orWhere('message', 'LIKE', "%$request->search%")
                            ->orWhere('ticket_id', 'LIKE', $request->search)
                            ->orWhere('status', 'LIKE', "%$request->search%");
                    })->get();
            } else {

                $tickets = Ticket::with('category', 'user', 'priority')->where('status', 'open')->where('priority', 1)->orderBy('created_at', 'desc')->get();
            }
        }



        return response()->json([
            'success' => 'true',
            'total_tickets' => $total_tickets,
            'open_tickets' => $open_tickets,
            'closed_tickets' => $closed_tickets,
            'urgent_tickets' => $urgent_tickets,
            'tickets' => $this->paginate($tickets, $request->per_page ?? 10),
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
        $ticket = new Ticket();

        $ticket->subject = $request->title;
        $ticket->user_id =  $user->id;
        $ticket->ticket_id = strtoupper(Str::random(10));
        $ticket->category_id = $request->category;
        $ticket->priority = $request->priority;
        $ticket->message = $request->message;
        $ticket->status = "Open";
        $ticket->file = $file;

        $ticket->save();

        $ticket->category = $ticket->category;
        $ticket->priority = $ticket->priority;

        $teacher_message = "Ticket opened Successfully!";
        $admin_message = "New Ticket has been opened!";
        $admin = User::where('role_name', 'admin')->first();

        // event(new OpenTicketEvent($user->id, $user, $teacher_message, $ticket));
        // event(new OpenTicketEvent($admin->id, $admin, $admin_message, $ticket));
        // dispatch(new OpenTicketJob($user->id, $user, $teacher_message, $ticket));
        // dispatch(new OpenTicketJob($admin->id, $admin, $admin_message, $ticket));

        return response()->json([
            'message' => 'success',
            'ticket' => $ticket,
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
        $tickets = Ticket::with('category', 'priority', 'user')->where('user_id', $user->id)->orderBy('created_at', 'desc')->get();

        foreach ($tickets as $ticket) {
            $latest_comment = $ticket->ticket_comments->first();
            if ($latest_comment) {
                $ticket->last_reply = $latest_comment->created_at->diffForHumans();
            } else {
                $ticket->last_reply = null;
            }
            unset($ticket['ticket_comments']);
        }

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

        $ticket = Ticket::with('category', 'user', 'priority')->where('ticket_id', $ticket_id)->firstOrFail();
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


        //Latest reply time
        $latest_comment = $ticket->ticket_comments->first();
        if ($latest_comment) {
            $ticket->last_reply = $latest_comment->created_at->diffForHumans();
        } else {
            $ticket->last_reply = null;
        }
        unset($ticket['ticket_comments']);

        return response()->json([
            'message' => 'success',
            'ticket' => $ticket,
        ]);
        return view('tickets.show', compact('ticket'));
    }
    public function change_status(Request $request)
    {
        $rules = [

            'status' => 'required',
            'ticket_id' => 'required',
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

        $token_1 = JWTAuth::getToken();
        $token_user = JWTAuth::toUser($token_1);


        $user = $token_user;
        if ($user->role_name != 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'access denied',
            ], 401);
        }

        if (strtolower($request->status) == 'closed' || strtolower($request->status) == 'inprogress') {
        } else {
            return response()->json([
                'success' => false,
                'message' => 'status can only be changed to closed or inprogress',
            ], 401);
        }
        $ticket = Ticket::where('ticket_id', $request->ticket_id)->firstOrFail();
        $ticket->status = $request->status;
        $ticket->save();
        $ticketOwner = $ticket->user;

        $teacher_message = "Ticket has marked as $request->status!";
        $admin_message = "Ticket has marked as $request->status!";
        $admin = User::where('role_name', 'admin')->first();

        event(new CloseTicketEvent($user->id, $user, $teacher_message, $ticket));
        event(new CloseTicketEvent($admin->id, $admin, $admin_message, $ticket));
        dispatch(new CloseTicketJob($user->id, $user, $teacher_message, $ticket));
        dispatch(new CloseTicketJob($admin->id, $admin, $admin_message, $ticket));

        return response()->json([
            'message' => 'success',
            'status' => 'Status changed successfully',
        ]);
        return redirect()->back()->with("status", "The ticket has been closed.");
    }



    public function paginate($items, $perPage, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage)->values(), $items->count(), $perPage, $page, $options);
    }
}
