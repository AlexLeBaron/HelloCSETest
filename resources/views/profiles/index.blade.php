@extends('layouts.app')

@section('title', 'Profils actifs')

@section('content')
    <h1>Liste des profils actifs</h1>
    <ul>
        @foreach($profiles as $profile)
            <li>
                <a href="{{ route('profiles.show', $profile->id) }}">
                    {{ $profile->firstname }} {{ $profile->lastname }}
                </a>
            </li>
        @endforeach
    </ul>
@endsection