@extends('mainLayouts.app')
@section('content')

    <div id="app">
        <student-leaderboard-component :user-id="{{ Auth::user()->id }}"></student-leaderboard-component>
    </div>


@endsection