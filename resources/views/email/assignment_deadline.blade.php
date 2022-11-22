@extends('web.default.layouts.email')

@section('body')
    <!-- content -->
    <td valign="top" class="bodyContent" mc:edit="body_content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        @if ($reminder == 1)
                            @if ($user->role_name == 'student')
                                <div class="card-body">
                                    <p>Hi <b>{{ $user->first_name }},</b> </p>
                                    <p>You have an assignment due tomorrow for your course <b>{{ $course->course_name }}</b>
                                        ID
                                        <b>{{ $course->course_code }}</b>. Please go to your student dashboard to submit
                                        your
                                        assignment on time.
                                    </p>
                                    <p>Regards,</p>
                                    <p>Student Success Team</p>
                                </div>
                            @endif

                            @if ($user->role_name == 'teacher')
                                <div class="card-body">
                                    <p>Hi <b>{{ $user->first_name }},</b> </p>
                                    <p>Your student <b>{{ $course->student->name }},</b> SIN
                                        <b>{{ $course->student->id_number }},</b> is due to
                                        submit an assigment tomorrow for course <b>{{ $course->course_name }}</b> ID
                                        <b>{{ $course->course_code }}</b>, booking number <b>{{$order->booking_id}}</b>.
                                    </p>
                                    <p>You can use the inbox tool to send them a polite reminder.</p>
                                    <p>Regards,</p>
                                    <p>Student Success Team</p>
                                </div>
                            @endif
                        @else
                            @if ($user->role_name == 'student')
                                <div class="card-body">
                                    <p>Hi <b>{{ $user->first_name }},</b> </p>
                                    <p>You have an assignment due today before 11:59 PM, for your course COURSE <b>{{ $course->course_name }}</b> ID <b>{{ $course->course_code }}</b>. Please
                                        go to your student dashboard to submit your assignment before the deadline.
                                    </p>
                                    <p>Regards,</p>
                                    <p>Student Success Team</p>
                                </div>
                            @endif

                            @if ($user->role_name == 'teacher')
                                <div class="card-body">
                                    <p>Hi <b>{{ $user->first_name }},</b> </p>
                                    <p>Your student <b>{{ $course->student->name }},</b> SIN
                                        <b>{{ $course->student->id_number }},</b> is due to
                                        submit an assigment tomorrow for course <b>{{ $course->course_name }}</b> ID
                                        <b>{{ $course->course_code }}</b>, booking number <b>{{$order->booking_id}}</b>.
                                    </p>
                                    <p>You can use the inbox tool to send them a polite reminder.</p>
                                    <p>Regards,</p>
                                    <p>Teacher Success Team</p>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </td>
@endsection
