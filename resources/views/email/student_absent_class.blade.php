@extends('web.default.layouts.email')

@section('body')
    <!-- content -->
    <td valign="top" class="bodyContent" mc:edit="body_content">
        @if ($user->role_name == 'student')
            <p>Hi <b>{{ $user->first_name }}</b>, </p>
            <p> You missed a class today on your course <b>{{ $class->course->course_name }}</b> ID
                <b>{{ $class->course->course_code }}</b> course with booking number {{ $class->course->order->booking_id ?? null }}.
            </p>
            <p>You can purchase additional classes to make-up for any missed class from your classroom dashboard.</p>
            <p>In case of any queries, please contact the student success team at studentsucess@metutors.com</p>
            <p>Regards,</p>
            <p>MEtutors Success Team</p>
        @endif

        @if ($user->role_name == 'teacher')
            <p>Hi <b>{{ $user->first_name }}</b>, </p>
            <p> Your student <b>{{ $class->student->first_name }}</b> SIN <b>{{ $class->student->id_number }}</b> missed the
                class today for course <b>{{ $class->course->course_name }}</b> ID <b>{{ $class->course->course_code }}</b>,
                booking number <b>{{ $class->course->order->booking_id ?? null }}</b>.</p>
            <p>Please use the inbox tool to contact your student and remind them they can purchase additional classes if
                necessary and to avoid missing more classes</p>
            <p>Regards,</p>
            <p>Teacher Success Team</p>

        @endif

        @if ($user->role_name == 'admin')
            <p>Hi <b>Student Success Team</b>, </p>
            <p>Student <b>{{ $class->student->first_name }}</b> SIN <b>{{ $class->student->id_number }}</b> was absent
                from class with <b>{{ $class->teacher->first_name }}</b> TIN <b>{{ $class->teacher->id_number }}</b> on
                course <b>{{ $class->course->course_name }}</b>
                ID <b>{{ $class->course->course_code }}</b>, booking number
                <b>{{ $class->course->order->booking_id ?? null }}</b>.
            </p>
            <p>Regards,</p>
            <p>Success Team</p>
        @endif


    </td>
@endsection
