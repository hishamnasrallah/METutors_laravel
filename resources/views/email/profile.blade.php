@extends('web.default.layouts.email')

@section('body')
    <!-- content -->
    <td valign="top" class="bodyContent" mc:edit="body_content">
        <h1 class="h1">{{ $profileMessage }}</h1>
        <p>User name : {{ $user->first_name }}</p>
        <p>Email : {{ $user->email }}</p>
        <p>Bio : {{ $user->bio }}</p>
    </td>
@endsection
