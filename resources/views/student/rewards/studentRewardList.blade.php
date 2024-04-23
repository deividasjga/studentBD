@extends('mainLayouts.app')
@section('content')

    <div id="app">
        <student-rewards-component :user-id="{{ Auth::user()->id }}"></student-rewards-component>
    </div>

@endsection