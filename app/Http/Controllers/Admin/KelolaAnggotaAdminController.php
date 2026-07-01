<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class KelolaAnggotaAdminController extends Controller
{
    // Menampilkan halaman utama kelola anggota beserta list anggota & statistik
    public function index(Request $request)
    {
        // 1. Ambil data statistik anggota
        $totalPengguna = User::count();
        $totalMahasiswa = User::where('role_user', User::ROLE_MAHASISWA)->count();
        $totalAdmin = User::where('role_user', User::ROLE_ADMIN)->count();
        $mahasiswaTerdaftar = User::where('role_user', User::ROLE_MAHASISWA)
            ->where('nim', '>', 0)
            ->count();
        $adminTerdaftar = User::where('role_user', User::ROLE_ADMIN)
            ->where('nip', '>', 0)
            ->count();

        $stats = [
            'total_pengguna'      => $totalPengguna,
            'total_mahasiswa'     => $totalMahasiswa,
            'total_admin'         => $totalAdmin,
            'mahasiswa_terdaftar' => $mahasiswaTerdaftar,
            'admin_terdaftar'     => $adminTerdaftar,
        ];

        // 2. Query list anggota dengan pencarian
        $query = User::query()->orderByDesc('id_user');

        $search = $request->input('q', $request->input('search'));
        if ($search != '') {
            $like = '%' . $search . '%';
            $query->where(function ($w) use ($like) {
                $w->where('nama_pengguna', 'like', $like)
                    ->orWhere('email', 'like', $like)
                    ->orWhere('nim', 'like', $like)
                    ->orWhere('nip', 'like', $like)
                    ->orWhere('role_user', 'like', $like);
            });
        }

        // Ambil data terurut dari yang terbaru dengan pagination
        $users = $query->paginate(8)->withQueryString();

        return view('Admin.kelola_anggota_admin', compact('stats', 'users'));
    }

    // Menyimpan data anggota baru ke database
    public function store(Request $request)
    {
        $tipe = (string) $request->input('tipe');

        // Step 1: Validasi input data secara langsung agar mudah dipahami
        $validated = $request->validate([
            'nama'     => 'required|string|max:255',
            'nim'      => 'required|digits_between:6,20',
            'tipe'     => 'required|in:Mahasiswa,Admin',
            'email'    => 'required|email|max:255|unique:user,email',
            'password' => 'nullable|string|max:255',
        ], $this->messages($tipe));

        $identifier = (int) $validated['nim'];
        $isAdmin = $tipe === 'Admin';

        // Step 2: Cek konflik NIM/NIP apakah sudah digunakan oleh anggota lain
        if ($this->identifierConflict($tipe, $identifier)) {
            return back()->withInput()->withErrors(['nim' => $isAdmin ? 'NIP sudah terdaftar.' : 'NIM sudah terdaftar.']);
        }

        // Tentukan kata sandi default (jika kosong, gunakan NIM/NIP sebagai password)
        $plainPassword = trim((string) ($validated['password'] ?? ''));
        $kataSandi = $plainPassword !== '' ? $plainPassword : (string) $identifier;

        // Step 3: Buat record anggota baru di database
        User::create([
            'nama_pengguna' => $validated['nama'],
            'email'         => $validated['email'],
            'role_user'     => $isAdmin ? User::ROLE_ADMIN : User::ROLE_MAHASISWA,
            'nim'           => $isAdmin ? 0 : $identifier,
            'nip'           => $isAdmin ? $identifier : 0,
            'kata_sandi'    => $kataSandi,
        ]);

        return redirect()->route('admin.anggota.index')->with('success', 'Anggota berhasil ditambahkan.');
    }

    // Memperbarui data anggota lama berdasarkan ID
    public function update(Request $request, $id)
    {
        $userId = (int) $id;
        $tipe = (string) $request->input('tipe');

        // Step 1: Validasi input data (abaikan email unik milik user ini sendiri)
        $validated = $request->validate([
            'nama'     => 'required|string|max:255',
            'nim'      => 'required|digits_between:6,20',
            'tipe'     => 'required|in:Mahasiswa,Admin',
            'email'    => 'required|email|max:255|unique:user,email,' . $userId . ',id_user',
            'password' => 'nullable|string|max:255',
        ], $this->messages($tipe));

        // Step 2: Cari data user yang ingin diupdate
        $user = User::where('id_user', $userId)->firstOrFail();

        $identifier = (int) $validated['nim'];
        $isAdmin = $tipe === 'Admin';

        // Step 3: Cek konflik NIM/NIP dengan user lain
        if ($this->identifierConflict($tipe, $identifier, $userId)) {
            return back()->withInput()->withErrors(['nim' => $isAdmin ? 'NIP sudah terdaftar.' : 'NIM sudah terdaftar.']);
        }

        // Tentukan data yang akan diperbarui
        $updateData = [
            'nama_pengguna' => $validated['nama'],
            'email'         => $validated['email'],
            'role_user'     => $isAdmin ? User::ROLE_ADMIN : User::ROLE_MAHASISWA,
            'nim'           => $isAdmin ? 0 : $identifier,
            'nip'           => $isAdmin ? $identifier : 0,
        ];

        // Ganti password jika ada input password baru yang diisi
        $plainPassword = trim((string) ($validated['password'] ?? ''));
        if ($plainPassword !== '') {
            $updateData['kata_sandi'] = $plainPassword;
        }

        // Step 4: Jalankan query update ke database
        $user->update($updateData);

        return redirect()->route('admin.anggota.index')->with('success', 'Anggota berhasil diperbarui.');
    }

    // Menghapus data anggota berdasarkan ID
    public function destroy($id)
    {
        $user = User::where('id_user', $id)->firstOrFail();

        // Cegah admin menghapus akun sendiri
        if ($user->id_user === auth()->id()) {
            return redirect()->route('admin.anggota.index')->with('error', 'Anda tidak dapat menghapus akun sendiri.');
        }

        // Step 2: Hapus data user dari database
        $user->delete();

        return redirect()->route('admin.anggota.index')->with('success', 'Anggota berhasil dihapus.');
    }

    // Memeriksa apakah terjadi bentrok NIM/NIP dengan anggota lain
    private function identifierConflict($tipe, $identifier, $exceptUserId = null)
    {
        $isAdmin = $tipe === 'Admin';
        $query = User::query();

        if ($isAdmin) {
            $query->where('nip', $identifier)->where('nip', '>', 0);
        } else {
            $query->where('nim', $identifier)->where('nim', '>', 0);
        }

        if ($exceptUserId !== null) {
            $query->where('id_user', '!=', (int) $exceptUserId);
        }

        return $query->exists();
    }

    // Kumpulan pesan validasi bahasa Indonesia
    private function messages($tipe = 'Mahasiswa')
    {
        $label = $tipe === 'Admin' ? 'NIP' : 'NIM';
        return [
            'nama.required'         => 'Nama lengkap wajib diisi.',
            'nim.required'          => $label . ' wajib diisi.',
            'nim.digits_between'    => $label . ' harus 6–20 digit angka.',
            'tipe.required'         => 'Tipe anggota wajib dipilih.',
            'tipe.in'               => 'Tipe anggota tidak valid.',
            'email.required'        => 'Email wajib diisi.',
            'email.email'           => 'Format email tidak valid.',
            'email.unique'          => 'Email sudah terdaftar.',
        ];
    }
}
