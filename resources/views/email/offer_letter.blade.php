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
                                <p>Congratulations on successfully passing your interview! We are delighted to offer you the
                                    following subjects to teach on the MEtutors platform:
                                </p>
                                @foreach ($teacher_subjects as $teacher_subject)
                                    @if ($loop->first)
                                        <hr>
                                    @endif
                                    <p>Program: {{ $teacher_subject->program->name }}</p>
                                    <p>Study Field: {{ $teacher_subject->field->name }}</p>
                                    <p>Subject: {{ $teacher_subject->subject->name }}</p>
                                    <p>Hourly Rate: {{ $teacher_subject->hourly_price }} USD</p>
                                    @if($teacher_subject->program->id == 3)
                                    <p>Country: {{ $teacher_subject->country->name }} USD</p>
                                    <p>Grade: {{ $teacher_subject->grade }} </p>
                                    @endif
                                    <hr>
                                @endforeach

                                <p>Please reply to this email accepting the offer <b>within the next 48 hours</b>. We are excited
                                    to have you as part of the MEtutors teaching team. If you have any queries you can
                                    contact the support team here
                                    @php
                                        echo 'https://metutors.com/contact';
                                    @endphp.</p>
                                <p>Regards,</p>
                                <p>Talent Acquisition Team</p>
                            </div>
                        @endif


                    </div>
                </div>
            </div>
        </div>
    </td>
@endsection
