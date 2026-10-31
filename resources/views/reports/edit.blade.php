@extends('layouts.app')

@section('title', 'Ubah Laporan')

@section('content')
    <div class="page-header">
        <div>
            <h1>Ubah Laporan</h1>
            <p class="text-muted">Perubahan hanya bisa disimpan selama status laporan masih Baru.</p>
        </div>
    </div>

    <div class="panel">
        @include('reports._form')
    </div>
@endsection
