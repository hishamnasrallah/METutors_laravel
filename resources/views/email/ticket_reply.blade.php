@extends('web.default.layouts.email')

@section('body')
    <td valign="top" class="bodyContent" mc:edit="body_content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            @if ($user->role_name == 'teacher' || $user->role_name == 'student')
                                <p>Hi <b>{{ $user->first_name }}</b>, </p>
                                <p>A new update has been received on support ticket no. <b>{{ $ticket->ticket_id }}</b>.</p>

                                <p>Ticket issuer: <b>{{ $ticket->user->first_name }}</b></p>
                                <p>Reply by: <b>{{ $ticket->comment->user->first_name }}</b></p>
                                <p>Ticket status: <b>{{ $ticket->status }}</b></p>
                                <p>Message update: <b>{{ $ticket->comment->created_at }}</b></p>

                                <p>Regards,</p>
                                <p>Technical Support Team</p>
                            @endif


                            @if ($user->role_name == 'admin')
                                <p>Hi <b>Sucess Team</b>, </p>
                                <p>A new update has been received on support ticket no. <b>{{ $ticket->ticket_id }}</b>.</p>

                                <p>Ticket issuer: <b>{{ $ticket->user->first_name }}</b></p>
                                <p>Reply by: <b>{{ $ticket->comment->user->first_name }}</b></p>
                                <p>Ticket status: <b>{{ $ticket->status }}</b></p>
                                <p>Message update: <b>{{ $ticket->comment->created_at }}</b></p>

                                <p>Regards,</p>
                                <p>Student Success Team</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </td>
@endsection
