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
<a href="index3.html" class="nav-link">Home</a>
</li>
<!-- <li class="nav-item d-none d-sm-inline-block">
<a href="#" class="nav-link">Contact</a>
</li> -->
</ul>

<!-- <ul class="navbar-nav ml-auto">

<li class="nav-item">
<a class="nav-link" data-widget="navbar-search" href="#" role="button">
<i class="fas fa-search"></i>
</a>
<div class="navbar-search-block">
<form class="form-inline">
<div class="input-group input-group-sm">
<input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
<div class="input-group-append">
<button class="btn btn-navbar" type="submit">
<i class="fas fa-search"></i>
</button>
<button class="btn btn-navbar" type="button" data-widget="navbar-search">
<i class="fas fa-times"></i>
</button>
</div>
</div>
</form>
</div>
</li>

<li class="nav-item dropdown">
<a class="nav-link" data-toggle="dropdown" href="#">
<i class="far fa-comments"></i>
<span class="badge badge-danger navbar-badge">3</span>
</a>
<div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
<a href="#" class="dropdown-item">

<div class="media">
<img src="https://adminlte.io/themes/v3/dist/img/user1-128x128.jpg" alt="User Avatar" class="img-size-50 mr-3 img-circle">
<div class="media-body">
<h3 class="dropdown-item-title">
Brad Diesel
<span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
</h3>
<p class="text-sm">Call me whenever you can...</p>
<p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
</div>
</div>

</a>
<div class="dropdown-divider"></div>
<a href="#" class="dropdown-item">

<div class="media">
<img src="https://adminlte.io/themes/v3/dist/img/user8-128x128.jpg" alt="User Avatar" class="img-size-50 img-circle mr-3">
<div class="media-body">
<h3 class="dropdown-item-title">
John Pierce
<span class="float-right text-sm text-muted"><i class="fas fa-star"></i></span>
</h3>
<p class="text-sm">I got your message bro</p>
<p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
</div>
</div>

</a>
<div class="dropdown-divider"></div>
<a href="#" class="dropdown-item">

<div class="media">
<img src="https://adminlte.io/themes/v3/dist/img/user3-128x128.jpg" alt="User Avatar" class="img-size-50 img-circle mr-3">
<div class="media-body">
<h3 class="dropdown-item-title">
Nora Silvester
<span class="float-right text-sm text-warning"><i class="fas fa-star"></i></span>
</h3>
<p class="text-sm">The subject goes here</p>
<p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
</div>
</div>

</a>
<div class="dropdown-divider"></div>
<a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
</div>
</li>

<li class="nav-item dropdown">
<a class="nav-link" data-toggle="dropdown" href="#">
<i class="far fa-bell"></i>
<span class="badge badge-warning navbar-badge">15</span>
</a>
<div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
<span class="dropdown-header">15 Notifications</span>
<div class="dropdown-divider"></div>
<a href="#" class="dropdown-item">
<i class="fas fa-envelope mr-2"></i> 4 new messages
<span class="float-right text-muted text-sm">3 mins</span>
</a>
<div class="dropdown-divider"></div>
<a href="#" class="dropdown-item">
<i class="fas fa-users mr-2"></i> 8 friend requests
<span class="float-right text-muted text-sm">12 hours</span>
</a>
<div class="dropdown-divider"></div>
<a href="#" class="dropdown-item">
<i class="fas fa-file mr-2"></i> 3 new reports
<span class="float-right text-muted text-sm">2 days</span>
</a>
<div class="dropdown-divider"></div>
<a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
</div>
</li>
<li class="nav-item">
<a class="nav-link" data-widget="fullscreen" href="#" role="button">
<i class="fas fa-expand-arrows-alt"></i>
</a>
</li>
<li class="nav-item">
<a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
<i class="fas fa-th-large"></i>
</a>
</li>
</ul> -->
</nav>


<aside class="main-sidebar sidebar-dark-primary elevation-4">

<a href="index3.html" class="brand-link">
<!-- <img src="https://adminlte.io/themes/v3/dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8"> -->
<span class="brand-text font-weight-light">GSGS</span>
</a>

<div class="sidebar">

<div class="user-panel mt-3 pb-3 mb-3 d-flex">
<div class="image">
<!-- <img src="https://adminlte.io/themes/v3/dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image"> -->
</div>
<div class="info">
<a href="#" class="d-block">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</a>
</div>
</div>

<!-- <div class="form-inline">
<div class="input-group" data-widget="sidebar-search">
<input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
<div class="input-group-append">
<button class="btn btn-sidebar">
<i class="fas fa-search fa-fw"></i>
</button>
</div>
</div>
</div> -->

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
<a href="" class="nav-link">
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
<a href="" class="nav-link">
<i class="nav-icon fas fa-tasks"></i>
<p>Homework</p>
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

<!-- <div class="content-header">
<div class="container-fluid">
<div class="row mb-2">
<div class="col-sm-6">
<h1 class="m-0">Starter Page</h1>
<div class="col-md-9"> -->
                <main class="py-4">
                    @yield('content')
                </main>
            <!-- </div>
</div>
<div class="col-sm-6">
<ol class="breadcrumb float-sm-right">
<li class="breadcrumb-item"><a href="#">Home</a></li>
<li class="breadcrumb-item active">Starter Page</li>
</ol>
</div>
</div>
</div>
</div>


<div class="content">
<div class="container-fluid">
<div class="row">
<div class="col-lg-6">
<div class="card">
<div class="card-body">
<h5 class="card-title">Card title</h5>
<p class="card-text">
Some quick example text to build on the card title and make up the bulk of the card's
content.
</p>
<a href="#" class="card-link">Card link</a>
<a href="#" class="card-link">Another link</a>
</div>
</div>
<div class="card card-primary card-outline">
<div class="card-body">
<h5 class="card-title">Card title</h5>
<p class="card-text">
Some quick example text to build on the card title and make up the bulk of the card's
content.
</p>
<a href="#" class="card-link">Card link</a>
<a href="#" class="card-link">Another link</a>
</div>
</div>
</div>

<div class="col-lg-6">
<div class="card">
<div class="card-header">
<h5 class="m-0">Featured</h5>
</div>
<div class="card-body">
<h6 class="card-title">Special title treatment</h6>
<p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
<a href="#" class="btn btn-primary">Go somewhere</a>
</div>
</div>
<div class="card card-primary card-outline">
<div class="card-header">
<h5 class="m-0">Featured</h5>
</div>
<div class="card-body">
<h6 class="card-title">Special title treatment</h6>
<p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
<a href="#" class="btn btn-primary">Go somewhere</a>
</div>
</div>
</div>

</div>

</div>
</div> -->

</div>

<aside class="control-sidebar control-sidebar-dark">

<div class="p-3">
<h5>Title</h5>
<p>Sidebar content</p>
</div>
</aside>


<footer class="main-footer">

<div class="float-right d-none d-sm-inline">
<!-- Anything you want -->
</div>

<!-- <strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">AdminLTE.io</a>.</strong> All rights reserved. -->
</footer>
</div>


</body>
</html>