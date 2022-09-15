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
                                <p> Congratulations your application to join MEtutors has been approved. Welcome to the
                                    MEtutors family!</p>
                                <p>In case you have any queries, please contact the MEtutors success team 
                                    @php echo 'https://metutors.com/contact';@endphp .
                                </p>
                                <p>We look forward to working with you.</p>
                                <p>Regards,</p>
                                <p>Talent Acquisition Team</p>
                            </div>
                        @endif

                        @if ($user->role_name == 'admin')
                            <div class="card-body">
                                <p>Hi <b>HR,</b> </p>
                                <p><b>{{ $interview->user->first_name }} {{ $interview->user->last_name }}</b> TIN#
                                    <b>{{ $interview->user->id_number }}</b> was successfully approved as a tutor on
                                    MEtutors.
                                </p>
                                <p><b>{{ $interview->user->first_name }} {{ $interview->user->last_name }}</b> is now
                                    available for bookings.</p>
                                <p><b>Tutor profile:</b> @php echo 'https://metutors.com/tutor/settings'; @endphp</p>
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
