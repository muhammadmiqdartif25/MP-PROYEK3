<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\ReportReview;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ReportReviewController extends Controller
{
    /**
     * Simpan catatan peninjauan tanpa mengubah status laporan.
     */
    public function store(Request $request, Report $report): RedirectResponse
    {
        Gate::authorize('review-report');

        $validated = $request->validateWithBag('reviewForm', [
            'note' => ['required', 'string', 'min:10', 'max:1000'],
        ], [
            'note.required' => 'Catatan wajib diisi.',
            'note.min' => 'Catatan minimal :min karakter.',
            'note.max' => 'Catatan maksimal :max karakter.',
        ]);

        ReportReview::create([
            'report_id' => $report->id,
            'user_id' => $request->user()->id,
            'note' => $validated['note'],
        ]);

        return redirect()
            ->route('reports.show', $report)
            ->with('status', 'Catatan peninjauan disimpan.');
    }
}
