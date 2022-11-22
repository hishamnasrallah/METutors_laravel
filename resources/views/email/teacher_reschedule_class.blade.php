@extends('web.default.layouts.email')

@section('body')
    <td valign="top" class="bodyContent" mc:edit="body_content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">

                            @if ($user->role_name == 'teacher')
                                <p>Hi <b>{{ $user->first_name }},</b></p>
                                <p>You have rescheduled a class with <b>{{ $class->course->student->first_name }}</b> SIN
                                    <b>{{ $class->course->student->id_number }}</b> on your
                                    course <b>{{ $class->course->course_name }}</b> ID <b>{{ $class->course->course_code }}</b>,
                                    booking number <b>{{ $class->course->course_order->booking_id ?? null }}</b> for the
                                    following
                                    day and time:
                                </p>


                                @php
                                    $day = '';
                                    if ($class->day == 1) {
                                        $day = 'Sunday';
                                    } elseif ($class->day == 2) {
                                        $day = 'Monday';
                                    } elseif ($class->day == 3) {
                                        $day = 'Tuesday';
                                    } elseif ($class->day == 4) {
                                        $day = 'Wednesday';
                                    } elseif ($class->day == 5) {
                                        $day = 'Thursday';
                                    } elseif ($class->day == 6) {
                                        $day = 'Friday';
                                    } else {
                                        $day = 'Saturday';
                                    }
                                @endphp

                                <p>Previous class: {{ $day }} - {{ $class->start_date }} -
                                    {{ $class->start_time }}</p>
                                <p>Rescheduled class: {{ $day }} - {{ $class->start_date }} -
                                    {{ $class->start_time }}</p>

                                <p>Regards,</p>
                                <p>Teacher Success Team</p>
                            @elseif ($user->role_name == 'student')
                                <p>Hi <b>{{ $user->first_name }},</b></p>

                                <p>Your teacher <b>{{ $class->teacher->first_name }}</b> TIN
                                    <b>{{ $class->teacher->id_number }}</b> has rescheduled a class on you course
                                    <b>{{ $class->course->course_name }}</b> ID <b>{{ $class->course->course_code }}</b>,
                                    booking number <b>{{ $class->course->course_order->booking_id ?? null }}</b> for the
                                    following
                                    day and time:
                                </p>

                                @php
                                    $day = '';
                                    if ($class->day == 1) {
                                        $day = 'Sunday';
                                    } elseif ($class->day == 2) {
                                        $day = 'Monday';
                                    } elseif ($class->day == 3) {
                                        $day = 'Tuesday';
                                    } elseif ($class->day == 4) {
                                        $day = 'Wednesday';
                                    } elseif ($class->day == 5) {
                                        $day = 'Thursday';
                                    } elseif ($class->day == 6) {
                                        $day = 'Friday';
                                    } else {
                                        $day = 'Saturday';
                                    }
                                @endphp

                                <p>Previous class: {{ $day }} - {{ $class->start_date }} -
                                    {{ $class->start_time }}</p>
                                <p>Rescheduled class: {{ $day }} - {{ $class->start_date }} -
                                    {{ $class->start_time }}</p>
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
