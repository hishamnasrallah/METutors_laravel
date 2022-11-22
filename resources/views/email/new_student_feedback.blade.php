@extends('web.default.layouts.email')

@section('body')
    <td valign="top" class="bodyContent" mc:edit="body_content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">

                            @if ($user->role_name == 'teacher')
                                <p>Hi <b>Teacher Sucess Team,</b></p>
                                <p>You have received feedback from <b>{{ $feedback->sender->first_name }}</b> on
                                    <b>{{ $feedback->course->course_name }}</b> ID
                                    <b>{{ $feedback->course->course_code }}</b> with booking number
                                    <b>{{ $feedback->course->course_order->booking_id ?? null }}</b>. The feedback is now visible on
                                    your
                                    teacher profile.
                                </p>
                                <p>If you have any queries please contact the teacher success team on
                                    teachersuccess@metutors.com.</p>

                                <p>Regards,</p>
                                <p>Teacher Success Team</p>
                            @endif

                            @if ($user->role_name == 'student')
                                <p>Hi <b>{{ $user->first_name }},</b></p>
                                <p>Your feedback for course <b>{{ $feedback->course->course_name }}</b> ID
                                    <b>{{ $feedback->course->course_code }}</b> with teacher
                                    <b>{{ $feedback->reciever->first_name }}</b> has been submitted
                                    successfully. You can review your feedback on your dashboard.</b>
                                </p>
                                <p>If you have any queries, please contact the student success team at
                                    studentsucess@metutors.com.</p>

                                <p>Regards,</p>
                                <p>Student Success Team</p>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </td>
@endsection
