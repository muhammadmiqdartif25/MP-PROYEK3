@extends('layouts.app')

@section('title', 'Data Wilayah')

@section('content')
    <h1>Data Wilayah</h1>
    <p class="text-muted">Wilayah dipakai sebagai lokasi laporan dan dasar rekap.</p>

    <table class="table">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Nama Wilayah</th>
                <th scope="col">Jumlah Laporan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($areas as $area)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $area->name }}</td>
                    <td>{{ $area->reports_count }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-muted">Belum ada wilayah.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
