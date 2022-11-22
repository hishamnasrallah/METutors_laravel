@extends('web.default.layouts.email')

@section('body')
    <td valign="top" class="bodyContent" mc:edit="body_content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        @if ($user->role_name == 'teacher')
                            <div class="card-body">
                                <p>Hi <b>{{ $user->first_name }},</b></p>
                                <p>You have a class with <b>{{ $class->student->first_name }}</b> SIN <b>{{ $class->student->id_number }}</b> in 1 hour for <b>{{ $class->course->course_name }}</b> ID <b>{{ $class->course->course_code }}</b> at TIME <b>{{ $class->start_time }}</b>. </p>
                                <p>Please start the class on time.</p>
                                <p>Regards,</p>
                                <p>Teacher Success Team</p>
                            </div>
                        @endif

                        @if ($user->role_name == 'student')
                            <div class="card-body">
                                <p>Hi <b>{{ $user->first_name }},</b></p>
                                <p>You have a class with <b>{{ $class->teacher->first_name }}</b> TIN
                                    <b>{{ $class->teacher->id_number }}</b> in 1 hour for
                                        <b>{{ $class->course->course_name }}</b> ID
                                        <b>{{ $class->course->course_code }}</b> at <b>{{ $class->start_time }}</b>, </p>
                                <p>Please enter the classroom on time.</p>
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
