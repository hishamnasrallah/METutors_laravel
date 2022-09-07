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
                                <p>Your application to join MEtutors teaching platofrm was reviewed by our talent
                                    acquisition team
                                    and an interview meeting has been scheudled on
                                    <b>{{ $interview_request->date_for_interview->format('Y-m-d') }}</b>,
                                    <b>{{ $interview_request->time_for_interview->format('H:i A') }}</b>. Please login to your account
                                    to start your interview.
                                </p>

                                <p>If you wish to change your interveiw date or time, please conatct us at
                                    @php echo "https://metutors.com/contact" @endphp.</p>
                                <p>Regards,</p>
                                <p>Talent Acquisition Team,</p>
                            </div>
                        @endif

                        @if ($user->role_name == 'admin')
                            <div class="card-body">
                                <p>Hi <b>HR,</b></p>
                                <p>A new interview request has been scheudled and sent to
                                    <b>{{ $interview_request->user->first_name }}
                                        {{ $interview_request->user->last_name }}</b>, TIN#
                                    <b>{{ $interview_request->user->id_number }}</b>.</p>
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
