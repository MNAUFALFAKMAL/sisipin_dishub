<?php

namespace App\Http\Controllers;

use App\Models\Bidang;
use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('bidang')->orderBy('name');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('bidang_id')) {
            $query->where('bidang_id', $request->bidang_id);
        }

        $users   = $query->paginate(15)->withQueryString();
        $bidangs = Bidang::orderBy('nama_bidang')->get();

        return view('users.index', compact('users', 'bidangs'));
    }

    public function create()
    {
        $bidangs = Bidang::orderBy('nama_bidang')->get();
        return view('users.create', compact('bidangs'));
    }

    public function store(StoreUserRequest $request)
    {
        User::create([
            'name'      => $request->name,
            'email'     => $request->username,
            'password'  => Hash::make($request->password),
            'role'      => $request->role,
            'bidang_id' => $request->bidang_id,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('users.index')
            ->with('success', "Pengguna {$request->name} berhasil ditambahkan.");
    }



    public function edit(User $user)
    {
        $bidangs = Bidang::orderBy('nama_bidang')->get();
        return view('users.edit', compact('user', 'bidangs'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $data = [
            'name'      => $request->name,
            'email'     => $request->username,
            'role'      => $request->role,
            'bidang_id' => $request->bidang_id,
            'is_active' => $request->boolean('is_active'),
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index')
            ->with('success', "Data pengguna {$user->name} berhasil diperbarui.");
    }

    public function destroy(User $user)
    {
        // Tidak boleh hapus diri sendiri
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak dapat menghapus akun sendiri.');
        }

        // Tidak boleh menonaktifkan super admin lain (hindari lockout total)
        if ($user->role === 'super_admin') {
            return back()->with('error', 'Tidak dapat menonaktifkan akun Super Admin lain.');
        }

        $user->update(['is_active' => false]);

        return redirect()->route('users.index')
            ->with('success', "Akun {$user->name} telah dinonaktifkan.");
    }

    /**
     * Aktifkan kembali akun yang dinonaktifkan.
     */
    public function activate(User $user)
    {
        $user->update(['is_active' => true]);
        return back()->with('success', "Akun {$user->name} telah diaktifkan kembali.");
    }
}
