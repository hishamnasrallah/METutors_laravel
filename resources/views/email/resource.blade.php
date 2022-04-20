@extends('web.default.layouts.email')

@section('body')
    <!-- content -->
    <td valign="top" class="bodyContent" mc:edit="body_content">
        <h1 class="h1">{{ $resourceMessage }}</h1>
        {{-- <p>Resource : {{ $resource }}</p> --}}

    </td>
@endsection
