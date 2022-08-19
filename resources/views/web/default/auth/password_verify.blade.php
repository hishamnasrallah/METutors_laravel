{{-- @extends('web.default.layouts.email')

@section('body')
    <!-- content -->
    <td valign="top" class="bodyContent" mc:edit="body_content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">{{ trans('auth.verify_your_email_address') }}</div>
                        <div class="card-body">
                            <div class="alert alert-success" role="alert">
                                {{ trans('auth.verification_link_has_been_sent_to_your_email') }}
                            </div>

                            copy this link

                            <p>
                                @php
                                    echo 'https://metutors.com/reset-password/' . $token . '/' . $email;
                                @endphp
                            </p>

                            <p>OR</p>

                            <a
                                href="
                            @php
                                echo 'https://metutors.com/reset-password/' . $token . '/' . $email;
                            @endphp
                            ">
                                {{ trans('auth.click_here') }}</a>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </td>
@endsection --}}

@extends('web.default.layouts.email')

@section('body')
    <!-- content -->
    <td valign="top" class="bodyContent" mc:edit="body_content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        {{-- <div class="card-header">{{ trans('auth.verify_your_email_address') }}</div> --}}
                        <div class="card-body">
                            <p>Dear {{ $user->first_name }} {{ $user->last_name }} </p>
                            <p>Please click this link to reset your password.</p>
                            <p>Link:
                                @php
                                    echo 'https://metutors.com/reset-password/' . $token . '/' . $email;
                                @endphp
                            </p>

                            <p>OR</p>

                            <a
                                href="
                            @php
                                echo 'https://metutors.com/reset-password/' . $token . '/' . $email;
                            @endphp
                            ">
                                {{ trans('auth.click_here') }}</a>

                            <p>Kindly ignore the mail if you have not made a password reset request.</p>
                            <p>Regards,</p>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </td>
@endsection
