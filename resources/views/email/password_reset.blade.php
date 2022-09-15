@extends('web.default.layouts.email')

@section('body')
    <!-- content -->
    <td valign="top" class="bodyContent" mc:edit="body_content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        @if ($user->role_name == 'teacher')
                            <div class="card-body">
                                <p>Hi <b>{{ $user->first_name }},</b> </p>
                                <p>You have successfully updated your password on MEtutors. You can log in to your profile on this link.@php echo 'https://metutors.com/tutor/settings';@endphp</p>
                                <p>Regards,</p>
                                <p>Technical Support Team</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </td>
@endsection
