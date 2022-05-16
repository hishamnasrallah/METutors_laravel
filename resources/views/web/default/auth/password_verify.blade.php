@extends('web.default.layouts.email')

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
                            
                            <p><?php echo 'http://localhost:4200/reset-password/'.$token.'/'.$email ?></p>
                            
                            <p>OR</p>
                            
                            <a href="<?php echo 'http://localhost:4200/reset-password/'.$token.'/'.$email ?>">{{ trans('auth.click_here') }}</a>
                            
                           <!--<a href="http://localhost:4200/reset-password">{{ trans('auth.click_here') }}</a>-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </td>
@endsection
