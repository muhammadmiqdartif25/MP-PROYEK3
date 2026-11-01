@extends('layouts.app')

@section('title', 'Laporan')

@section('content')
    @php($isPelapor = auth()->user()->role === 'pelapor')

    <div class="page-header">
        <div>
            <h1>Laporan</h1>
            <p class="text-muted">Daftar laporan kejadian karhutla.</p>
        </div>

        @can('create', App\Models\Report::class)
            <a href="{{ route('reports.create') }}" class="btn btn--inline">Buat Laporan</a>
        @endcan
    </div>

    <form method="GET" action="{{ route('reports.index') }}" class="filter-bar" role="search">
        <div class="form-group">
            <label for="q" class="form-label">Cari</label>
            <input type="search" id="q" name="q" value="{{ request('q') }}" class="form-input"
                   placeholder="Judul atau deskripsi">
        </div>

        @can('review-report')
            <div class="form-group">
                <label for="area_id" class="form-label">Wilayah</label>
                <select id="area_id" name="area_id" class="form-input"
                        data-area-info="area-info-panel-index"
                        data-area-info-url-template="{{ url('/area/__ID__/info') }}">
                    <option value="">Semua wilayah</option>
                    @foreach ($areas as $area)
                        <option value="{{ $area->id }}" @selected((string) request('area_id') === (string) $area->id)>
                            {{ $area->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        @endcan

        <div class="form-group">
            <label for="status" class="form-label">Status</label>
            <select id="status" name="status" class="form-input">
                <option value="">Semua status</option>
                @foreach (['baru', 'diperiksa', 'diteruskan', 'selesai'] as $status)
                    <option value="{{ $status }}" @selected(request('status') === $status)>
                        {{ ucfirst($status) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="filter-bar__actions">
            <button type="submit" class="btn btn--inline">Terapkan</button>
            <a href="{{ route('reports.index') }}">Reset</a>
        </div>
    </form>

    @can('review-report')
        <div id="area-info-panel-index" class="area-info" aria-live="polite">
            <p class="area-info__hint text-muted">Pilih wilayah untuk melihat ringkasan.</p>
        </div>
    @endcan

    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Judul</th>
                    <th scope="col">Wilayah</th>
                    <th scope="col">Status</th>
                    <th scope="col">Waktu Kejadian</th>
                    @unless ($isPelapor)
                        <th scope="col">Pelapor</th>
                    @endunless
                    <th scope="col">Tindakan</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($reports as $report)
                    <tr>
                        <td>{{ $report->title }}</td>
                        <td>{{ $report->area->name }}</td>
                        <td>
                            <span class="badge badge--{{ $report->status }}">{{ ucfirst($report->status) }}</span>
                        </td>
                        <td>{{ $report->incident_at->format('d/m/Y H:i') }}</td>
                        @unless ($isPelapor)
                            <td>{{ $report->user->full_name }}</td>
                        @endunless
                        <td><a href="{{ route('reports.show', $report) }}">Lihat</a></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ $isPelapor ? 5 : 6 }}" class="empty-cell">
                            <span class="text-muted">Belum ada laporan yang cocok dengan filter.</span>
                            @can('create', App\Models\Report::class)
                                <a href="{{ route('reports.create') }}">Buat laporan pertama</a>
                            @endcan
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($reports->isNotEmpty())
        <div class="pagination-wrap">
            {{ $reports->links() }}
        </div>
    @endif
@endsection
