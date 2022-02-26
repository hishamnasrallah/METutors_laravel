@extends('web.default.layouts.email')

@section('body')
    <!-- content -->
    <td valign="top" class="bodyContent" mc:edit="body_content">
        <h1 class="h1">{{ $details['title'] }}</h1>
        <p>{!! nl2br($details['message']) !!}</p>

       <a href="{{ $details['link'] }}" ><p class="code">{{ $details['link'] }}</p></a> 

        <p> {{ $details['ignoremessage'] }} </p>
    </td>
@endsection
