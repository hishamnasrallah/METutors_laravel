@extends('web.default.layouts.email')

@section('body')
    <td valign="top" class="bodyContent" mc:edit="body_content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            //------------Documents uploaded by teacher---------------
                            @if ($uploaded_by == 'teacher')
                                @if ($user->role_name == 'teacher')
                                    <p>Hi <b>{{ $user->first_name }}</b>, </p>
                                    <p>You have successfully added a new document for your student
                                        <b>{{ $resource->course->student->first_name }}</b> SIN
                                        <b>{{ $resource->course->student->id_number }}</b> for
                                        course <b>{{ $resource->course->course_name }}</b> ID
                                        <b>{{ $resource->course->course_code }}</b>, booking number
                                        <b>{{ $resource->course->course_order->booking_id ?? null }}</b>.Your student has
                                        been
                                        notified. .
                                    </p>
                                    <p>Regards,</p>
                                    <p>Teacher Success Team</p>
                                @endif

                                @if ($user->role_name == 'student')
                                    <p>Hi <b>{{ $user->first_name }}</b>, </p>
                                    <p>Your teacher <b>{{ $resource->course->teacher->first_name }}</b> added a new document
                                        for your course <b>{{ $resource->course->course_name }}</b> ID
                                        <b>{{ $resource->course->course_code }}</b> that needs your review. You can find
                                        this
                                        under Other Documents on your Classroom dashboard.

                                    </p>
                                    <p>Regards,</p>
                                    <p>Student Success Team</p>
                                @endif
                            @endif

                            //------------Documents uploaded by student---------------
                            @if ($uploaded_by == 'student')
                                @if ($user->role_name == 'student')
                                    <p>Hi <b>{{ $user->first_name }}</b>, </p>
                                    <p>You have successfully uploaded a new document for your teacher
                                        <b>{{ $resource->course->teacher->first_name }}</b> TIN
                                        <b>{{ $resource->course->teacher->id_number }}</b> for
                                        your course <b>{{ $resource->course->course_name }}</b> ID
                                        <b>{{ $resource->course->course_code }}</b> to review. You can find this under
                                        Other Documents on
                                        your Classroom dashboard.
                                    </p>
                                    <p>Regards,</p>
                                    <p>Student Success Team</p>
                                @endif

                                @if ($user->role_name == 'teacher')
                                    <p>Hi <b>{{ $user->first_name }}</b>, </p>
                                    <p>Your student <b>{{ $resource->course->student->first_name }}</b> ID
                                        <b>{{ $resource->course->student->id_number }}</b> for course
                                        <b>{{ $resource->course->course_name }}</b> ID
                                        <b>{{ $resource->course->course_code }}</b>, booking number
                                        <b>{{ $resource->course->course_order->booking_id ?? null }}</b>
                                        has added a new document that needs your review. You can find this under Other
                                        Documents on your Classroom dashboard.

                                    </p>
                                    <p>Regards,</p>
                                    <p>Teacher Success Team</p>
                                @endif
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </td>
@endsection
