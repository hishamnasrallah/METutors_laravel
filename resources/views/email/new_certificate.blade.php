@extends('web.default.layouts.email')

@section('body')
    @php
        use Carbon\Carbon;
    @endphp
    <td valign="top" class="bodyContent" mc:edit="body_content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        @if ($user->role_name == 'student')
                            <div class="card-body">
                                <p>Hi <b>{{ $user->first_name }},</b></p>
                                <p>Congrats on completing your course <b>{{ $certificate->course->course_name }}</b> ID
                                    <b>{{ $certificate->course->course_code }}</b>, booking number
                                    <b>{{ $certificate->course->course_order->booking_id ?? null }}</b> with
                                    <b>{{ $certificate->course->teacher->first_name }}</b> on MEtutors. You can now log in
                                    to your dashboard to download your certificate.
                                </p>
                                <p>In case of any queries, please contact the student success team at
                                    studentsucess@metutors.com.</p>
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
