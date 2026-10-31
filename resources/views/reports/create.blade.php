@extends('layouts.app')

@section('title', 'Buat Laporan')

@section('content')
    <div class="page-header">
        <div>
            <h1>Buat Laporan</h1>
            <p class="text-muted">Isi data kejadian yang Anda temui, lalu kirim laporan.</p>
        </div>
    </div>

    <div class="panel">
        @include('reports._form')
    </div>
@endsection
