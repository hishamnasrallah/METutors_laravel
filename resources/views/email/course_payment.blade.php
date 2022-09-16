@extends('web.default.layouts.email')

@section('body')
    <!-- content -->
    <td valign="top" class="bodyContent" mc:edit="body_content">
        <h1 class="h1">{{ $custom_message }}</h1>
        <p>Course name : {{ $course->course_name }}</p>
        <p>Start date : {{ $course->start_date }}</p>
        <p>End date : {{ $course->end_date }}</p>
        <p>Start time : {{ $course->start_time }}</p>
        <p>End time : {{ $course->end_time }}</p>
        {{-- <p>Transaction_id : {{ $course->transacrtion_id }}</p> --}}
    </td>
@endsection
