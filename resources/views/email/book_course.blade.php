@extends('web.default.layouts.email')

@section('body')
    <td valign="top" class="bodyContent" mc:edit="body_content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">

                            @if ($user->role_name == 'admin')
                                <p>Hi <b>{{ $user->first_name }},</b></p>
                                <p><b>{{ $course->student->first_name }}</b> SIN <b>{{ $course->student->id_number }}</b>
                                    has completed the booking for COURSE ID <b>{{ $course->course_code }}</b> successfully.
                                    The
                                    invoice number for this booking order is
                                    <b>{{ $course->order->booking_id ?? null }}</b>.</b>.
                                </p>
                                <p>Details for this course booking are listed below:</p>
                                <p>Invoice ID: <b>{{ $course->order->invoice_id ?? null }}</b></p>
                                <p>Booking ID: <b>{{ $course->order->booking_id ?? null }}</b></p>
                                <p>Program Name: <b>{{ $course->program->name }}</b></p>
                                <p>Field Study: <b>{{ $course->field->name }}</b></p>
                                <p>Grade Level: <b>{{ $course->field->name }}</b></p>
                                <p>Course Name: <b>{{ $course->course_name }}</b></p>
                                <p>Course ID: <b>{{ $course->course_code }}</b></p>
                                <p>Total hours: <b>{{ $course->total_hours }}</b></p>
                                <p>Total classes: <b>{{ $course->total_classes }}</b></p>
                                <p>Days: <b>{{ $course->days }}</b></p>
                                <p>Start / End Date: <b>{{ $course->start_date }}/{{ $course->end_date }}</b></p>
                                <p>Start / End Time: <b>{{ $course->start_time }}/{{ $course->end_time }}</b></p>
                                <p>Tutor name: <b>{{ $course->teacher->first_name }}</b></p>
                                <p>TIN: <b>{{ $course->teacher->id_number }}</b></p>
                                <p>Regards,</p>
                                <p>MEtutors Registrar</p>
                            @elseif ($user->role_name == 'teacher')
                                <p>Hi <b>Teacher Sucess Team,</b></p>
                                <p>Congratulations, you have been assigned a new course. Please access your dashboard to
                                    accept the new course within the next 4 hours.</b>.
                                </p>
                                <p>Failing to doing so will result in losing the opportunity to teach this course on
                                    MEtutors.</p>
                                <p>Details for this course booking are listed below:</p>
                                <p>SIN: <b>{{ $course->student->id_number }}</b></p>
                                <p>Booking ID: <b>{{ $course->order->booking_id }}</b></p>
                                <p>Program Name: <b>{{ $course->program->name }}</b></p>
                                <p>Field Study: <b>{{ $course->field->name }}</b></p>
                                <p>Course ID: <b>{{ $course->course_code }}</b></p>
                                <p>Course Name: <b>{{ $course->course_name }}</b></p>
                                <p>Days: <b>{{ $course->days }}</b></p>
                                <p>Start / End Date: <b>{{ $course->start_date }}/{{ $course->end_date }}</b></p>
                                <p>Start / End Time: <b>{{ $course->start_time }}/{{ $course->end_time }}</b></p>

                                <p>If you have any queries please contact the teacher success team at
                                    teachersuccess@metutors.com. </p>

                                <p>Regards,</p>
                                <p>Teacher Success Team</p>
                            @else
                                <p>Hi <b>{{ $user->first_name }},</b></p>
                                <p>Congratulations, we have received your new course booking for
                                    <b>{{ $course->course_name }}</b> COURSE ID <b>{{ $course->course_code }}</b>
                                    on MEtutors. We are excited to help you acheive your learning goals! </b>.
                                </p>
                                <p>Your teacher has been notified and a class schedule confirmation will be sent once they
                                    confirm. If for any reason the teacher you selected is not able to teach this course,
                                    you will be able to select a replacement teacher or will receive a full refund.</p>
                                <p>Details for this course booking are listed below:</p>
                                <p>Invoice ID: <b>{{ $course->order->invoice_id ?? null }}</b></p>
                                <p>Booking ID: <b>{{ $course->order->booking_id ?? null }}</b></p>
                                <p>Program Name: <b>{{ $course->program->name }}</b></p>
                                <p>Field Study: <b>{{ $course->field->name }}</b></p>
                                <p>Grade Level: <b>{{ $grade }}</b></p>
                                <p>Course Name: <b>{{ $course->course_name }}</b></p>
                                <p>Course ID: <b>{{ $course->course_code }}</b></p>
                                <p>Total hours: <b>{{ $course->total_hours }}</b></p>
                                <p>Total classes: <b>{{ $course->total_classes }}</b></p>
                                <p>Days: <b>{{ $course->weekdays }}</b></p>
                                <p>Start / End Date: <b>{{ $course->start_date }}/{{ $course->end_date }}</b></p>
                                <p>Start / End Time: <b>{{ $course->start_time }}/{{ $course->end_time }}</b></p>
                                <p>Tutor name: <b>{{ $course->teacher->first_name }}</b></p>
                                <p>TIN: <b>{{ $course->teacher->id_number }}</b></p>
                                <p>Regards,</p>
                                <p>MEtutors Registrar</p>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </td>
@endsection
