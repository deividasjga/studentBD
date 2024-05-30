<!DOCTYPE html>

<html lang="en">
<head>
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>GSGS</title>

@vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="hold-transition sidebar-mini">

<div class="wrapper" id="app">

<nav class="main-header navbar navbar-expand navbar-white navbar-light">

<ul class="navbar-nav">
<li class="nav-item">
<a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
</li>
<li class="nav-item d-none d-sm-inline-block">
<a href="{{ route('home') }}" class="nav-link">Home</a>
</li>

</ul>


</nav>


<aside class="main-sidebar sidebar-dark-primary elevation-4">

<a href="{{ route('home') }}" class="brand-link">
<span class="brand-text font-weight-light">GSGS</span>
</a>

<div class="sidebar">

<div class="user-panel mt-3 pb-3 mb-3 d-flex">
<div class="image">
</div>
<div class="info">
<a href="#" class="d-block">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</a>
</div>
</div>


<nav class="mt-2">
<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

<li class="nav-item menu-open">
@auth
@if(Auth::user()->role === 'admin')
<a href="#" class="nav-link" >
<i class="nav-icon fas fa-users"></i>
<p>
Users
<i class="right fas fa-angle-left"></i>
</p>
</a>
<ul class="nav nav-treeview">
<li class="nav-item">
<a href="{{ route('StudentList') }}" class="nav-link {{ Request::is('StudentList') ? 'active' : '' }}">
<i class="far fa-circle nav-icon"></i>
<p>Students</p>
</a>
</li>
<li class="nav-item">
<a href="{{ route('TeacherList') }}" class="nav-link {{ Request::is('TeacherList') ? 'active' : '' }}">
<i class="far fa-circle nav-icon"></i>
<p>Teachers</p>
</a>
</li>
<li class="nav-item">
<a href="{{ route('ParentList') }}" class="nav-link {{ Request::is('ParentList') ? 'active' : '' }}">
<i class="far fa-circle nav-icon"></i>
<p>Parents</p>
</a>
</li>
<li class="nav-item">
<a href="{{ route('AdminList') }}" class="nav-link {{ Request::is('AdminList') ? 'active' : '' }}">
<i class="far fa-circle nav-icon"></i>
<p>Admins</p>
</a>
</li>
</ul>

<li class="nav-item">
<a href="{{ route('subjectList') }}" class="nav-link {{ Request::is('subjects') ? 'active' : '' }}">
<i class="nav-icon fas fa-book"></i>
<p>Subjects</p>
</a>
</li>

<li class="nav-item">
<a href="{{ route('classList') }}" class="nav-link {{ Request::is('classes') ? 'active' : '' }}">
<i class="nav-icon fas fa-chalkboard"></i>
<p>Classes</p>
</a>
</li>

<li class="nav-item">
<a href="{{ route('indexAssign') }}" class="nav-link {{ Request::is('teachersAssign') ? 'active' : '' }}">
<i class="nav-icon fas fa-address-book"></i>
<p>Assign Teachers</p>
</a>
</li>

<li class="nav-item">
<a href="{{ route('challengeList') }}" class="nav-link {{ Request::is('admin/challenges') ? 'active' : '' }}">
<i class="nav-icon fas fa-bullseye"></i>
<p>Challenges</p>
</a>
</li>

<li class="nav-item">
<a href="{{ route('rewardList') }}" class="nav-link {{ Request::is('admin/rewards') ? 'active' : '' }}">
<i class="nav-icon fas fa-gift"></i>
<p>Rewards</p>
</a>
</li>

@elseif(Auth::user()->role === 'teacher')
<li class="nav-item">
<a href="{{ route('teacherClassList') }}" class="nav-link {{ Request::is('teacher-classes') ? 'active' : '' }}">
<i class="nav-icon fas fa-chalkboard"></i>
<p>My classes</p>
</a>
</li>
<li class="nav-item">
<a href="{{ route('teacherHomework') }}" class="nav-link {{ Request::is('teacher/homework') ? 'active' : '' }}">
<i class="nav-icon fas fa-tasks"></i>
<p>Homework</p>
</a>
</li>
<li class="nav-item">
<a href="{{ route('teacherLeaderboardList') }}" class="nav-link {{ Request::is('teacher/leaderboard') ? 'active' : '' }}">
<i class="nav-icon fas fa-trophy"></i>
<p>Leaderboard</p>
</a>
</li>
@elseif(Auth::user()->role === 'student')
<li class="nav-item">
<a href="{{ route('studentGrades') }}" class="nav-link {{ Request::is('student/grades') ? 'active' : '' }}">
<i class="nav-icon fas fa-book"></i>
<p>My grades</p>
</a>
</li>
<li class="nav-item">
<a href="{{ route('studentHomework') }}" class="nav-link {{ Request::is('student/homework') ? 'active' : '' }}">
<i class="nav-icon fas fa-tasks"></i>
<p>Homework</p>
</a>
</li>
<li class="nav-item">
<a href="{{ route('studentChallengeList') }}" class="nav-link {{ Request::is('student/challenges') ? 'active' : '' }}">
<i class="nav-icon fas fa-bullseye"></i>
<p>Challenges</p>
</a>
</li>
<li class="nav-item">
<a href="{{ route('studentRewardList') }}" class="nav-link {{ Request::is('student/rewards') ? 'active' : '' }}">
<i class="nav-icon fas fa-gift"></i>
<p>Rewards</p>
</a>
</li>
<li class="nav-item">
<a href="{{ route('studentLeaderboardList') }}" class="nav-link {{ Request::is('student/leaderboard') ? 'active' : '' }}">
<i class="nav-icon fas fa-trophy"></i>
<p>Leaderboard</p>
</a>
</li>

@elseif(Auth::user()->role === 'parent')
<li class="nav-item">
<a href="{{ route('parentGrades') }}" class="nav-link {{ Request::is('parent/grades') ? 'active' : '' }}">
<i class="nav-icon fas fa-book"></i>
<p>Grades</p>
</a>
</li>
<li class="nav-item">
<a href="{{ route('parentHomework') }}" class="nav-link {{ Request::is('parent/homework') ? 'active' : '' }}">
<i class="nav-icon fas fa-tasks"></i>
<p>Homework</p>
</a>
</li>
<li class="nav-item">
<a href="{{ route('parentLeaderboard') }}" class="nav-link {{ Request::is('parent/leaderboard') ? 'active' : '' }}">
<i class="nav-icon fas fa-trophy"></i>
<p>Leaderboard</p>
</a>
</li>
@endif
@endauth
</li>

<li class="nav-item">
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <a href="#" onclick="event.preventDefault(); this.closest('form').submit();" class="nav-link">
            <i class="nav-icon fas fa-sign-out-alt"></i>
            <p>
            Logout
            </p>
        </a>
        </li>
    </form>

</ul>
</nav>

</div>

</aside>

<div class="content-wrapper">


                <main class="py-4">
                    @yield('content')
                </main>


</div>

<aside class="control-sidebar control-sidebar-dark">

<div class="p-3">
<h5>Title</h5>
<p>Sidebar content</p>
</div>
</aside>


<footer class="main-footer">

<div class="float-right d-none d-sm-inline">

</div>

<strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">AdminLTE.io</a>.</strong> All rights reserved.
</footer>
</div>


</body>
</html>