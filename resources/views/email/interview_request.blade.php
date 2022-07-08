@extends('web.default.layouts.email')

@section('body')
    <!-- content -->
    <td valign="top" class="bodyContent" mc:edit="body_content">
        <h1 class="h1">{{ $custom_message }}</h1>
        <p>Interview Date : {{ $interview_request->date_for_interview }}</p>
        <p>Interview time: {{ $interview_request->time_for_interview }}</p>
    </td>
@endsection
