@extends('mainLayouts.app')
@section('content')

    <div id="app">
        <parent-homework-component :user-id="{{ Auth::user()->id }}"></parent-homework-component>
    </div>


@endsection