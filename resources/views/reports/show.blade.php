@extends('layouts.app')

@section('title', $report->title)

@section('content')
    @php($transition = App\Models\Report::transitions()[$report->status] ?? null)
    @php($canAdvance = $transition !== null && in_array(auth()->user()->role, $transition['roles'], true))
    @php($statusBag = $errors->getBag('statusForm'))
    @php($reviewBag = $errors->getBag('reviewForm'))

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
                @if ($report->hasEvidence())
                    <a href="{{ route('reports.evidence', $report) }}">Unduh Bukti</a>
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

    @if ($canAdvance)
        @php($targetStatus = $transition['next'])
        @php($needsForwarding = $targetStatus === 'diteruskan')

        <section class="section">
            <h2>Aksi Status</h2>

            <form method="POST" action="{{ route('reports.status', $report) }}" class="panel">
                @csrf
                @method('PATCH')

                <p class="status-target">Status berikutnya: <strong>{{ ucfirst($targetStatus) }}</strong></p>

                <input type="hidden" name="status" value="{{ $targetStatus }}">

                @if ($statusBag->has('status'))
                    <p class="form-error">{{ $statusBag->first('status') }}</p>
                @endif

                <div class="form-group">
                    <label for="note" class="form-label">Catatan</label>
                    <textarea id="note" name="note" rows="4" class="form-input" required
                              @if ($statusBag->has('note')) aria-invalid="true" aria-describedby="note-error" @endif>{{ $statusBag->any() ? old('note') : '' }}</textarea>
                    <p class="form-hint" id="note-hint">Minimal 10 karakter, maksimal 1000 karakter.</p>
                    @if ($statusBag->has('note'))
                        <p class="form-error" id="note-error">{{ $statusBag->first('note') }}</p>
                    @endif
                </div>

                @if ($needsForwarding)
                    <div class="form-group">
                        <label for="forwarded_to" class="form-label">Diteruskan Kepada</label>
                        <input type="text" id="forwarded_to" name="forwarded_to" value="{{ old('forwarded_to') }}"
                               class="form-input" maxlength="150" required
                               @if ($statusBag->has('forwarded_to')) aria-invalid="true" aria-describedby="forwarded_to-error" @endif>
                        @if ($statusBag->has('forwarded_to'))
                            <p class="form-error" id="forwarded_to-error">{{ $statusBag->first('forwarded_to') }}</p>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="forwarding_note" class="form-label">Catatan Penerusan</label>
                        <textarea id="forwarding_note" name="forwarding_note" rows="3" class="form-input" required
                                  @if ($statusBag->has('forwarding_note')) aria-invalid="true" aria-describedby="forwarding_note-error" @endif>{{ old('forwarding_note') }}</textarea>
                        @if ($statusBag->has('forwarding_note'))
                            <p class="form-error" id="forwarding_note-error">{{ $statusBag->first('forwarding_note') }}</p>
                        @endif
                    </div>
                @endif

                <div class="form-actions">
                    <button type="submit" class="btn btn--inline">Tandai sebagai {{ ucfirst($targetStatus) }}</button>
                </div>
            </form>
        </section>
    @endif

    <section class="section">
        <h2>Catatan Peninjauan</h2>

        @if ($report->reviews->isEmpty())
            <p class="text-muted">Belum ada catatan peninjauan untuk laporan ini.</p>
        @else
            <ul class="review-list">
                @foreach ($report->reviews->sortByDesc('created_at') as $review)
                    <li class="review-list__item">
                        <p class="review-list__note">{{ $review->note }}</p>
                        <p class="meta-line">
                            {{ $review->user->full_name }}, {{ $review->created_at->format('d/m/Y H:i') }}
                        </p>
                    </li>
                @endforeach
            </ul>
        @endif

        @can('review-report')
            <form method="POST" action="{{ route('reports.review', $report) }}" class="panel review-form">
                @csrf

                <div class="form-group">
                    <label for="review-note" class="form-label">Tambah Catatan</label>
                    <textarea id="review-note" name="note" rows="3" class="form-input" required
                              @if ($reviewBag->has('note')) aria-invalid="true" aria-describedby="review-note-error" @endif>{{ $reviewBag->any() ? old('note') : '' }}</textarea>
                    <p class="form-hint">Minimal 10 karakter, maksimal 1000 karakter. Status laporan tidak berubah.</p>
                    @if ($reviewBag->has('note'))
                        <p class="form-error" id="review-note-error">{{ $reviewBag->first('note') }}</p>
                    @endif
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn--inline">Simpan Catatan</button>
                </div>
            </form>
        @endcan
    </section>

    <section class="section">
        <h2>Riwayat Status</h2>

        @if ($report->statusLogs->isEmpty())
            <p class="text-muted">Belum ada riwayat perubahan status.</p>
        @else
            <ul class="plain-list">
                @foreach ($report->statusLogs->sortByDesc('created_at') as $log)
                    <li>
                        <span class="meta-line">{{ $log->created_at->format('d/m/Y H:i') }}</span>
                        {{ $log->user?->full_name ?? 'Sistem' }}
                        <span class="status-arrow">{{ ucfirst($log->old_status) }} &rarr; {{ ucfirst($log->new_status) }}</span>
                    </li>
                @endforeach
            </ul>
        @endif
    </section>

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
