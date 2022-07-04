@extends('web.default.layouts.email')

@section('body')
    <!-- content -->
    <td valign="top" class="bodyContent" mc:edit="body_content">
        <h1 class="h1">{{ $custom_message }}</h1>
        <p>Class title : {{ $class->title }}</p>
        <p>Date : {{ $class->start_date }}</p>
        <p>Start time : {{ $class->start_time }}</p>
        <p>End time : {{ $class->end_time }}</p>
    </td>
@endsection
