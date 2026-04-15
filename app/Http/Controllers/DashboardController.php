<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Absensi;
use App\Models\Semester;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $tanggal = $request->get('tanggal', Carbon::today()->format('Y-m-d'));
        $semesterAktif = Semester::active()->first();
        $tahunAjaranAktif = TahunAjaran::active()->first();

        $stats = $this->getDailyStats($tanggal, $semesterAktif);
        $chartData = $this->getChartData($request, $semesterAktif);
        $recentActivity = Absensi::with('siswa.kelas')
            ->orderBy('tanggal', 'desc')
            ->limit(10)
            ->get();

        return view('dashboard', compact(
            'stats',
            'chartData',
            'recentActivity',
            'tanggal',
            'semesterAktif',
            'tahunAjaranAktif'
        ));
    }

    private function getDailyStats($tanggal, $semester)
    {
        $query = Absensi::byDate($tanggal);
        if ($semester) {
            $query->bySemester($semester->id);
        }

        $total = $query->count();
        
        return [
            'total' => $total,
            'hadir' => (clone $query)->byStatus('Hadir')->count(),
            'sakit' => (clone $query)->byStatus('Sakit')->count(),
            'izin' => (clone $query)->byStatus('Izin')->count(),
            'alfa' => (clone $query)->byStatus('Alfa')->count(),
            'terlambat' => (clone $query)->byStatus('Terlambat')->count(),
        ];
    }

    private function getChartData(Request $request, $semester)
    {
        $period = $request->get('period', 7);
        $startDate = Carbon::today()->subDays($period - 1);

        $labels = [];
        $hadirData = [];
        $sakitData = [];
        $izinData = [];
        $alfaData = [];

        for ($i = 0; $i < $period; $i++) {
            $date = $startDate->copy()->addDays($i);
            $labels[] = $date->format('d/m');
            
            $query = Absensi::byDate($date);
            if ($semester) {
                $query->bySemester($semester->id);
            }

            $hadirData[] = (clone $query)->byStatus('Hadir')->count();
            $sakitData[] = (clone $query)->byStatus('Sakit')->count();
            $izinData[] = (clone $query)->byStatus('Izin')->count();
            $alfaData[] = (clone $query)->byStatus('Alfa')->count();
        }

        return compact('labels', 'hadirData', 'sakitData', 'izinData', 'alfaData');
    }
}
