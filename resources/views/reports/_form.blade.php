@php($report = $report ?? null)
@php($isEdit = $report !== null)

<form method="POST"
      action="{{ $isEdit ? route('reports.update', $report) : route('reports.store') }}"
      enctype="multipart/form-data">
    @csrf
    @if ($isEdit)
        @method('PUT')
    @endif

    <div class="form-grid">
        <div class="form-group form-grid__full">
            <label for="title" class="form-label">Judul</label>
            <input type="text" id="title" name="title" value="{{ old('title', $report?->title) }}"
                   class="form-input" maxlength="150" required
                   @error('title') aria-invalid="true" aria-describedby="title-error" @enderror>
            @error('title')
                <p class="form-error" id="title-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group form-grid__full">
            <label for="description" class="form-label">Deskripsi</label>
            <textarea id="description" name="description" rows="5" class="form-input" required
                      @error('description') aria-invalid="true" aria-describedby="description-error" @enderror>{{ old('description', $report?->description) }}</textarea>
            @error('description')
                <p class="form-error" id="description-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label for="incident_at" class="form-label">Waktu Kejadian</label>
            <input type="datetime-local" id="incident_at" name="incident_at"
                   value="{{ old('incident_at', $report?->incident_at?->format('Y-m-d\TH:i')) }}"
                   class="form-input" required
                   @error('incident_at') aria-invalid="true" aria-describedby="incident_at-error" @enderror>
            @error('incident_at')
                <p class="form-error" id="incident_at-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label for="area_id" class="form-label">Wilayah</label>
            <select id="area_id" name="area_id" class="form-input" required
                    data-area-info="area-info-panel"
                    data-area-info-url-template="{{ url('/area/__ID__/info') }}"
                    @error('area_id') aria-invalid="true" aria-describedby="area_id-error" @enderror>
                <option value="">Pilih wilayah</option>
                @foreach ($areas as $area)
                    <option value="{{ $area->id }}" @selected((string) old('area_id', $report?->area_id) === (string) $area->id)>
                        {{ $area->name }}
                    </option>
                @endforeach
            </select>
            @error('area_id')
                <p class="form-error" id="area_id-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group form-grid__full">
            <div id="area-info-panel" class="area-info" aria-live="polite">
                <p class="area-info__hint text-muted">Pilih wilayah untuk melihat ringkasan.</p>
            </div>
        </div>

        <div class="form-group form-grid__full">
            <label for="address" class="form-label">Alamat</label>
            <textarea id="address" name="address" rows="2" class="form-input" required
                      @error('address') aria-invalid="true" aria-describedby="address-error" @enderror>{{ old('address', $report?->address) }}</textarea>
            @error('address')
                <p class="form-error" id="address-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label for="latitude" class="form-label">Lintang</label>
            <input type="number" id="latitude" name="latitude" step="any" inputmode="decimal"
                   value="{{ old('latitude', $report?->latitude) }}" class="form-input" required
                   @error('latitude') aria-invalid="true" aria-describedby="latitude-error" @enderror>
            @error('latitude')
                <p class="form-error" id="latitude-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label for="longitude" class="form-label">Bujur</label>
            <input type="number" id="longitude" name="longitude" step="any" inputmode="decimal"
                   value="{{ old('longitude', $report?->longitude) }}" class="form-input" required
                   @error('longitude') aria-invalid="true" aria-describedby="longitude-error" @enderror>
            @error('longitude')
                <p class="form-error" id="longitude-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group form-grid__full">
            <label for="evidence" class="form-label">Bukti (opsional)</label>
            <input type="file" id="evidence" name="evidence" accept=".jpg,.jpeg,.png,.pdf" class="form-input"
                   @error('evidence') aria-invalid="true" aria-describedby="evidence-error" @enderror>
            <p class="form-hint">Format JPG, JPEG, PNG, atau PDF. Ukuran maksimal 2 MB.</p>
            @error('evidence')
                <p class="form-error" id="evidence-error">{{ $message }}</p>
            @enderror

            @if ($report?->evidence)
                <p class="form-hint">
                    Bukti saat ini: {{ $report->evidence->original_name }}.
                    Unggah berkas baru untuk menggantinya.
                </p>
            @endif
        </div>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn--inline">Simpan Laporan</button>
        <a href="{{ $isEdit ? route('reports.show', $report) : route('reports.index') }}">Batal</a>
    </div>
</form>
