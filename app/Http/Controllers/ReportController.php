<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReportRequest;
use App\Models\Area;
use App\Models\Report;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $reports = Report::query()
            ->with(['user', 'area'])
            ->when($user->role === 'pelapor', fn ($query) => $query->ownedBy($user))
            ->when($request->filled('q'), function ($query) use ($request): void {
                $keyword = '%'.$request->string('q').'%';
                $query->where(function ($query) use ($keyword): void {
                    $query->where('title', 'like', $keyword)
                        ->orWhere('description', 'like', $keyword);
                });
            })
            ->when($request->filled('area_id'), fn ($query) => $query->where('area_id', $request->integer('area_id')))
            ->when($request->filled('status'), fn ($query) => $query->where('status', (string) $request->string('status')))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('reports.index', [
            'reports' => $reports,
            'areas' => Area::orderBy('name')->get(),
        ]);
    }

    public function create(): View
    {
        return view('reports.create', [
            'areas' => Area::orderBy('name')->get(),
        ]);
    }

    public function store(ReportRequest $request): RedirectResponse
    {
        $report = DB::transaction(function () use ($request): Report {
            $report = Report::create([
                ...$request->safe()->except('evidence'),
                'user_id' => $request->user()->id,
                'status' => 'baru',
            ]);

            $this->storeEvidence($report, $request->file('evidence'));

            return $report;
        });

        return redirect()
            ->route('reports.show', $report)
            ->with('status', 'Laporan berhasil dikirim.');
    }

    public function show(Request $request, Report $report): View
    {
        $this->authorize('view', $report);

        $report->load(['user', 'area', 'evidence']);

        $relatedReports = collect();

        if (in_array($request->user()->role, ['relawan', 'koordinator'], true)) {
            $relatedReports = Report::query()
                ->with('user')
                ->where('area_id', $report->area_id)
                ->whereKeyNot($report->id)
                ->latest()
                ->take(5)
                ->get();
        }

        return view('reports.show', [
            'report' => $report,
            'relatedReports' => $relatedReports,
        ]);
    }

    public function edit(Report $report): View
    {
        $this->authorize('update', $report);

        return view('reports.edit', [
            'report' => $report,
            'areas' => Area::orderBy('name')->get(),
        ]);
    }

    public function update(ReportRequest $request, Report $report): RedirectResponse
    {
        $this->authorize('update', $report);

        DB::transaction(function () use ($request, $report): void {
            $report->update($request->safe()->except('evidence'));

            $this->storeEvidence($report, $request->file('evidence'));
        });

        return redirect()
            ->route('reports.show', $report)
            ->with('status', 'Laporan berhasil diperbarui.');
    }

    public function evidence(Report $report): StreamedResponse
    {
        $this->authorize('view', $report);

        $evidence = $report->evidence;

        abort_if($evidence === null, 404, 'Laporan ini tidak memiliki bukti.');

        abort_unless(Storage::disk('local')->exists($evidence->file_path), 404, 'Berkas bukti tidak ditemukan.');

        return Storage::disk('local')->download($evidence->file_path, $evidence->original_name);
    }

    /**
     * Simpan berkas bukti bila ada, menggantikan bukti lama pada laporan yang sama.
     */
    private function storeEvidence(Report $report, ?UploadedFile $file): void
    {
        if ($file === null) {
            return;
        }

        $path = $file->store('evidence', 'local');

        $report->evidence()->updateOrCreate([], [
            'file_path' => $path,
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType() ?? 'application/octet-stream',
            'file_size' => $file->getSize(),
        ]);
    }
}
