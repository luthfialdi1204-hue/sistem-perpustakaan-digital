<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\FormatsBuku;
use App\Models\Buku;
use Illuminate\Http\Request;

class Katalog_Buku extends Controller
{
    use FormatsBuku;

    public function beranda()
    {
        $rows = Buku::query()
            ->inRandomOrder()
            ->limit(8)
            ->get()
            ->map(fn (Buku $b) => $this->formatBukuRow($b))
            ->values();

        return view('Mahasiswa.Beranda_Mahasiswa', [
            'rows' => $rows,
        ]);
    }

    public function index(Request $request)
    {
        $perPage = 12;
        $paginated = $this->applyBukuSearch(Buku::query(), $request)
            ->orderByDesc('kode_buku')
            ->paginate($perPage)
            ->appends($request->query());

        $rows = $paginated->getCollection()
            ->map(fn (Buku $b) => $this->formatBukuRow($b))
            ->values();

        return view('Mahasiswa.katalog_Buku', [
            'rows' => $rows,
            'categories' => $this->bukuCategories(),
            'meta' => [
                'current_page' => $paginated->currentPage(),
                'last_page' => $paginated->lastPage(),
                'total' => $paginated->total(),
                'per_page' => $paginated->perPage(),
            ],
            'filters' => [
                'q' => (string) $request->input('q', $request->input('search', '')),
                'category' => (string) $request->input('category', 'all'),
            ],
        ]);
    }

    public function list(Request $request)
    {
        $query = $this->applyBukuSearch(Buku::query(), $request);

        if ($request->boolean('random')) {
            $query->inRandomOrder();
        } else {
            $query->orderByDesc('kode_buku');
        }

        $limit = $request->integer('limit');
        if ($limit > 0) {
            $query->limit($limit);
        }

        $perPage = $request->integer('per_page');
        if ($limit <= 0 && $perPage > 0) {
            if ($perPage < 1) {
                $perPage = 12;
            }
            if ($perPage > 50) {
                $perPage = 50;
            }

            $paginated = $query->paginate($perPage);
            $rows = $paginated->getCollection()->map(fn (Buku $b) => $this->formatBukuRow($b))->values();

            return response()->json([
                'data' => $rows,
                'categories' => $this->bukuCategories(),
                'meta' => [
                    'current_page' => $paginated->currentPage(),
                    'last_page' => $paginated->lastPage(),
                    'total' => $paginated->total(),
                    'per_page' => $paginated->perPage(),
                ],
            ]);
        }

        $rows = $query->get()->map(fn (Buku $b) => $this->formatBukuRow($b))->values();

        return response()->json([
            'data' => $rows,
            'categories' => $this->bukuCategories(),
        ]);
    }
}
