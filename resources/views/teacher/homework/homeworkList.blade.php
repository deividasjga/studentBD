@extends('mainLayouts.app')
@section('content')

    <div id="app">
        <teacher-homework-component :user-id="{{ Auth::user()->id }}"></teacher-homework-component>
    </div>


@endsection