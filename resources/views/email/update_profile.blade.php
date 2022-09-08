@extends('web.default.layouts.email')

@section('body')
    <td valign="top" class="bodyContent" mc:edit="body_content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">

                        @if ($user->role_name == 'teacher')
                            <div class="card-body">
                                <p>Hi <b>{{ $user->first_name }}</b>, </p>
                                <p>Your profile has been updated successfully, it can be viewed here @php echo 'https://metutors.com/tutor/settings';@endphp.</p>
                                <p>We will be happy to help with any other queries you may have.</p>
                                <p>Regards,</p>
                                <p>Technical Support Team</p>
                            </div>
                        @endif

                        @if ($user->role_name == 'admin')
                            <div class="card-body">
                                <p>Hi <b>HR,</b> </p>
                                <p><b>{{ $user->first_name }}</b>,TIN <b>{{ $user->id_number }}</b>
                                    has updated their profile. You can see the updates here @php
                                        echo "https://metutors.com/admin/tutor/profile/$user->id";
                                    @endphp.</p>
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
