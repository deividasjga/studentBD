@extends('mainLayouts.app')
@section('content')
<div class="container">
        <h2>Assign Subjects to {{ $teacher->name }}</h2>

        <form action="{{ route('updateSubjects', ['teacher_id' => $teacher->id, 'class_id' => $class->id]) }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="classes">Select Subjects:</label>
                <select class="form-control" name="subjects[]" multiple>
                    @foreach ($allSubjects as $subject)
                        <option value="{{ $subject->id }}" {{ $assignedSubjects->contains($subject) ? 'selected' : '' }}>
                            {{ $subject->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
@endsection