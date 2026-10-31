@extends('layouts.app')

@section('title', $report->title)

@section('content')
    <div class="page-header">
        <div>
            <h1>{{ $report->title }}</h1>
            <p>
                <span class="badge badge--{{ $report->status }}">{{ ucfirst($report->status) }}</span>
            </p>
        </div>

        @can('update', $report)
            <a href="{{ route('reports.edit', $report) }}" class="btn btn--inline">Ubah Laporan</a>
        @endcan
    </div>

    <div class="panel">
        <dl class="detail-list">
            <dt>Wilayah</dt>
            <dd>{{ $report->area->name }}</dd>

            <dt>Pelapor</dt>
            <dd>{{ $report->user->full_name }}</dd>

            <dt>Waktu Kejadian</dt>
            <dd>{{ $report->incident_at->format('d/m/Y H:i') }}</dd>

            <dt>Alamat</dt>
            <dd>{{ $report->address }}</dd>

            <dt>Koordinat</dt>
            <dd>{{ number_format($report->latitude, 6) }}, {{ number_format($report->longitude, 6) }}</dd>

            <dt>Deskripsi</dt>
            <dd class="detail-list__prose">{{ $report->description }}</dd>

            <dt>Bukti</dt>
            <dd>
                @if ($report->evidence)
                    <a href="{{ route('reports.evidence', $report) }}">Unduh Bukti ({{ $report->evidence->original_name }})</a>
                @else
                    <span class="text-muted">Tidak ada bukti terlampir.</span>
                @endif
            </dd>

            @if ($report->forwarded_to)
                <dt>Diteruskan Ke</dt>
                <dd>{{ $report->forwarded_to }}</dd>
            @endif

            @if ($report->forwarding_note)
                <dt>Catatan Penerusan</dt>
                <dd class="detail-list__prose">{{ $report->forwarding_note }}</dd>
            @endif

            <dt>Dikirim Pada</dt>
            <dd>{{ $report->created_at->format('d/m/Y H:i') }}</dd>
        </dl>
    </div>

    @can('review-report')
        <section class="section">
            <h2>Laporan Lain di Wilayah Ini</h2>

            @if ($relatedReports->isEmpty())
                <p class="text-muted">Belum ada laporan lain di wilayah ini.</p>
            @else
                <ul class="plain-list">
                    @foreach ($relatedReports as $related)
                        <li>
                            <a href="{{ route('reports.show', $related) }}">{{ $related->title }}</a>
                            <span class="text-muted">
                                {{ ucfirst($related->status) }}, {{ $related->incident_at->format('d/m/Y H:i') }}
                            </span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </section>
    @endcan

    <p class="mt-4">
        <a href="{{ route('reports.index') }}">Kembali ke daftar laporan</a>
    </p>
@endsection
