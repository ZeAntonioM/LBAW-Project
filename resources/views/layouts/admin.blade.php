@extends('layouts.app')

@section('navbar')
    <li id="adminUsersPage"><a href="{{route('admin_users')}}"> <i class="fa-solid fa-house"></i> Users</a></li>
    <li id="adminProjectsPage"><a href="{{route('admin_show_projects')}}"> <i class="fa-solid fa-users"></i> Projects</a></li>
@endsection