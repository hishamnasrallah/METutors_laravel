@extends('web.default.layouts.email')

@section('body')
    <td valign="top" class="bodyContent" mc:edit="body_content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        @if ($created_by == 'student')
                            @if ($user->role_name == 'student')
                                <div class="card-body">
                                    <p>Hi <b>{{ $user->first_name }},</b> </p>
                                    <p>We have received your support ticket no. <b>{{ $ticket->ticket_id }}</b> and aim to
                                        respond within 24 hours. You will
                                        receive a notification email once the status is changed. You can view all updates on
                                        your dashboard.</p>
                                    <p>Ticket Status: <b>{{ $ticket->status }}</b></p>
                                    <p>Regards,</p>
                                    <p>Technical Support Team</p>
                                </div>
                            @endif
                            @if ($user->role_name == 'admin')
                                <div class="card-body">
                                    <p>Hi <b>Teacher Success Team,</b> </p>
                                    <p>Student <b>{{ $ticket->user->first_name }}</b> SIN
                                        <b>{{ $ticket->user->id_number }}</b>
                                        has created a new support ticket No. <b>{{ $ticket->ticket_id }}</b>. All details
                                        can be viewed on the Admin dashboard.
                                    </p>
                                    <p>Ticket Status: <b>{{ $ticket->status }}</b></p>
                                    <p>Regards,</p>
                                    <p>Technical Support Team</p>
                                </div>
                            @endif
                        @endif

                        @if ($created_by == 'teacher')
                            @if ($user->role_name == 'teacher')
                                <div class="card-body">
                                    <p>Hi <b>{{ $user->first_name }},</b> </p>
                                    <p>We have received your support ticket no. <b>{{ $ticket->ticket_id }},</b> and aim to respond within 24 hours. You
                                        will receive a notification email once the status is changed. You can view all
                                        updates on your dashboard.</p>
                                    <p>Ticket Status: <b>{{ $ticket->status }}</b></p>
                                    <p>Regards,</p>
                                    <p>Technical Support Team</p>
                                </div>
                            @endif

                            @if ($user->role_name == 'admin')
                                <div class="card-body">
                                    <p>Hi <b>Teacher Success Team,</b> </p>
                                    <p>Teacher <b>{{ $ticket->user->first_name }}</b> TIN
                                        <b>{{ $ticket->user->id_number }}</b> has created a new support ticket No.
                                        <b>{{ $ticket->ticket_id }}</b>. All details can
                                        be viewed on the Admin dashboard.
                                    </p>
                                    <p>Ticket Status: <b>{{ $ticket->status }}</b></p>
                                    <p>Regards,</p>
                                    <p>Technical Support Team</p>
                                </div>
                            @endif
                        @endif


                    </div>
                </div>
            </div>
        </div>
    </td>
@endsection
