@extends('layouts.app')

@section('title', 'Register')

@section('content')
    <h1>Register</h1>

    @if(session('error'))
        <div class="alert">{{ session('error') }}</div>
    @endif

    <form action="{{ route('register') }}" method="POST">
        @csrf
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="password" name="password_confirmation" placeholder="Confirm Password" required>
        <button type="submit">Register</button>
    </form>

    <div style="text-align: center; margin-top: 1rem;">
        <a href="{{ route('login') }}">Already have an account? Login here</a>
    </div>
@endsection
