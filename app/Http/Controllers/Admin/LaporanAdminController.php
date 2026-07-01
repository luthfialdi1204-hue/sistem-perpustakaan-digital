<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\FormatsBuku;
use App\Http\Controllers\Concerns\FormatsPeminjaman;
use App\Models\DetailPeminjaman;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LaporanAdminController extends Controller
{
    use FormatsBuku;
    use FormatsPeminjaman;

    public function index(Request $request)
    {
        $this->syncOverdueStatuses();

        $perPage = 10;
        $page = (int) $request->input('page', 1);
        if ($page < 1) {
            $page = 1;
        }

        $query = $this->applyPeminjamanSearch($this->peminjamanBaseQuery(), $request)
            ->orderByDesc('kode_detail');

        $start = trim((string) $request->input('start', $request->input('date_start', '')));
        if ($start !== '') {
            $query->whereDate('tgl_Peminjaman', '>=', $start);
        }
        $end = trim((string) $request->input('end', $request->input('date_end', '')));
        if ($end !== '') {
            $query->whereDate('tgl_Peminjaman', '<=', $end);
        }

        $allQuery = $query->clone();
        $paginated = $query->paginate($perPage)->appends($request->query());
        $rows = $paginated->getCollection()->map(function (DetailPeminjaman $d) {
            $row = $this->formatPeminjamanRow($d);
            if (($row['status'] ?? '') === 'Terlambat' && ($row['telat'] ?? '') !== '') {
                $row['telatNote'] = 'Telat '.$row['telat'];
            }
            $row['borrowAt'] = $row['borrowIso'] ? \Carbon\Carbon::parse($row['borrowIso'])->translatedFormat('d/F/Y') : '—';
            $row['dueAt']    = $row['dueIso'] ? \Carbon\Carbon::parse($row['dueIso'])->translatedFormat('d/F/Y') : '—';
            return $row;
        })->values();


        $all = $allQuery->get()->map(function (DetailPeminjaman $d) {
            $row = $this->formatPeminjamanRow($d);
            if (($row['status'] ?? '') === 'Terlambat' && ($row['telat'] ?? '') !== '') {
                $row['telatNote'] = 'Telat '.$row['telat'];
            }
            $row['borrowAt'] = $row['borrowIso'] ? \Carbon\Carbon::parse($row['borrowIso'])->translatedFormat('d/F/Y') : '—';
            $row['dueAt']    = $row['dueIso'] ? \Carbon\Carbon::parse($row['dueIso'])->translatedFormat('d/F/Y') : '—';
            return $row;
        })->values();

        $topFines = collect($all)->map(function ($row) {
            $raw = (string) ($row['denda'] ?? '');
            $amt = 0;
            if ($raw !== '' && $raw !== '—') {
                $amt = (int) preg_replace('/\D+/', '', $raw);
            }
            return [
                'member' => $row['member'] ?? '',
                'amt' => $amt,
            ];
        })
        ->filter(fn($x) => $x['amt'] > 0)
        ->groupBy('member')
        ->map(function ($items, $member) {
            $totalAmt = $items->sum('amt');
            return [
                'member' => $member,
                'amt' => $totalAmt,
                'denda' => 'Rp' . number_format($totalAmt, 0, ',', '.'),
            ];
        })
        ->sortByDesc('amt')
        ->take(5)
        ->values()
        ->toArray();

        $allStats = DetailPeminjaman::all()->map(function (DetailPeminjaman $d) {
            return $this->formatPeminjamanRow($d);
        });

        $total = $allStats->count();
        $countByStatus = fn (string $s) => $allStats->where('status', $s)->count();
        $dikembalikan = $countByStatus('Sudah Lunas') + $countByStatus('Dikembalikan');
        $terlambat = $countByStatus('Terlambat');
        $mengajukan = $countByStatus('Mengajukan');
        $sedangDipinjam = $countByStatus('Sedang Dipinjam');
        $ditolak = $countByStatus('Ditolak');
        $dibatalkan = $countByStatus('Dibatalkan');
        $totalDenda = $allStats->reduce(function (int $sum, array $row) {
            $raw = (string) ($row['denda'] ?? '');
            if ($raw === '' || $raw === '—') {
                return $sum;
            }
            $n = (int) preg_replace('/\D+/', '', $raw);
            return $sum + max(0, $n);
        }, 0);

        return view('Admin.laporan_admin', [
            'rows' => $rows,
            'top_fines' => $topFines,
            'meta' => [
                'current_page' => $paginated->currentPage(),
                'last_page' => $paginated->lastPage(),
                'total' => $paginated->total(),
                'per_page' => $paginated->perPage(),
            ],
            'stats' => [
                'total' => $total,
                'selesai' => $dikembalikan,
                'dikembalikan' => $dikembalikan,
                'terlambat' => $terlambat,
                'mengajukan' => $mengajukan,
                'sedang_dipinjam' => $sedangDipinjam,
                'ditolak' => $ditolak,
                'dibatalkan' => $dibatalkan,
                'total_denda' => $totalDenda,
            ],
            'filters' => [
                'q' => (string) $request->input('q', $request->input('search', '')),
                'status' => (string) $request->input('status', 'all'),
                'start' => (string) $start,
                'end' => (string) $end,
            ],
        ]);
    }

    public function list(Request $request): JsonResponse
    {
        $this->syncOverdueStatuses();

        $perPage = (int) $request->input('per_page', 10);
        if ($perPage < 1) {
            $perPage = 10;
        }
        if ($perPage > 10000) {
            $perPage = 10000;
        }
        $page = (int) $request->input('page', 1);
        if ($page < 1) {
            $page = 1;
        }

        $query = $this->applyPeminjamanSearch($this->peminjamanBaseQuery(), $request)
            ->orderByDesc('kode_detail');

        $start = trim((string) $request->input('start', $request->input('date_start', '')));
        if ($start !== '') {
            $query->whereDate('tgl_Peminjaman', '>=', $start);
        }
        $end = trim((string) $request->input('end', $request->input('date_end', '')));
        if ($end !== '') {
            $query->whereDate('tgl_Peminjaman', '<=', $end);
        }

        $all = $query->get()
            ->map(function (DetailPeminjaman $d) {
                $row = $this->formatPeminjamanRow($d);

                if (($row['status'] ?? '') === 'Terlambat' && ($row['telat'] ?? '') !== '') {
                    $row['telatNote'] = 'Telat '.$row['telat'];
                }

                $row['borrowAt'] = $row['borrowIso'] ? \Carbon\Carbon::parse($row['borrowIso'])->translatedFormat('d/F/Y') : '—';
                $row['dueAt']    = $row['dueIso'] ? \Carbon\Carbon::parse($row['dueIso'])->translatedFormat('d/F/Y') : '—';

                return $row;
            })
            ->values();

        $total = $all->count();
        $lastPage = max(1, (int) ceil($total / $perPage));
        if ($page > $lastPage) {
            $page = $lastPage;
        }

        $slice = $all->slice(($page - 1) * $perPage, $perPage)->values();

        $allStats = DetailPeminjaman::all()->map(function (DetailPeminjaman $d) {
            return $this->formatPeminjamanRow($d);
        });

        $totalStats = $allStats->count();
        $countByStatusStats = fn (string $s) => $allStats->where('status', $s)->count();
        $dikembalikan = $countByStatusStats('Sudah Lunas') + $countByStatusStats('Dikembalikan');
        $terlambat = $countByStatusStats('Terlambat');
        $mengajukan = $countByStatusStats('Mengajukan');
        $sedangDipinjam = $countByStatusStats('Sedang Dipinjam');
        $ditolak = $countByStatusStats('Ditolak');
        $dibatalkan = $countByStatusStats('Dibatalkan');
        $totalDenda = $allStats->reduce(function (int $sum, array $row) {
            $raw = (string) ($row['denda'] ?? '');
            if ($raw === '' || $raw === '—') {
                return $sum;
            }
            $n = (int) preg_replace('/\D+/', '', $raw);
            return $sum + max(0, $n);
        }, 0);

        return response()->json([
            'data' => $slice,
            'meta' => [
                'current_page' => $page,
                'last_page' => $lastPage,
                'total' => $total,
                'per_page' => $perPage,
            ],
            'stats' => [
                'total' => $totalStats,
                'selesai' => $dikembalikan,
                'dikembalikan' => $dikembalikan,
                'terlambat' => $terlambat,
                'mengajukan' => $mengajukan,
                'sedang_dipinjam' => $sedangDipinjam,
                'ditolak' => $ditolak,
                'dibatalkan' => $dibatalkan,
                'total_denda' => $totalDenda,
            ],
        ]);
    }
}
