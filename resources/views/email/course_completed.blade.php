@extends('web.default.layouts.email')

@section('body')
    <!-- content -->
    <td valign="top" class="bodyContent" mc:edit="body_content">

        @if ($user->role_name == 'student')
            <p>Hi <b>{{ $user->first_name }}</b>, </p>
            <p>Congratiolations, all classes on course <b>{{ $course->course_name }}</b> ID
                <b>{{ $course->course_code }}</b>, booking number <b>{{ $course->course_order->booking_id }}</b> with your
                teacher <b>{{ $course->teacher->first_name }} {{ $course->teacher->last_name }}</b> TIN
                {{ $course->teacher->id_number }} have been completed. </p>
            <p>Please go under your account to download your certificate. Dont forget to leave your teacher a feedback on their profile.</p>
            <p>Discover more courses on MEtutors and continue learning! </p>

            <p>Regards,</p>
            <p>Student Success Team</p>
        @endif

        @if ($user->role_name == 'teacher')
            <p>Hi <b>{{ $user->first_name }}</b>, </p>
            <p>Congratulations, all classes on course <b>{{ $course->course_name }}</b> ID
                <b>{{ $course->course_code }}</b>, booking number <b>{{ $course->course_order->booking_id }}</b> with
                student <b>{{ $course->student->first_name }}</b> SIN
                <b>{{ $course->student->id_number }}</b> have been completed. Great efforts and well done.
            </p>
            <p>Enjoy teaching with MEtutors!</p>
            <p>Regards,</p>
            <p>Teacher Success Team</p>
        @endif


        @if ($user->role_name == 'admin')
            <p>Hi <b>Student Success Team</b>, </p>
            <p>Student <b>{{ $course->student->first_name }}</b> SIN <b>{{ $course->student->id_number }}</b> has
                completed COURSE ID <b>{{ $course->course_code }}</b>, booking number
                <b>{{ $course->course_order->booking_id }}</b> with <b>{{ $course->teacher->first_name }}</b>
                TIN <b>{{ $course->teacher->first_name }}</b>.
            </p>
            <p>Date of completion: <b>{{ $course->updated_at }}</b></p>
            <p>Regards,</p>
            <p>Success Team</p>
        @endif

    </td>
@endsection
