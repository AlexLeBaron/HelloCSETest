@extends('layouts.app')

@section('title', 'Profiles')

@section('content')
    <h1>Profiles</h1>

    @if($profiles->isEmpty())
        <p>No profiles found.</p>
    @else
        <ul>
            @foreach($profiles as $profile)
                <li>
                    <strong>{{ $profile->firstname }} {{ $profile->lastname }}</strong>
                    <a href="{{ route('profiles.show', $profile->id) }}" class="view-link">View Profile</a>
                </li>
            @endforeach
        </ul>
    @endif
@endsection
