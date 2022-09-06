@extends('web.default.layouts.email')

@section('body')
    <td valign="top" class="bodyContent" mc:edit="body_content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">

                        @if ($user->role_name == 'teacher')
                            <div class="card-body">
                                <p>Hi <b>{{ $user->first_name }}</b> </p>
                                <p>Thank you for your interest in joining the MEtutors teaching platform.</p>
                                <p>Unfortunatlely, your application has not been successful at this time.</p>
                                <p>We would like to thank you for your time during the application process and would like to
                                    encourage you to resubmit your application in the future.</p>
                                <p> In case you have any queries, please do not hesitate to contact us at @php echo 'https://metutors.com/contact';@endphp.
                                </p>
                                <p>Regards,</p>
                                <p>MEtutors Talent Acquisition Team</p>
                            </div>
                        @endif

                        @if ($user->role_name == 'admin')
                            <div class="card-body">
                                <p>Hi <b>HR,</b> </p>
                                <p><b>{{ $interview->user->first_name }}</b>,TIN <b>{{ $interview->user->id_number }}</b>
                                    hiring application was rejected. For further information visit the tutor
                                    management portal at Tutor management page</p>
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
