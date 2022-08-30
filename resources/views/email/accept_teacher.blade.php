@extends('web.default.layouts.email')

@section('body')
    <td valign="top" class="bodyContent" mc:edit="body_content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        @if ($user->role_name == 'teacher')
                            <div class="card-body">
                                <p>Hi <b>{{ $user->first_name }} {{ $user->last_name }},</b> </p>
                                <p>Congratulations on your approval as a teacher TIN: <b>{{ $user->id_number }}</b>for the
                                    MEtutors platform. We welcome you
                                    to our family and wish you the best!</p>
                                <p> In case you have any queries, feel free to reach out to our support team at link here -
                                    @php
                                        echo 'https://metutors.com/about';
                                    @endphp
                                </p>
                                <p>Regards,</p>
                                <p>Talent Acquisition Team</p>
                            </div>
                        @endif

                        @if ($user->role_name == 'admin')
                            <div class="card-body">
                                <p>Hi <b>HR</b> </p>
                                <p> <b>{{ $interview->user->first_name }}</b>, TIN# <b>{{ $interview->user->id_number }}</b>
                                    was successfully approved as a tutor on MEtutors. <b>{{ $interview->user->first_name }}
                                        was added to the
                                        MEtutors platform tutors' list and is available for course bookings.
                                </p>
                                <p>Tutor profile:
                                    @php
                                        echo 'https://metutors.com/tutor/settings';
                                    @endphp
                                </p>
                                <p>Regards</p>
                                <p>Talent Acquisition Team</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </td>
@endsection
