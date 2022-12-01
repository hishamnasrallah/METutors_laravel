@extends('web.default.layouts.email')

@section('body')
    @php
        use Carbon\Carbon;
    @endphp
    <td valign="top" class="bodyContent" mc:edit="body_content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">

                            @if ($user->role_name == 'teacher')
                                <p>Hi <b>{{ $user->first_name }},</b></p>
                                <p>Good news! </p>
                                <p>Your support ticket no. <b>{{ $ticket->ticket_id }}</b> has been resolved successfully
                                    and we have changed the status
                                    from <b>{{ $ticket_status }}</b> to <b>{{ $ticket->status }}</b>
                                </p>
                                @if ($comment)
                                    <p>Message update: <b>{{ Carbon::parse($comment)->format('Y M d') }}</b>
                                    </p>
                                @endif
                                <p>We hope that our customer support team has been helpful. </p>
                                <p>Regards,</p>
                                <p>Technical Support Team</p>
                            @elseif ($user->role_name == 'admin')
                                <p>Hi <b>{{ $user->first_name }},</b></p>
                                <p>Please note that support ticket No. <b>{{ $ticket->ticket_id }}</b> has been resolved
                                    successfully. </p>
                                <p>Ticket issuer: <b>{{ $ticket->user->first_name }}</b></p>
                                <p>Reply by: <b>{{ $user->first_name }}</b></p>

                                <p>Ticket status: <b>{{ $ticket->status }}</b></p>
                                @if ($comment)
                                    <p>Message update: <b>{{ Carbon::parse($comment)->format('Y M d') }}</b>
                                    </p>
                                @endif

                                <p>Regards,</p>
                                <p>Technical Support Team</p>
                            @elseif ($user->role_name == 'student')
                                <p>Hi <b>{{ $user->first_name }},</b></p>
                                <p>Good news! </p>
                                <p>Your support ticket no. <b>{{ $ticket->ticket_id }}</b> has been resolved successfully
                                    and we have changed the status
                                    from <b>{{ $ticket_status }}</b> to <b>{{ $ticket->status }}</b>
                                </p>
                                @if ($comment)
                                    <p>Message update: <b>{{ Carbon::parse($comment)->format('Y M d') }}</b>
                                    </p>
                                @endif

                                <p>We hope that our customer support team has been helpful. </p>
                                <p>Regards,</p>
                                <p>Technical Support Team</p>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </td>
@endsection
