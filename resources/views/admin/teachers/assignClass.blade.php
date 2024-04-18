@extends('admin.layouts.app')
@section('content')
<div class="container">
        <h2>Assign Classes to {{ $teacher->name }}</h2>

        <form action="{{ route('updateClass', $teacher->id) }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="classes">Select Classes:</label>
                <select class="form-control" id="classes" name="classes[]" multiple>
                    @foreach ($classes as $class)
                    <option value="{{ $class->id }}" {{ $teacher->teacherClasses->contains($class->id) ? 'selected' : '' }}>
                            {{ $class->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
@endsection