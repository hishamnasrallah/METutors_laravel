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

                                <p>You have rejected the assignment for student
                                    <b>{{ $assignment->course->student->first_name }}</b> SIN
                                    <b>{{ $assignment->course->student->id_number }}</b> for the
                                    course <b>{{ $assignment->course->course_name }}</b> ID
                                    <b>{{ $assignment->course->course_code }}</b>, booking number <b>
                                        {{ $assignment->course->course_order->booking_id ?? null }}</b>. Your student has
                                    been notified.
                                </p>

                                <p>Details on the assignment are listed here:</p>
                                <p>Title: <b>{{ $assignment->title }}</b></p>
                                <p>Assigned on:
                                    <b>{{ Carbon::parse($assignment->assignee->created_at)->format('Y M d h:i a') }}</b>
                                </p>
                                <p>Due date: <b>{{ Carbon::parse($assignment->deadline)->format('Y M d') }}</b></p>
                                <p>Teacher,s rejection date:
                                    <b>{{ Carbon::parse($assignment->assignee->rejected_at)->format('Y M d') }}</b>
                                </p>
                                <p>Number of days for grading: <b>{{ $assignment->assignee->teacher_feedback->rating }} out
                                        of 10</b></p>

                                <p>
                                    The more homework you assign to your student and the faster grading response you
                                    provide, the more value given to our students. We thank you for being part of this
                                    process.
                                </p>

                                <p>Regards,</p>
                                <p>Teacher Success Team</p>
                            </div>
                        @endif

                        @if ($user->role_name == 'student')
                            <div class="card-body">
                                <p>Hi <b>{{ $user->first_name }},</b> </p>

                                <p>Your teacher <b>{{ $assignment->course->teacher->first_name }}</b> has rejected your
                                    assignment that you submitted for your
                                    course <b>{{ $assignment->course->course_name }}</b> ID
                                    <b>{{ $assignment->course->course_code }}</b>, booking number
                                    <b>{{ $assignment->course->course_order->booking_id ?? null }}</b>. You can view your
                                    assignment with your teacher,s feedback on your dashboard.Kindly make sure to resubmit
                                    your assignment before the new due date.
                                </p>
                                <p>Teacher,s feedabck: {{ $assignment->assignee->teacher_feedback->review }}</p>
                                <p>Enjoy learning with MEtutors!</p>

                                <p>Regards,</p>
                                <p>Student Success Team</p>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </td>
@endsection
