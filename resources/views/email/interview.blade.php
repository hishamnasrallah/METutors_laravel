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
                                <p>Hi <b>{{ $user->first_name }}</b>, You have successfully sent an interview request to the
                                    METutor Admin team. You will
                                    receive a notification when the MEtutor platform admin team accepts/rejects your request
                                    or schedules an interview as per their decision.</p>

                                <p> Teacher Information Number (TIN): <b>{{ $interview_request->user->id_number }}</b></p>
                                <p>Regards</p>
                            </div>
                        @endif

                        @if ($user->role_name == 'admin')
                            <div class="card-body">
                                <p>Hi <b>{{ $user->first_name }} {{ $user->last_name }}</b> </p>
                                <p>You have a new interview request from
                                    <b>{{ $interview_request->user->first_name }}</b>.
                                    Please action it by approving or rejecting it and accordingly schedule the interview.
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
