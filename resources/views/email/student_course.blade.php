@extends('web.default.layouts.email')

@section('body')
    <!-- content -->
    <td valign="top" class="bodyContent" mc:edit="body_content">
        <h1 class="h1">Course Booked Successfully!</h1>
        You have Successfully submitted the MeTutor Course({{ $course }}).

        <p>Start date : {{ $start_date }}</p>
        <p>End date : {{ $end_date }}</p>
        <p>Start time : {{ $start_time }}</p>
        <p>End time : {{ $end_time }}</p>
    </td>
@endsection
