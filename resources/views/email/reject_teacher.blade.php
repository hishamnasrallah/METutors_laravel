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
                                <p>Thank you for your interest in joining the MEtutors teaching platform. Unfortunately,
                                    your application has not been successful at this time. </p>
                                <p>We would like to thank you for your time during the application process and would like to
                                    encourage you to resubmit your application in the future. In case you have any queries,
                                    please do not hesitate to contact us at @php echo 'https://metutors.com/contact';@endphp.
                                </p>
                                <p>Regards,</p>
                                <p>Talent Acquisition Team</p>
                            </div>
                        @endif

                        @if ($user->role_name == 'admin')
                            <div class="card-body">
                                <p>Hi <b>HR,</b> </p>
                                <p>The application for <b>{{ $interview->user->first_name }}
                                        {{ $interview->user->last_name }}</b>,TIN# <b>{{ $interview->user->id_number }}</b>
                                    has been declined. For further information visit the tutor management portal at <b>@php echo "https://metutors.com/admin/tutor/interview" @endphp</b>
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
