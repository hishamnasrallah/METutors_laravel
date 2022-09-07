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
                                <p>Dear <b>{{ $user->first_name }},</b> </p>
                                <p>You have successfuly updated your password on MEtutors. Please do not share it with
                                    others and keep it secured.</p>
                                <p> If you have any queries please contact our support team at @php echo 'https://metutors.com/contact';@endphp.
                                </p>
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
