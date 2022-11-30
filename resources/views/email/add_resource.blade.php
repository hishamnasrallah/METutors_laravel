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
                                <p>You have successfully added resources for your student
                                    <b>{{ $resource->class->student->first_name }}</b> SIN
                                    <b>{{ $resource->class->student->id_number }}</b> for course
                                    <b>{{ $resource->class->course->course_name }}</b> ID
                                    <b>{{ $resource->class->course->course_code }}</b>, booking number
                                    <b>{{ $resource->class->course->course_order->booking_id }}</b>. You can view all of the
                                    resources you have added on your dashboard.
                                </p>
                                <p>Regards,</p>
                                <p>Teacher Success Team</p>
                            </div>
                        @endif

                        @if ($user->role_name == 'student')
                            <div class="card-body">
                                <p>Hi <b>HR,</b> </p>
                                <p>Your teacher <b>{{ $resource->class->teacher->first_name }}</b> TIN <b>{{ $resource->class->teacher->id_number }}</b> has added new resources for your course <b>{{ $resource->class->course->course_name }}</b> ID
                                    <b>{{ $resource->class->course->course_code }}</b>, booking number <b>{{ $resource->class->course->course_order->booking_id }}</b>. You can view and download the resources on your dashboard.
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
