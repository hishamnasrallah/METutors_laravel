@extends('web.default.layouts.email')

@section('body')
    <td valign="top" class="bodyContent" mc:edit="body_content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        @if ($user->role_name == 'teacher')
                            <div class="card-body">
                                <p>Hi <b>{{ $user->first_name }},</b></p>
                                <p>ne of the documents you submitted as part of your application process has been declined. Please log in to your dashboard to upload necessary documents.
                                </p>
                                <p>In case you have any queries, please feel free to contact us at @php echo "https://metutors.com/contact" @endphp.</p>
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
