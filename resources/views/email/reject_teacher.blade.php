@extends('web.default.layouts.email')

@section('body')
    <td valign="top" class="bodyContent" mc:edit="body_content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">

                        @if ($user->role_name == 'teacher')
                            <div class="card-body">
                                <p>Hi <b>{{ $user->first_name }} {{ $user->last_name }}</b> </p>
                                <p We are sorry to inform you that Admin REJECTED your candidature based on the interview.
                                    </p>
                                <p> Teacher Information Number (TIN): <b>{{ $interview->user->id_number }}</b></p>
                                <p>Regards</p>
                            </div>
                        @endif

                        @if ($user->role_name == 'admin')
                            <div class="card-body">
                                <p>Hi <b> {{ $user->first_name }} {{ $user->last_name }}</b> </p>
                                <p> You have REJECTED Tutor {{ $interview->user->first_name }} based on their interview.</p>
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
