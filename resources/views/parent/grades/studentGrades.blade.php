@extends('mainLayouts.app')
@section('content')

    <div id="app">
        <parent-grades-component :user-id="{{ Auth::user()->id }}"></parent-grades-component>
    </div>


@endsection