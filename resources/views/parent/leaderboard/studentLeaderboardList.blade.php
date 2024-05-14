@extends('mainLayouts.app')
@section('content')

    <div id="app">
        <parent-leaderboard-component :user-id="{{ Auth::user()->id }}"></parent-leaderboard-component>
    </div>


@endsection