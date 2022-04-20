@extends('web.default.layouts.email')

@section('body')
    <!-- content -->
    <td valign="top" class="bodyContent" mc:edit="body_content">
        <h1 class="h1">{{ $assignmentMessage }}</h1>
        <p>Assignment id : {{ $assignment->assignment_id }}</p>
        <p>Student_id : {{ $assignment->student_id }}</p>
        <p>Teacher_id : {{ $assignment->feedback_by }}</p>
        <p>Review : {{ $assignment->review }}</p>
        <p>Rating : {{ $assignment->rating }}</p>
    </td>
@endsection
