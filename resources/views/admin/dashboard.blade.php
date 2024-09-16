@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
    <h1>Welcome, Admin</h1>

    <p>You are logged in!</p>

    <div style="text-align: center; margin-top: 2rem;">
        <a href="{{ route('logout') }}" style="color: red;">Logout</a>
    </div>
@endsection
