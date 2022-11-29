@extends('web.default.layouts.email')

@section('body')
    <td valign="top" class="bodyContent" mc:edit="body_content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        @if ($user->role_name == 'teacher')
                            <div class="card-body">
                                <p>Hi <b>{{ $user->first_name }},</b> </p>
                                <p>Your student <b>{{ $topic->course->student->first_name }}</b> SIN <b>{{ $topic->course->student->id_number }}</b> has added a new topic for course <b>{{ $topic->course->course_name }}</b> ID <b>{{ $topic->course->course_code }}</b>.
                                    You can see the new topic on your dashboard.
                                </p>
                                <p>Regards,</p>
                                <p>Teacher Success Team</p>
                            </div>
                        @endif

                        @if ($user->role_name == 'student')
                            <div class="card-body">
                                <p>Hi <b>{{ $user->first_name }},</b> </p>
                                <p>You have successfully added a new topic for your course
                                    <b>{{ $topic->course->course_name }}</b> ID <b>{{ $topic->course->course_code }}</b>
                                    with teacher
                                    <b>{{ $topic->course->teacher->first_name }}</b> TIN
                                    <b>{{ $topic->course->teacher->id_number }}</b>. You can view all of your topics on your
                                    dashboard
                                </p>
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
