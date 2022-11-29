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
                                <p>You have successfully updated resources for your student
                                    <b>{{ $resource->class->student->first_name }}</b> SIN
                                    <b>{{ $resource->class->student->id_number }}</b> for
                                    course <b>{{ $resource->class->course->course_name }}</b> ID
                                    <b>{{ $resource->class->course->course_code }}</b>, booking number
                                    <b>{{ $resource->class->course->course_order->booking_id ?? null }}</b>. You can view
                                    the upadted resources on
                                    your dashboard.</p>
                                <p>Regards,</p>
                                <p>Teacher Success Team</p>
                            @endif

                            @if ($user->role_name == 'student')
                                <p>Hi <b>{{ $user->first_name }}</b>, </p>
                                <p>Your teacher <b>{{ $resource->class->teacher->first_name }}</b> has updated the resources
                                    for your course <b>{{ $resource->class->course->course_name }}</b> ID
                                    <b>{{ $resource->class->course->course_code }}</b>, booking number
                                    <b>{{ $resource->class->course->course_order->booking_id ?? null }}</b>. You can view
                                    and download the resources on your dashboard.
                                </p>
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
