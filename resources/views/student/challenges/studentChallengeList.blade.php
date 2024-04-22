@extends('mainLayouts.app')
@section('content')

    <div id="app">
        <student-challenges-component :user-id="{{ Auth::user()->id }}"></student-challenges-component>
    </div>


@endsection