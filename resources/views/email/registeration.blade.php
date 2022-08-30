@extends('web.default.layouts.email')

@section('body')
    <td valign="top" class="bodyContent" mc:edit="body_content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        @if ($user->role_name == 'teacher')
                            <div class="card-body">
                                <p>Hi <b> {{ $user->first_name }} {{ $user->last_name }},</b> </p>
                                <p>You have successfully registered on MEtutors.com. We are delighted to have you on board
                                    with us
                                    and to be considered for a teaching position on our platform.</p>
                                <p>You have been assigned a Teacher Identification Number (TIN): {{ $user->id_number }}.
                                    Kindly visit your
                                    profile
                                    page at
                                    @php
                                        echo 'https://metutors.com/tutor/settings';
                                    @endphp
                                    and submit your Interview Request to begin
                                    the hiring
                                    process.</p>
                                <p> Please feel free to contact us at
                                    @php
                                        echo 'https://metutors.com/about';
                                    @endphp
                                    in case of any queries.</p>
                                <p>Best wishes and happy teaching</p>
                                <p>Regards,</p>
                                <p>Administrative Team</p>

                            </div>
                        @endif

                        @if ($user->role_name == 'admin')
                            <div class="card-body">
                                <p>Hi HR, </p>
                                <p>New registration by {{ $user->teacher->first_name }}, TIN#(
                                    {{ $user->teacher->id_number }}) was received. Click the
                                    link to view the profile.
                                </p>
                                <p>
                                    @php
                                        echo 'https://metutors.com/tutor/settings';
                                    @endphp
                                </p>
                                <p>Regards</p>
                                <p>Administrative Team</p>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </td>
@endsection
