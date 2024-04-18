@extends('admin.layouts.app')
@section('content')
<div class="container">
        <h2>Assign Subjects to {{ $teacher->name }}</h2>

        <form action="{{ route('updateSubjects', ['teacher_id' => $teacher->id, 'class_id' => $class->id]) }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="classes">Select Subjects:</label>
                <select class="form-control" id="subjects" name="subjects[]" multiple>
                    @foreach ($subjects as $subject)
                        <option value="{{ $subject->id }}" {{ $teacher->teacherSubjects->contains($subject->id) ? 'selected' : '' }}>
                            {{ $subject->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
@endsection