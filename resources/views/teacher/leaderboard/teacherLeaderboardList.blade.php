@extends('mainLayouts.app')
@section('content')

    <div id="app">
        <teacher-leaderboard-component :user-id="{{ Auth::user()->id }}"></teacher-leaderboard-component>
    </div>


@endsection