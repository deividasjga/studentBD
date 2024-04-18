@extends('admin.layouts.app')
@section('content')
    <div class="container">
        <h2>Assign Teachers</h2>
        <ul>
            @foreach ($teachers as $teacher)
                <li>
                    {{ $teacher->first_name }}
                    <a href="{{ route('assignClass', $teacher->id) }}" class="btn btn-primary">Assign Classes</a>
                </li>
                <p>Assigned Classes:</p>
        <ul>
        @foreach ($teacher->teacherClasses as $class)
            <li>{{ $class->name }}</li>
            <a href="{{ route('assignSubjects', ['teacher_id' => $teacher->id, 'class_id' => $class->id]) }}" class="btn btn-primary">Assign Subjects</a>
            <p>Assigned Subjects:</p>
                <ul>
                @foreach ($teacher->teacherSubjects as $subject)
                @if ($subject->classes->contains($class))
                    <li>{{ $subject->name }}</li>
                    @endif
                @endforeach
                </ul>
            @endforeach
        </ul>
    @endforeach
        </ul>
    </div>
@endsection