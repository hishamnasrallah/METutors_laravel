@extends('web.default.layouts.email')

@section('body')
    <!-- content -->
    <td valign="top" class="bodyContent" mc:edit="body_content">

        @if ($user->role_name == 'student')
            <p>Hi <b>{{ $user->first_name }}</b>, </p>
            <p>Congratulations, <b>{{ $course->teacher->first_name }}</b> TEACHER ID
                <b>{{ $course->teacher->id_number }}</b> has agreed to teach your <b>{{ $course->course_name }}</b>, COURSE
                ID <b>{{ $course->course_code }}</b>
                course on MEtutors.
            </p>
            <p>Your course will be on <b>{{ json_encode($course_days) }}</b> at <b>{{ $course->start_time }}</b> -
                <b>{{ $course->end_time }}</b>. The course starts on <b>{{ $course->start_date }}</b> and finishes on
                <b>{{ $course->end_date }}</b>.
            </p>
            <p>You can find all of these details on your student dashboard. You will receive your class schedule
                confirmation shortly.</p>
            <p>In case of any queries, please contact the student success team at studentsucess@metutors.com.</p>

            <p>Regards,</p>
            <p>Student Success Team</p>
        @endif

        @if ($user->role_name == 'teacher')
            <p>Hi <b>{{ $user->first_name }}</b>, </p>
            <p>Thank you for accepting to teach the new course on MEtutors platform.</p>
            <p>You will be teaching <b>{{ $course->course_name }}</b> <b>{{ $course->course_code }}</b>, booking number
                <b>{{ $course->course_order->booking_id ?? null }}</b> to <b>{{ $course->student->first_name }}</b> SIN
                <b>{{ $course->student->id_number }}</b>. The course will
                begin on <b>{{ $course->start_date }}</b> and end on <b>{{ $course->end_date }}</b>. The course will be on
                <b>{{ json_encode($course_days) }}</b> at <b>{{ $course->start_time }} - {{ $course->end_time }}</b>.
            </p>
            <p>You can find details for this new course on your teacher dashboard.</p>
            <p>If you have any queries please contact the teacher success team at teachersuccess@metutors.com.</p>
            <p>Regards,</p>
            <p>Teacher Success Team</p>
        @endif


        @if ($user->role_name == 'admin')
            <p>Hi <b>Teacher Sucess Team</b>, </p>
            <p>Teacher <b>{{ $course->teacher->first_name }}</b> TIN <b>{{ $course->teacher->id_number }}</b> has accepted
                to teach course COURSE ID <b>{{ $course->course_code }}</b> successfully to
                <b>{{ $course->student->first_name }}</b>
                SIN <b>{{ $course->student->id_number }}</b> with invoice number
                <b>{{ $course->course_order->invoice_id ?? null }}</b>.
            </p>
            <p>Here are the details about this booking:</p>
            <p>Booking ID : <b>{{ $course->course_order->booking_id ?? null }}</b></p>
            <p>Program Name: <b>{{ $course->program->name }}</b></p>
            <p>Field Study: <b>{{ $course->field->name }}</b></p>
            @if ($teacher_grade)
                <p>Grade Level: <b>{{ $teacher_grade }}</b></p>
            @endif
            <p>Course ID: <b>{{ $course->course_code }}</b></p>
            <p>Course Name: <b>{{ $course->course_name }}</b></p>
            <p>Days: <b>{{ json_encode($course_days) }}</b></p>
            <p>Start / End Date: <b>{{ $course->start_date }} / {{ $course->end_date }}</b></p>
            <p>Start / End Time: <b>{{ $course->start_time }} / {{ $course->end_time }}</b></p>

            <p>Regards,</p>
            <p>MEtutors Registrar</p>
        @endif

    </td>
@endsection
