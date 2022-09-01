@extends('web.default.layouts.email')

@section('body')
    <td valign="top" class="bodyContent" mc:edit="body_content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        @if ($user->role_name == 'teacher')
                            <div class="card-body">
                                <p>Hi <b>{{ $user->first_name }} {{ $user->last_name }},</b> </p>
                                <p> We thank you for your interest in joining MEtutors teaching platform. Your interview
                                    request was
                                    received by our Talent Acquisition team. Your application TIN
                                    {{ $interview_request->user->id_number }} is under review at this time.
                                    You will receive a status update or an interview date confirmation shortly..</p>

                                <p> Please feel free to contact us at
                                    @php
                                        echo 'https://metutors.com/about';
                                    @endphp in case of any queries.</p>
                                <p>Regards</p>
                                <p>Talent Acquisition Team</p>
                            </div>
                        @endif

                        @if ($user->role_name == 'admin')
                            <div class="card-body">
                                <p>Hi <b>HR,</b> </p>
                                <p>You have received a new interview request from
                                    <b>{{ $interview_request->user->first_name }}
                                        TIN#{{ $interview_request->user->id_number }} </b>.
                                </p>
                                <p>Please proceed with the interview process at the link here
                                    @php
                                        echo 'https://metutors.com/admin/tutor/interview/details/' .  $interview_request->id;
                                    @endphp
                                </p>
                                <p>Regards</p>
                                <p>Talent Acquisition Team</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </td>
@endsection
