@extends('web.default.layouts.email')

@section('body')
    <!-- content -->
    <td valign="top" class="bodyContent" mc:edit="body_content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">

                        <div class="card-body">
                            <p>Hi <b>{{ $user->first_name }} {{ $user->last_name }},</b> </p>
                            <p>Looks like you've forgotten your password! Click the following link to get a new one
                                @php echo 'https://metutors.com/reset-password/' . $token . '/' . $email; @endphp</p>


                            <p>If you didn't request a new password, you can ignore this email.</p>
                            <p>Regards,</p>
                            <p>Technical Support Team</p>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </td>
@endsection
