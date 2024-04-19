@extends('mainLayouts.app')
@section('content')
<div id="app">
    <teacher-subject-grading-component
        :user_id="{{ $userId }}" 
        :class_id="{{ $classId }}" 
        :subject_id="{{ $subjectId }}"
    ></teacher-subject-grading-component>
</div>

@endsection