<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\ReportReview;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ReportStatusController extends Controller
{
    /**
     * Ubah status laporan satu langkah ke depan, wajib disertai catatan.
     */
    public function update(Request $request, Report $report): RedirectResponse
    {
        $user = $request->user();
        $transition = Report::transitions()[$report->status] ?? null;

        if ($transition === null || ! in_array($user->role, $transition['roles'], true)) {
            abort(403);
        }

        if ($request->input('status') !== $transition['next']) {
            throw ValidationException::withMessages([
                'status' => 'Status hanya dapat maju satu langkah dari status saat ini.',
            ])->errorBag('statusForm');
        }

        // BR-04: laporan tanpa bukti tidak boleh diteruskan.
        if ($transition['next'] === 'diteruskan' && ! $report->hasEvidence()) {
            throw ValidationException::withMessages([
                'status' => 'Laporan tanpa bukti tidak dapat diteruskan.',
            ])->errorBag('statusForm');
        }

        $rules = [
            'note' => ['required', 'string', 'min:10', 'max:1000'],
        ];

        if ($transition['next'] === 'diteruskan') {
            $rules['forwarded_to'] = ['required', 'string', 'max:150'];
            $rules['forwarding_note'] = ['required', 'string', 'max:1000'];
        }

        $validated = $request->validateWithBag('statusForm', $rules, [
            'note.required' => 'Catatan wajib diisi.',
            'note.min' => 'Catatan minimal :min karakter.',
            'note.max' => 'Catatan maksimal :max karakter.',
            'forwarded_to.required' => 'Tujuan penerusan wajib diisi.',
            'forwarded_to.max' => 'Tujuan penerusan maksimal :max karakter.',
            'forwarding_note.required' => 'Catatan penerusan wajib diisi.',
            'forwarding_note.max' => 'Catatan penerusan maksimal :max karakter.',
        ]);

        DB::transaction(function () use ($report, $user, $transition, $validated): void {
            ReportReview::create([
                'report_id' => $report->id,
                'user_id' => $user->id,
                'note' => $validated['note'],
            ]);

            if ($transition['next'] === 'diteruskan') {
                $report->forwarded_to = $validated['forwarded_to'];
                $report->forwarding_note = $validated['forwarding_note'];
            }

            $report->status = $transition['next'];
            $report->save();
        });

        return redirect()
            ->route('reports.show', $report)
            ->with('status', 'Status laporan diperbarui.');
    }
}
