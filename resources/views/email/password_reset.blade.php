@extends('web.default.layouts.email')

@section('body')
    <!-- content -->
    <td valign="top" class="bodyContent" mc:edit="body_content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        {{-- @if ($user->role_name == 'teacher') --}}
                            <div class="card-body">
                                <p>Dear {{ $user->first_name }}." ".{{ $user->last_name }} </p>
                                <p>You have successfully reset your password.</p>
                                <p>Regards,</p>

                            </div>
                        {{-- @endif --}}
                    </div>
                </div>
            </div>
        </div>
    </td>
@endsection
