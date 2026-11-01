<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Report;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class AreaController extends Controller
{
    public function index(): View
    {
        return view('area.index', [
            'areas' => Area::withCount('reports')->orderBy('name')->get(),
        ]);
    }

    /**
     * Ringkasan laporan satu wilayah untuk permintaan fetch() dari sisi klien.
     */
    public function info(Area $area): JsonResponse
    {
        $activeCount = $area->reports()
            ->whereIn('status', ['baru', 'diperiksa', 'diteruskan'])
            ->count();

        $recentReports = $area->reports()
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->take(3)
            ->get(['id', 'title', 'status', 'created_at']);

        return response()->json([
            'area' => [
                'id' => $area->id,
                'name' => $area->name,
            ],
            'active_count' => $activeCount,
            'recent_reports' => $recentReports
                ->map(fn (Report $report): array => [
                    'id' => $report->id,
                    'title' => $report->title,
                    'status' => $report->status,
                    'created_at' => $report->created_at->format('d/m/Y H:i'),
                ])
                ->all(),
        ]);
    }
}
