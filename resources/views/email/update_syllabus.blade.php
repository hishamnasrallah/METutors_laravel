@extends('web.default.layouts.email')

@section('body')
    <td valign="top" class="bodyContent" mc:edit="body_content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            @if ($user->role_name == 'teacher')
                                <p>Hi <b>{{ $user->first_name }}</b>, </p>
                                <p>Y
                                    You have updated the syllabus for your student
                                    <b>{{ $topic->class_topic->student->first_name }}</b> ID
                                    <b>{{ $topic->class_topic->student->id_number }}</b> for course
                                    <b>{{ $topic->class_topic->course->course_name }}</b> ID <b>{{ $topic->class_topic->course->course_code }}</b>,
                                    booking number <b>{{ $topic->class_topic->course->course_order->booking_id ?? null }}</b>
                                </p>
                                <p>If you need to make any further changes you can do it on your dashboard.</p>
                                <p>Regards,</p>
                                <p>Teacher Success Team</p>
                            @endif

                            @if ($user->role_name == 'student')
                                <p>Hi <b>{{ $user->first_name }}</b>, </p>
                                <p>Your teacher <b>{{ $topic->class_topic->teacher->first_name }}</b> TIN
                                    <b>{{ $topic->class_topic->teacher->id_number }}</b> has updated the syllabus for your course
                                    <b>{{ $topic->class_topic->course->course_name }}</b> ID <b>{{ $topic->class_topic->course->course_code }}</b>.
                                </p>
                                <p> You can view the syllabus update on your dashboard.</p>
                                <p>Regards,</p>
                                <p>Student Success Team</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </td>
@endsection
