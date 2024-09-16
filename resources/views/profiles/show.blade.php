@extends('layouts.app')

@section('title', 'Détails du profil')

@section('content')
    <h1>{{ $profile->firstname }} {{ $profile->lastname }}</h1>
    <p><strong>Status:</strong> {{ $profile->status }}</p>
    @if($profile->image)
        <img src="{{ asset('storage/images/' . $profile->image) }}" alt="Image du profil">
    @endif
@endsection