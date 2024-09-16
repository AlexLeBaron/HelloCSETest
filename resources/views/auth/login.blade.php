@extends('layouts.app')

@section('title', 'Login')

@section('content')
    <h1>Login</h1>

    @if(session('error'))
        <div class="alert">{{ session('error') }}</div>
    @endif

    <form action="{{ route('login') }}" method="POST">
        @csrf
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>

    <div style="text-align: center; margin-top: 1rem;">
        <a href="{{ route('register') }}">Register</a>
    </div>
@endsection
