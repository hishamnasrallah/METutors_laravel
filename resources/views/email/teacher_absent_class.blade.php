@extends('web.default.layouts.email')

@section('body')
    <!-- content -->
    <td valign="top" class="bodyContent" mc:edit="body_content">
        @if ($user->role_name == 'student')
            <p>Hi <b>{{ $user->first_name }}</b>, </p>
            <p>Due to unforseen circumstances, your teacher <b>{{ $class->teacher->first_name }}</b> TIN
                <b>{{ $class->teacher->id_number }}</b> could not make it to class today. We apologize for any inconveniance
                this have caused.
            </p>
            <p>Please proceed with scheduling your make-up class for <b>{{ $class->course->course_name }}</b> ID
                <b>{{ $class->course->course_code }}</b> from your classroom dashboard.</p>
            <p>Please feel free to contact our student success team at studentsucess@metutors.com if you have any queries.</p>
            <p>Regards,</p>
            <p>Student Success Team</p>
        @endif

        @if ($user->role_name == 'teacher')
            <p>Hi <b>{{ $user->first_name }}</b>, </p>
            <p> Your did not attend your class on course <b>{{ $class->course->course_name }}</b> ID
                <b>{{ $class->course->course_code }}</b>,
                at the scheduled time with your student <b>{{ $class->student->first_name }}</b> SIN
                <b>{{ $class->student->id_number }}</b>, booking number
                <b>{{ $class->course->order->booking_id ?? null }}</b>.
            </p>
            <p>Please use the inbox tool to message your student and explain why you weren't in class. Your student will be
                offered a make-up class.</p>
            <p>Regards,</p>
            <p>Teacher Success Team</p>
        @endif

        @if ($user->role_name == 'admin')
            <p>Hi <b>Teacher Success Team</b>, </p>
            <p>Teacher <b>{{ $class->teacher->first_name }}</b> ID <b>{{ $class->teacher->id_number }}</b> was absent
                from class with <b>{{ $class->student->first_name }}</b> SIN <b>{{ $class->student->id_number }}</b> on
                course <b>{{ $class->course->course_name }}</b>
                ID <b>{{ $class->course->course_code }}</b>, booking number
                <b>{{ $class->course->order->booking_id ?? null }}</b>.
            <p>A reminder email will be sent to <b>{{ $class->student->first_name }}</b> to schedule a make-up class.</p>
            </p>
            <p>Regards,</p>
            <p>Success Team</p>
        @endif


    </td>
@endsection
