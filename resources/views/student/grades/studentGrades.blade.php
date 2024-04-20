@extends('mainLayouts.app')
@section('content')

    <div id="app">
        <student-grades-component :user-id="{{ Auth::user()->id }}"></student-grades-component>
    </div>


@endsection