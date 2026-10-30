@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
    <h1>Pelaporan Kebakaran Hutan dan Lahan</h1>
    <p class="text-muted">
        Sampaikan indikasi kebakaran di sekitar Anda dan pantau perkembangannya.
    </p>

    <p class="mt-4">
        <a href="{{ route('login.index') }}" class="btn">Masuk untuk melapor</a>
    </p>
@endsection
