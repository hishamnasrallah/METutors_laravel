@extends('web.default.layouts.email')

@section('body')
    <!-- content -->
    <td valign="top" class="bodyContent" mc:edit="body_content">

        @if ($user->role_name == 'student')
            <p>Hi <b>{{ $user->first_name }}</b>, </p>
            <p>This is a reminder email to let you know that your class <b>{{ $class->title }}</b> for course
                <b>{{ $class->course->course_name }}</b>
                ID <b>{{ $class->course->course_name }}</b>
                has started with teacher <b>{{ $class->teacher->first_name }}</b> TIN <b>{{ $class->teacher->id_number }}</b>.</p>
                <p>Please go to your dashboard to access the classroom.</p>
            <p>Regards,</p>
            <p>Student Success Team</p>
        @endif

        @if ($user->role_name == 'teacher')
            <p>Hi <b>{{ $user->first_name }}</b>, </p>
            <p>Your class <b>{{ $class->title }} </b> with <b>{{ $class->student->first_name }}</b> SIN
                <b>{{ $class->student->id_number }}</b> cours <b>{{ $class->course->course_name }}</b> ID
                <b>{{ $class->course->course_code }}</b> with booking number
                <b>{{ $class->course->order->booking_id ?? null }}</b> has started.</p>
            <p> Please go to your dashboard to access the classroom.</p>
            <p>Regards,</p>
            <p>Teacher Success Team</p>
            @endif

    </td>
@endsection
