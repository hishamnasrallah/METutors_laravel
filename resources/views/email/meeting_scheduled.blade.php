@extends('web.default.layouts.email')

@section('body')
@php
    use Carbon\Carbon;
@endphp
    <td valign="top" class="bodyContent" mc:edit="body_content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        @if ($user->role_name == 'teacher')
                            <div class="card-body">
                                <p>Hi <b>{{ $user->first_name }},</b> </p>
                                <p>Your application to join MEtutors teaching platofrm was reviewed by our talent acquisition
                                    team and an interview has been scheduled on
                                    <b>{{ Carbon::parse($interview_request->date_for_interview)->setTimeZone('US/Central')->format('Y-m-d') }}</b>,
                                    <b>{{ Carbon::parse($interview_request->time_for_interview)->setTimeZone('US/Central')->format('h:i A') }}</b> (US/Central) timezone.
                                    
                                </p>

                                <p>If you wish to change your interveiw date or time, please conatct us at
                                    @php echo "https://metutors.com/contact" @endphp.</p>
                                <p>Regards,</p>
                                <p>Talent Acquisition Team</p>
                            </div>
                        @endif

                        @if ($user->role_name == 'admin')
                            <div class="card-body">
                                <p>Hi <b>HR,</b></p>
                                <p>A new interview request has been scheduled and the details have been sent to
                                    <b>{{ $interview_request->user->first_name }}
                                        {{ $interview_request->user->last_name }}</b>, TIN#
                                    <b>{{ $interview_request->user->id_number }}</b>.</p>
                                    <p>Please proceed with the interview process.</p>
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
