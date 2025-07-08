<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\User; // Pastikan model User sudah diimpor
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserManagementController extends Controller
{
    /**
     * Menampilkan halaman manajemen pengguna dengan fungsionalitas pencarian dan filter.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Memulai query builder dasar untuk model User
        $query = User::query();

        // Logika untuk menangani PENCARIAN
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('email', 'like', '%' . $searchTerm . '%');
            });
        }

        // Logika untuk menangani FILTER ROLE
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Eksekusi query, urutkan dari yang terbaru, dan paginasi hasilnya
        $users = $query->latest()->paginate(10);

        // Kirim data users ke view
        return view('admin.users.index', compact('users'));
    }

    /**
     * Menyimpan pengguna baru yang dibuat melalui modal ke database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // PERBAIKAN: Validasi ditambahkan untuk lokasi dan nomor telepon
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'lokasi' => ['nullable', 'string', 'max:255'],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'role' => ['required', 'string', 'in:admin,petani,pembeli'],
        ]);

        // PERBAIKAN: Data baru ditambahkan saat membuat user
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Password di-hash demi keamanan
            'lokasi' => $request->lokasi,
            'phone_number' => $request->phone_number,
            'role' => $request->role,
        ]);

        // Kembali ke halaman index dengan pesan sukses
        return redirect()->route('admin.users.index')
            ->with('success', 'Pengguna baru berhasil ditambahkan!');
    }

    /**
     * Menampilkan form untuk mengedit pengguna.
     * Menggunakan Route Model Binding untuk efisiensi.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\View\View
     */
    public function edit(User $user)
    {
        $roles = ['admin', 'petani', 'pembeli'];
        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Memproses pembaruan data pengguna.
     * Menggunakan Route Model Binding.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, User $user)
    {
        // PERBAIKAN: Validasi ditambahkan untuk lokasi dan nomor telepon
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'lokasi' => ['nullable', 'string', 'max:255'],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'role' => ['required', 'in:admin,petani,pembeli'],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);

        // Update data
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;
        $user->lokasi = $request->lokasi;
        $user->phone_number = $request->phone_number;

        // Jika password diisi, maka update passwordnya
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'Data pengguna berhasil diperbarui!');
    }

    /**
     * Menghapus pengguna dari database.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $user)
    {
        // Cek untuk mencegah admin menghapus akunnya sendiri
        if (auth()->id() === $user->id) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri!');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
                         ->with('success', 'Pengguna berhasil dihapus.');
    }
}
