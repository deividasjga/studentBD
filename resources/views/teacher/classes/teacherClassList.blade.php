@extends('mainLayouts.app')
@section('content')

<div class="content-header">
    <div class="container-fluid">
    <div class="row mb-2">
    <div class="col-sm-6">
    <h1 class="m-0">Classes</h1>
    <br>
    </div>
    
    <div class="col-sm-6">
    <ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="#">Home</a></li>
    <li class="breadcrumb-item active">Classes</li>
    </ol>
    </div>
    </div>
    </div>
    </div>
    
    
    <div class="content">
    <div class="container-fluid">
      <div class="col-md-8 mx-auto">
        <div class="card">
        <div class="card-body">
        <table class="table table-bordered">
          <thead>
          <tr>
            <th style="width: 45px">#</th>
            <th>Class</th>
            <th>Subject</th>
            <th>Option</th>
          </tr>
        </thead>
        <tbody>
            @php $classIndex = 1 @endphp
            @foreach ($teacherClasses as $class)
                @foreach ($class->subjects as $subject)
                    <tr>
                        <td>{{ $classIndex }}</td>
                        <td>{{ $class->name }}</td>
                        <td>{{ $subject->name }}</td>
                        <td>
                        <a href="{{ route('subjectGrading', ['user_id' => Auth::user()->id, 'class_id' => $class->id, 'subject_id' => $subject->id]) }}" class="btn btn-primary">Select</a>

                    </tr>
                    @php $classIndex++ @endphp
                @endforeach
            @endforeach
        </tbody>
        </table>
    </div>
    </div>
    </div>
  </div>
</div>

@endsection
