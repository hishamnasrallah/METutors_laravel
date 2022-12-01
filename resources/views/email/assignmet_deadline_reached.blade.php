@extends('web.default.layouts.email')

@section('body')
    <!-- content -->
    <td valign="top" class="bodyContent" mc:edit="body_content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">

                        @if ($user->role_name == 'student')
                            <div class="card-body">
                                <p>Hi <b>{{ $user->first_name }},</b> </p>
                                <p>You have failed to upload your assignment for course
                                    <b>{{ $assignment->course->course_name }}</b> ID
                                    <b>{{ $assignment->course->course_code }}</b> on time and have
                                    now missed the deadline. Please upload your assignmemt as soon as possible or use the
                                    inbox tool to contact your teacher. Please note this assignment will now be marked as
                                    late.
                                </p>
                                <p>Kind Regards,</p>
                                <p>Student Success Team</p>
                            </div>
                        @endif

                        @if ($user->role_name == 'teacher')
                            <div class="card-body">
                                <p>Hi <b>{{ $user->first_name }},</b> </p>
                                <p>Your student <b>{{ $assignment->course->student->first_name }}</b> SIN
                                    <b>{{ $assignment->course->student->id_number }}</b> has missed their assignment
                                    deadline for course
                                    <b>{{ $assignment->course->course_name }}</b> ID
                                    <b>{{ $assignment->course->course_code }}</b>, booking number
                                    <b>{{ $assignment->course->course_order->booking_id ?? null }}</b>. Please use the inbox
                                    tool to contact them.
                                </p>
                                <p>If you have any queries please contact the teacher success team on
                                    teachersuccess@metutors.com. </p>
                                <p>Regards,</p>
                                <p>Teacher Success Team</p>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </td>
@endsection
