@extends('web.default.layouts.email')

@section('body')
    <!-- content -->
    <td valign="top" class="bodyContent" mc:edit="body_content">
        <h1 class="h1">{{ $custom_message }}</h1>
        <p>Course name : {{ $custom_data->course_description }}</p>
    </td>
@endsection
