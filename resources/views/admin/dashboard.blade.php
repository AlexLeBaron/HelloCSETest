@extends('layouts.app')

@section('title', 'Tableau de bord Admin')

@section('content')
    <h1>Bienvenue sur le panneau d'administration</h1>

    <p>C'est ici que vous pouvez gérer les profils et les utilisateurs.</p>

    @if(Session::has('admin_token'))
        <p><strong>Votre token API :</strong> {{ Session::get('admin_token') }}</p>
    @endif

    <!-- Exemple de liens vers la gestion des profils -->
    <ul>
        <li><a href="{{ route('profiles.index') }}">Voir tous les profils</a></li>
        <li><a href="{{ route('profiles.create') }}">Créer un nouveau profil</a></li>
    </ul>
@endsection
