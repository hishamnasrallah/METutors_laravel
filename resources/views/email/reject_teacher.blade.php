@extends('web.default.layouts.email')

@section('body')
    <td valign="top" class="bodyContent" mc:edit="body_content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">

                        @if ($user->role_name == 'teacher')
                            <div class="card-body">
                                <p>Hi <b>{{ $user->first_name }} {{ $user->last_name }}</b> </p>
                                <p>We thank you for your interest in joining MEtutors teaching platform. We are sorry to
                                    inform you
                                    that MEtutors' talent acquisition team did not find your qualifications or credentials
                                    meet the
                                    job requirements at this time. Unfortunetely, your application was declined.</p>
                                <p> We encourage you to resubmit your applications in the future and wish you all the best.
                                    In case
                                    you have any queries, please do not hesitate to contact us at
                                    @php
                                        echo 'https://metutors.com/about';
                                    @endphp
                                </p>
                                <p> Teacher Information Number (TIN): <b>{{ $interview->user->id_number }}</b></p>
                                <p>Regards,</p>
                                <p>Talent Acquisition Team</p>
                            </div>
                        @endif

                        @if ($user->role_name == 'admin')
                            <div class="card-body">
                                <p>Hi <b>HR,</b> </p>
                                <p> You have REJECTED Tutor {{ $interview->user->first_name }} based on their interview.</p>
                                <p> Teacher Information Number (TIN): <b>{{ $interview->user->id_number }}</b></p>

                                <p> Yasin, TIN {{ $interview->user->first_name }} hiring application was rejected. For
                                    further information visit the tutor
                                    management portal at Tutor management page</p>
                                <p>Regards</p>
                                <p> Talent Acquisition Team</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </td>
@endsection
