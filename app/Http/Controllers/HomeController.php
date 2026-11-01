<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $statuses = ['baru', 'diperiksa', 'diteruskan', 'selesai'];

        if ($user->role === 'pelapor') {
            $aggregate = Report::query()
                ->ownedBy($user)
                ->selectRaw('status, COUNT(*) as jumlah')
                ->groupBy('status')
                ->get();

            $counts = [];

            foreach ($aggregate as $row) {
                $counts[$row->status] = (int) $row->jumlah;
            }

            $myCounts = [];

            foreach ($statuses as $status) {
                $myCounts[$status] = $counts[$status] ?? 0;
            }

            $myTotal = array_sum($myCounts);
            $mySelesai = $myCounts['selesai'];

            return view('home.index', [
                'myTotal' => $myTotal,
                'myActive' => $myTotal - $mySelesai,
                'mySelesai' => $mySelesai,
                'myEditable' => $myCounts['baru'],
            ]);
        }

        $areaId = $request->filled('area_id') && is_numeric($request->input('area_id'))
            ? (int) $request->input('area_id')
            : null;

        $aggregate = Report::query()
            ->when($areaId, fn ($query) => $query->where('area_id', $areaId))
            ->selectRaw('area_id, status, COUNT(*) as jumlah')
            ->groupBy('area_id', 'status')
            ->get();

        $counts = [];

        foreach ($aggregate as $row) {
            $counts[$row->area_id][$row->status] = (int) $row->jumlah;
        }

        $total = 0;
        $aktiv = 0;

        foreach ($counts as $perStatus) {
            foreach ($perStatus as $status => $jumlah) {
                $total += $jumlah;

                if ($status !== 'selesai') {
                    $aktiv += $jumlah;
                }
            }
        }

        $areas = Area::orderBy('name')->get();

        $rows = $areas
            ->when($areaId, fn ($collection) => $collection->where('id', $areaId))
            ->map(function (Area $area) use ($counts, $statuses): array {
                $row = ['name' => $area->name];
                $rowTotal = 0;

                foreach ($statuses as $status) {
                    $jumlah = $counts[$area->id][$status] ?? 0;
                    $row[$status] = $jumlah;
                    $rowTotal += $jumlah;
                }

                $row['total'] = $rowTotal;

                return $row;
            });

        return view('home.index', [
            'areas' => $areas,
            'areaId' => $areaId,
            'total' => $total,
            'aktiv' => $aktiv,
            'selesai' => $total - $aktiv,
            'rows' => $rows,
        ]);
    }
}
