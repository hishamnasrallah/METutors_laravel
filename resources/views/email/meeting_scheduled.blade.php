@extends('web.default.layouts.email')

@section('body')
    <td valign="top" class="bodyContent" mc:edit="body_content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        @if ($user->role_name == 'teacher')
                            <div class="card-body">
                                <p>Dear <b>{{ $user->first_name }} {{ $user->last_name }}</b> </p>
                                <p>Hi <b>{{ $user->first_name }}</b>, MEtutor platform admin team accepts your request
                                    or schedules an interview as per their decision on
                                    <b>{{ $interview_request->date_for_interview }}</b> (date) at
                                    <b>{{ $interview_request->time_for_interview }} (time)</b> .
                                </p>
                                <p> Teacher Information Number (TIN): <b>{{ $interview_request->user->id_number }}</b></p>
                                <p>Regards</p>
                            </div>
                        @endif

                        @if ($user->role_name == 'admin')
                            <div class="card-body">
                                <p>Hi <b>{{ $user->first_name }} {{ $user->last_name }}</b> </p>
                                <p>You have a suuccessfully scheduled an interview on
                                    <b>{{ $interview_request->date_for_interview }}</b> (date) at
                                    <b>{{ $interview_request->time_for_interview }} (time)</b> .
                                    <b>{{ $interview_request->user->first_name }}</b>.
                                </p>
                                <p> Teacher Information Number (TIN): <b>{{ $interview_request->user->id_number }}</b></p>
                                <p>Regards</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </td>
@endsection
