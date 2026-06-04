<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KelolaAnggotaAdminController extends Controller
{
    private function rules(int|string|null $exceptUserId = null): array
    {
        $emailRule = Rule::unique('user', 'email');
        if ($exceptUserId !== null) {
            $emailRule = $emailRule->ignore((int) $exceptUserId, 'id_user');
        }

        return [
            'nama' => ['required', 'string', 'max:255'],
            'nim' => ['required', 'digits_between:6,20'],
            'tipe' => ['required', 'in:Mahasiswa,Admin'],
            'email' => ['required', 'email', 'max:255', $emailRule],
            'password' => ['nullable', 'string', 'max:255'],
        ];
    }

    private function formatAnggotaRow(User $u): array
    {
        $role = $u->role_user ?? $u->role;
        $isAdmin = $role === User::ROLE_ADMIN;

        return [
            'id' => (int) $u->id_user,
            'nim' => $u->loginIdentifier() ?? '',
            'nama' => $u->nama_pengguna ?? '',
            'tipe' => $isAdmin ? 'Admin' : 'Mahasiswa',
            'email' => $u->email ?? '',
            'status' => 'Aktif',
            'initials' => $u->initials(),
        ];
    }

    private function identifierConflict(string $tipe, int $identifier, ?int $exceptUserId = null): bool
    {
        $isAdmin = $tipe === 'Admin';
        $query = User::query();

        if ($isAdmin) {
            $query->where('nip', $identifier)->where('nip', '>', 0);
        } else {
            $query->where('nim', $identifier)->where('nim', '>', 0);
        }

        if ($exceptUserId !== null) {
            $query->where('id_user', '!=', $exceptUserId);
        }

        return $query->exists();
    }

    public function index()
    {
        return view('Admin.kelola_anggota_admin');
    }

    public function list(Request $request)
    {
        $q = trim((string) $request->input('q', $request->input('search', '')));
        $perPage = (int) $request->input('per_page', 8);
        if ($perPage < 1) {
            $perPage = 8;
        }
        if ($perPage > 50) {
            $perPage = 50;
        }

        $query = User::query()
            ->select(['id_user', 'nama_pengguna', 'nim', 'nip', 'email', 'role_user'])
            ->orderByDesc('id_user');

        if ($q !== '') {
            $like = '%'.$q.'%';
            $query->where(function ($w) use ($like) {
                $w->where('nama_pengguna', 'like', $like)
                    ->orWhere('email', 'like', $like)
                    ->orWhere('nim', 'like', $like)
                    ->orWhere('nip', 'like', $like)
                    ->orWhere('role_user', 'like', $like);
            });
        }

        $paginated = $query->paginate($perPage);

        $rows = $paginated->getCollection()
            ->map(fn (User $u) => $this->formatAnggotaRow($u))
            ->values();

        return response()->json([
            'data' => $rows,
            'meta' => [
                'current_page' => $paginated->currentPage(),
                'last_page' => $paginated->lastPage(),
                'total' => $paginated->total(),
                'per_page' => $paginated->perPage(),
            ],
        ]);
    }

    public function show(int|string $id)
    {
        $user = User::query()->where('id_user', (int) $id)->first();
        if (! $user) {
            return response()->json(['message' => 'Anggota tidak ditemukan'], 404);
        }

        return response()->json([
            'data' => $this->formatAnggotaRow($user),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules(), $this->messages());

        $tipe = $validated['tipe'];
        $identifier = (int) $validated['nim'];
        $isAdmin = $tipe === 'Admin';

        if ($this->identifierConflict($tipe, $identifier)) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => [
                    'nim' => [$isAdmin ? 'NIP sudah terdaftar.' : 'NIM sudah terdaftar.'],
                ],
            ], 422);
        }

        $plainPassword = trim((string) ($validated['password'] ?? ''));
        $kataSandi = $plainPassword !== '' ? $plainPassword : (string) $identifier;

        $user = User::query()->create([
            'nama_pengguna' => $validated['nama'],                      // <- dari input user
            'email' => $validated['email'],                              // <- dari input user
            'role_user' => $isAdmin ? User::ROLE_ADMIN : User::ROLE_MAHASISWA,
            'nim' => $isAdmin ? 0 : $identifier,                         // <- NIM mahasiswa
            'nip' => $isAdmin ? $identifier : 0,                           // <- NIP admin
            'kata_sandi' => $kataSandi,
        ]);

        return response()->json([
            'message' => 'Anggota berhasil ditambahkan',
            'data' => $this->formatAnggotaRow($user),
        ], 201);
    }

    public function update(Request $request, int|string $id)
    {
        $userId = (int) $id;
        $validated = $request->validate($this->rules($userId), $this->messages());

        $user = User::query()->where('id_user', $userId)->first();
        if (! $user) {
            return response()->json(['message' => 'Anggota tidak ditemukan'], 404);
        }

        $tipe = $validated['tipe'];
        $identifier = (int) $validated['nim'];
        $isAdmin = $tipe === 'Admin';

        if ($this->identifierConflict($tipe, $identifier, $userId)) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => [
                    'nim' => [$isAdmin ? 'NIP sudah terdaftar.' : 'NIM sudah terdaftar.'],
                ],
            ], 422);
        }

        $updateData = [
            'nama_pengguna' => $validated['nama'],
            'email' => $validated['email'],
            'role_user' => $isAdmin ? User::ROLE_ADMIN : User::ROLE_MAHASISWA,
            'nim' => $isAdmin ? 0 : $identifier,
            'nip' => $isAdmin ? $identifier : 0,
        ];

        $plainPassword = trim((string) ($validated['password'] ?? ''));
        if ($plainPassword !== '') {
            $updateData['kata_sandi'] = $plainPassword;
        }

        $user->update($updateData);

        return response()->json([
            'message' => 'Anggota berhasil diperbarui',
            'data' => $this->formatAnggotaRow($user->fresh()),
        ]);
    }

    public function destroy(int|string $id)
    {
        $user = User::query()->where('id_user', (int) $id)->first();
        if (! $user) {
            return response()->json(['message' => 'Anggota tidak ditemukan'], 404);
        }

        $user->delete();

        return response()->json(['message' => 'Anggota berhasil dihapus']);
    }

    private function messages(): array
    {
        return [
            'nama.required' => 'Nama lengkap wajib diisi.',
            'nim.required' => 'NIM/NIP wajib diisi.',
            'nim.digits_between' => 'NIM/NIP harus 6–20 digit angka.',
            'tipe.required' => 'Tipe anggota wajib dipilih.',
            'tipe.in' => 'Tipe anggota tidak valid.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
        ];
    }
}
