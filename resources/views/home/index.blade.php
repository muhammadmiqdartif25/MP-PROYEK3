@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
    <h1>Halo, {{ auth()->user()->full_name }}</h1>
    <p class="text-muted">
        Anda masuk sebagai <strong>{{ auth()->user()->role }}</strong>.
    </p>

    <p class="mt-4 text-muted">
        Buka menu Laporan untuk melihat laporan yang masuk beserta status terbarunya.
    </p>
@endsection
