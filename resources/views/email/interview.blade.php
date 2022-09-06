@extends('web.default.layouts.email')

@section('body')
    <td valign="top" class="bodyContent" mc:edit="body_content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        @if ($user->role_name == 'teacher')
                            <div class="card-body">
                                <p>Hi <b>{{ $user->first_name }},</b> </p>
                                <p>Thank you for your interest in joining the MEtutor's teaching team.</p>
                                <p>We have received your interview request and we are currently reviewing your application.
                                </p>
                                <p>If your application is successful, we will be in touch shortly to confirm an interview
                                    date
                                    and time.</p>
                                {{-- {{ $interview_request->user->id_number }} --}}

                                <p> Please feel free to contact us at
                                    @php
                                        echo 'https://metutors.com/contact';
                                    @endphp if you have any queries in the meantime.</p>
                                <p>Regards,</p>
                                <p>Talent Acquisition Team</p>
                            </div>
                        @endif

                        @if ($user->role_name == 'admin')
                            <div class="card-body">
                                <p>Hi <b>HR,</b> </p>
                                <p>You have received a new interview request from
                                    <b>{{ $interview_request->user->first_name }} {{ $interview_request->user->last_name }}</b>,
                                        TIN#  <b>{{ $interview_request->user->id_number }}</b>.
                                </p>
                                <p>Please proceed with the interview process and confirm or reschedule the requested time on
                                    the following link 
                                    @php
                                        echo 'https://metutors.com/admin/tutor/interview/details/' . $interview_request->id;
                                    @endphp
                                </p>
                                <p>Regards,</p>
                                <p>Talent Acquisition Team</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </td>
@endsection
