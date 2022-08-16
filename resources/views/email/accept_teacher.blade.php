@extends('web.default.layouts.email')

@section('body')
    <td valign="top" class="bodyContent" mc:edit="body_content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        @if ($user->role_name == 'teacher')
                            <div class="card-body">
                                <p>Dear <b>{{ $user->first_name }} {{ $user->last_name }}</b> </p>
                                <p>Congratulations on your approval as a teacher on the MEtutor platform.</p>
                                <p> Teacher Information Number (TIN): <b>{{ $user->id_number }}</b></p>
                                <p>Regards</p>
                            </div>
                        @endif

                        @if ($user->role_name == 'admin')
                            <div class="card-body">
                                <p>Hi <b>{{ $user->first_name }} {{ $user->last_name }}</b> </p>
                                <p>You have successfully approved Tutor <b>{{ $interview->user->first_name }}</b> based on
                                    their
                                    interview.
                                </p>
                                <p>{{ $user->first_name }} got added to the
                                    MEtutor platform tutors' list and is available for course bookings</p>
                                <p> Teacher Information Number (TIN): <b>{{ $interview->user->id_number }}</b></p>
                                <p>Regards</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </td>
@endsection
