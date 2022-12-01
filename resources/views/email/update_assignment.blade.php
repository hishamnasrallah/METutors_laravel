@extends('web.default.layouts.email')

@section('body')
    <td valign="top" class="bodyContent" mc:edit="body_content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        @if ($user->role_name == 'teacher')
                            <div class="card-body">
                                <p>Hi <b>{{ $user->first_name }},</b> </p>
                                <p>You have successfully updated the assignment for student
                                    <b>{{ $assignment->course->student->first_name }}</b> SIN
                                    <b>{{ $assignment->course->student->id_number }}</b> for the course
                                    <b>{{ $assignment->course->course_name }}</b>
                                    ID <b>{{ $assignment->course->course_code }}</b>, booking number
                                    <b>{{ $assignment->course->course_order->booking_id ?? null }}</b>.Your student has been
                                    notified.
                                </p>
                                <p>Regards,</p>
                                <p>Teacher Success Team</p>
                            </div>
                        @endif

                        @if ($user->role_name == 'student')
                            <div class="card-body">
                                <p>Hi <b>HR,</b> </p>
                                <p>Your teacher <b>{{ $assignment->course->teacher->first_name }}</b> has updated your
                                    assignment for the
                                    course <b>{{ $assignment->course->course_name }}</b> ID
                                    <b>{{ $assignment->course->course_code }}</b>, booking number
                                    <b>{{ $assignment->course->course_order->booking_id ?? null }}</b>. You can view your
                                    assignment on your dashboard.
                                </p>
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
