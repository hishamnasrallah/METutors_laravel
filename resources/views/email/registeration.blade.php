@extends('web.default.layouts.email')

@section('body')
    <td valign="top" class="bodyContent" mc:edit="body_content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        @if ($user->role_name == 'teacher')
                            <div class="card-body">
                                <p>Dear <b> {{ $user->first_name }} {{ $user->last_name }}</b> </p>
                                <p>Welcome to MeTutor platform. You have registered successfully. We are glad to have you on
                                    board. You can click the link to view your profile.</p>
                                <p>Link:
                                    @php
                                        echo 'metutors.comtutor/settings';
                                    @endphp
                                </p>
                                <p>Best wishes and happy teaching</p>
                                <p>Regards</p>

                            </div>
                        @endif

                        @if ($user->role_name == 'admin')
                            <div class="card-body">
                                <p>Hi <b>{{ $user->first_name }} {{ $user->last_name }}</b> </p>
                                <p>Please note a new registration by tutor {{ $user->teacher->first_name }} Click the link
                                    to
                                    view the profile.</p>
                                {{-- {{ $user->teacher->first_name }} --}}
                                <p>Link:
                                    @php
                                        echo 'metutors.comtutor/settings';
                                    @endphp
                                </p>
                                <p>Teacher Information Number:<b>{{ $user->teacher->id_number }}</b></p>
                                <p>Regards</p>

                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </td>
@endsection
