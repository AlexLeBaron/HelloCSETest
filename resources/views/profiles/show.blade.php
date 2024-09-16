@extends('layouts.app')

@section('title', 'Profile Details')

@section('content')
    <h1>{{ $profile->firstname }} {{ $profile->lastname }}</h1>

    <p><strong>Email:</strong> {{ $profile->email }}</p>
    <p><strong>Status:</strong> {{ ucfirst($profile->status) }}</p>

    @if($profile->image)
        <div style="margin-top: 20px;">
            <img src="{{ asset('storage/images/' . $profile->image) }}" alt="Profile image" style="width: 100%; max-width: 300px;">
        </div>
    @endif

    <div style="margin-top: 20px;">
        <a href="{{ route('profiles.index') }}">Back to Profiles</a>
    </div>
@endsection
