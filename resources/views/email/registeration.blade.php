@extends('web.default.layouts.email')

@section('body')
    <td valign="top" class="bodyContent" mc:edit="body_content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        @if ($user->role_name == 'teacher')
                            <div class="card-body">
                                <p>Hi <b> {{ $user->first_name }},</b> </p>
                                <p>Welcome to MEtutors!</p>
                                <p>You have successfully registered on the platform and we are looking forward to working
                                    with
                                    you. We have assigned you the following Teacher Identification Number (TIN):
                                    <b>{{ str_replace("TIN", "#", $user->id_number) }}</b>
                                </p>
                                <p> Please visit @php echo 'https://metutors.com/tutor/settings';@endphp to complete your application and submit your Interview
                                    request. If you have any other queries you can contact us at @php echo 'https://metutors.com/contact'; @endphp.

                                </p>
                                <p>Regards,</p>
                                <p>Talent Acquisition Team</p>
                            </div>
                        @endif

                        @if ($user->role_name == 'admin')
                            <div class="card-body">
                                <p>Hi <b>HR</b>, </p>
                                <p>A new teacher has registered on the platform, <b>{{ $user->teacher->first_name }}</b> TIN# <b>{{ $user->teacher->id_number }}</b>. You can see the new teacher
                                    profile on this link  @php echo 'https://metutors.com/tutor/settings';@endphp</p>
                                <p>Regards,</p>
                                <p>MEtutors Talent Acquisition Team</p>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </td>
@endsection
