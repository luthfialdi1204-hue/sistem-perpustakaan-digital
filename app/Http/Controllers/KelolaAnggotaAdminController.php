<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KelolaAnggotaAdminController extends Controller
{
    public function index()
    {
        return view('Admin.kelola_anggota_admin');
    }

    public function list()
    {
        $rows = User::query()
            ->select(['id_user', 'nama_pengguna', 'nim', 'nip', 'email', 'role_user'])
            ->orderByDesc('id_user')
            ->get()
            ->map(function ($u) {
                $role = $u->role_user ?? $u->role;
                $isAdmin = $role === User::ROLE_ADMIN;
                $identifier = $isAdmin ? (string) ($u->nip ?? '') : (string) ($u->nim ?? '');

                return [
                    'id' => (int) $u->id_user,
                    'nim' => $identifier, // tetap pakai field "nim" untuk UI (nim/nip)
                    'nama' => $u->nama_pengguna ?? $u->name ?? '',
                    'tipe' => $isAdmin ? 'Admin' : 'Mahasiswa',
                    'email' => $u->email ?? '',
                    'status' => 'Aktif',
                ];
            });

        return response()->json(['data' => $rows]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => ['required', 'string', 'max:30'],
            'nim' => ['required', 'digits_between:6,20'],
            'tipe' => ['required', 'in:Mahasiswa,Admin'],
            'email' => ['required', 'email', 'max:50'],
            'password' => ['nullable', 'string', 'max:30'],
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validasi gagal', 'errors' => $validator->errors()], 422);
        }

        $tipe = $request->input('tipe');
        $identifier = $request->input('nim');
        $isAdmin = $tipe === 'Admin';

        // Tabel `user` mewajibkan nim & nip (NOT NULL). Isi 0 untuk yang tidak dipakai.
        $attrs = [
            'nama_pengguna' => $request->input('nama'),
            'email' => $request->input('email'),
            'role_user' => $isAdmin ? User::ROLE_ADMIN : User::ROLE_MAHASISWA,
            'nim' => $isAdmin ? 0 : (int) $identifier,
            'nip' => $isAdmin ? (int) $identifier : 0,
        ];

        $plainPassword = trim((string) $request->input('password', ''));
        $attrs['kata_sandi'] = $plainPassword !== ''
            ? substr($plainPassword, 0, 30)
            : substr((string) $identifier, 0, 30);

        $queryKey = $isAdmin ? ['nip' => (int) $identifier] : ['nim' => (int) $identifier];
        $user = User::query()->updateOrCreate($queryKey, $attrs);

        return response()->json([
            'message' => 'Anggota berhasil disimpan',
            'id' => (int) $user->id_user,
        ]);
    }

    public function update(Request $request, int $id)
    {
        $validator = Validator::make($request->all(), [
            'nama' => ['required', 'string', 'max:30'],
            'nim' => ['required', 'digits_between:6,20'],
            'tipe' => ['required', 'in:Mahasiswa,Admin'],
            'email' => ['required', 'email', 'max:50'],
            'password' => ['nullable', 'string', 'max:30'],
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validasi gagal', 'errors' => $validator->errors()], 422);
        }

        $user = User::query()->where('id_user', $id)->first();
        if (! $user) {
            return response()->json(['message' => 'Anggota tidak ditemukan'], 404);
        }

        $tipe = $request->input('tipe');
        $identifier = $request->input('nim');
        $isAdmin = $tipe === 'Admin';

        $user->nama_pengguna = $request->input('nama');
        $user->email = $request->input('email');
        $user->role_user = $isAdmin ? User::ROLE_ADMIN : User::ROLE_MAHASISWA;
        $user->nim = $isAdmin ? 0 : (int) $identifier;
        $user->nip = $isAdmin ? (int) $identifier : 0;

        $plainPassword = trim((string) $request->input('password', ''));
        if ($plainPassword !== '') {
            $user->kata_sandi = substr($plainPassword, 0, 30);
        }

        $user->save();

        return response()->json(['message' => 'Anggota berhasil diperbarui']);
    }

    public function destroy(int $id)
    {
        $user = User::query()->where('id_user', $id)->first();
        if (! $user) {
            return response()->json(['message' => 'Anggota tidak ditemukan'], 404);
        }

        $user->delete();

        return response()->json(['message' => 'Anggota berhasil dihapus']);
    }
}

