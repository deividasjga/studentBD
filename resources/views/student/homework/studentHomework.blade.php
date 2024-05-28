@extends('mainLayouts.app')
@section('content')

    <div id="app">
        <student-homework-component :user-id="{{ Auth::user()->id }}"></student-homework-component>
    </div>


@endsection