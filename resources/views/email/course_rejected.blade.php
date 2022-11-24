@extends('web.default.layouts.email')

@section('body')
    <!-- content -->
    <td valign="top" class="bodyContent" mc:edit="body_content">

        @if ($user->role_name == 'student')
            <p>Hi <b>{{ $user->first_name }}</b>, </p>
            <p>We regret to inform you that <b>{{ $course->teacher->first_name }} {{ $course->teacher->last_name }}</b> ID
                is not available to teach your <b>{{ $course->course_name }}</b>
                COURSE ID <b>{{ $course->course_code }}</b> course. Please log in to your classroom dashboard to select one
                of the following choices by
                clicking on the course STATUS lable:
            </p>

            <p>1. Receive refund for remaining classes</p>
            <p>2. Select an alternative tutor for your course</p>
            <p>3. Select MEtutors to assign an alternative tutor fo your course</p>
            <p>Details for this course booking are listed below:</p>
            <p>Invoice : <b>{{$course->course_order->invoice_id ?? null}}</b></p>
            <p>Booking ID : <b>{{$course->course_order->booking_id  ?? null}}</b></p>

            <p>In case of any queries, please contact the student success team at studentsucess@metutors.com</p>

            <p>Regards,</p>
            <p>Student Success Team</p>
        @endif

        @if ($user->role_name == 'teacher')
            <p>Hi <b>{{ $user->first_name }}</b>, </p>
            <p>You have declined to teach <b>{{ $course->course_name }}</b> ID <b>{{ $course->course_code }}</b>, booking
                number <b>{{ $course->course_order->bookin_id ?? null }}</b> to SIN
                <b>{{ $course->student->id_number }}</b>.
            </p>
            <p>If you have any queries please contact the teacher success team on teachersuccess@metutors.com. </b>.</p>
            <p>Regards,</p>
            <p>Teacher Success Team</p>
        @endif


        @if ($user->role_name == 'admin')
            <p>Hi <b>Teacher Sucess Team</b>, </p>
            <p>Teacher <b>{{ $course->teacher->first_name }}</b> TIN <b>{{ $course->teacher->id_number }}</b> has declined
                to teach course COURSE ID <b>{{ $course->course_code }}</b>, booking number
                <b>{{ $course->course_order->booking_id ?? null }}</b> to SIN <b>{{ $course->student->first_name }}</b>
                with invoice number <b>{{ $course->course_order->invoice_id ?? null }}</b>.
            </p>

            <p>Regards,</p>
            <p>MEtutors Registrar</p>
        @endif

    </td>
@endsection
