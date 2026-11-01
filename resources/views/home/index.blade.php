@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
    <div class="page-header">
        <div>
            <h1>Halo, {{ auth()->user()->full_name }}</h1>
            <p>Anda masuk sebagai <strong>{{ auth()->user()->role }}</strong>.</p>
        </div>
    </div>

    @can('review-report')
        <p class="text-muted">Jumlah laporan karhutla per wilayah dan per status.</p>

        <form method="GET" action="{{ route('home.index') }}" class="filter-bar" role="search">
            <div class="form-group">
                <label for="area_id" class="form-label">Wilayah</label>
                <select id="area_id" name="area_id" class="form-input"
                        data-area-info="area-info-panel-home"
                        data-area-info-url-template="{{ url('/area/__ID__/info') }}">
                    <option value="">Semua wilayah</option>
                    @foreach ($areas as $area)
                        <option value="{{ $area->id }}" @selected($areaId === $area->id)>
                            {{ $area->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="filter-bar__actions">
                <button type="submit" class="btn btn--inline">Terapkan</button>
                <a href="{{ route('home.index') }}">Reset</a>
            </div>
        </form>

        <div id="area-info-panel-home" class="area-info" aria-live="polite">
            <p class="area-info__hint text-muted">Pilih wilayah untuk melihat ringkasan.</p>
        </div>

        <dl class="stat-row">
            <div class="stat">
                <dt class="stat__label">Total laporan</dt>
                <dd class="stat__value">{{ $total }}</dd>
            </div>
            <div class="stat">
                <dt class="stat__label">Laporan aktif</dt>
                <dd class="stat__value">{{ $aktiv }}</dd>
            </div>
            <div class="stat">
                <dt class="stat__label">Laporan selesai</dt>
                <dd class="stat__value">{{ $selesai }}</dd>
            </div>
        </dl>

        <div class="table-wrap mt-4">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Wilayah</th>
                        <th scope="col" class="num">Baru</th>
                        <th scope="col" class="num">Diperiksa</th>
                        <th scope="col" class="num">Diteruskan</th>
                        <th scope="col" class="num">Selesai</th>
                        <th scope="col" class="num">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($rows as $row)
                        <tr>
                            <td>{{ $row['name'] }}</td>
                            <td class="num">{{ $row['baru'] }}</td>
                            <td class="num">{{ $row['diperiksa'] }}</td>
                            <td class="num">{{ $row['diteruskan'] }}</td>
                            <td class="num">{{ $row['selesai'] }}</td>
                            <td class="num">{{ $row['total'] }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="empty-cell">Belum ada data laporan untuk filter ini.</td>
                        </tr>
                    @endforelse
                </tbody>
                @if ($rows->isNotEmpty())
                    <tfoot>
                        <tr>
                            <th scope="row">Jumlah</th>
                            <td class="num">{{ $rows->sum('baru') }}</td>
                            <td class="num">{{ $rows->sum('diperiksa') }}</td>
                            <td class="num">{{ $rows->sum('diteruskan') }}</td>
                            <td class="num">{{ $rows->sum('selesai') }}</td>
                            <td class="num">{{ $rows->sum('total') }}</td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    @else
        <p class="text-muted">Ringkasan laporan yang Anda kirim sendiri.</p>

        @if ($myTotal === 0)
            <p class="text-muted">Anda belum mengirim laporan.</p>
        @else
            <dl class="stat-row">
                <div class="stat">
                    <dt class="stat__label">Total laporan saya</dt>
                    <dd class="stat__value">{{ $myTotal }}</dd>
                </div>
                <div class="stat">
                    <dt class="stat__label">Sedang diproses</dt>
                    <dd class="stat__value">{{ $myActive }}</dd>
                </div>
                <div class="stat">
                    <dt class="stat__label">Selesai</dt>
                    <dd class="stat__value">{{ $mySelesai }}</dd>
                </div>
            </dl>
        @endif

        <p class="mt-4">
            @if ($myEditable > 0)
                {{ $myEditable }} laporan masih bisa diperbaiki karena statusnya Baru.
            @else
                <span class="text-muted">Tidak ada laporan yang sedang bisa diperbaiki.</span>
            @endif
        </p>

        <p>
            <a href="{{ route('reports.index') }}">Lihat laporan saya</a>
        </p>
    @endcan
@endsection
