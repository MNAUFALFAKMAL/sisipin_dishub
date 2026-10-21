<?php

namespace App\Http\Controllers;

use App\Models\Bidang;
use App\Models\Peran;
use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Access\AuthorizationException;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', User::class);

        $query = User::with(['bidang', 'peran'])->orderBy('name');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%");
            });
        }

        if ($request->filled('peran_id')) {
            $query->where('peran_id', $request->peran_id);
        }

        if ($request->filled('bidang_id')) {
            $query->where('bidang_id', $request->bidang_id);
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        $users   = $query->paginate(15)->withQueryString();
        $perans  = Peran::where('is_active', true)->orderBy('nama')->get();
        $bidangs = Bidang::orderBy('nama_bidang')->get();

        return view('users.index', compact('users', 'perans', 'bidangs'));
    }

    public function create()
    {
        $this->authorize('create', User::class);

        $perans  = Peran::where('is_active', true)->orderBy('nama')->get();
        $bidangs = Bidang::orderBy('nama_bidang')->get();
        return view('users.create', compact('perans', 'bidangs'));
    }

    public function store(StoreUserRequest $request)
    {
        $this->authorize('create', User::class);

        User::create([
            'name'      => $request->name,
            'email'     => $request->username,
            'password'  => Hash::make($request->password),
            'peran_id'  => $request->peran_id,
            'bidang_id' => $request->bidang_id,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('users.index')
            ->with('success', "Pengguna {$request->name} berhasil ditambahkan.");
    }

    public function edit(User $user)
    {
        $this->authorize('update', $user);

        $perans  = Peran::where('is_active', true)->orderBy('nama')->get();
        $bidangs = Bidang::orderBy('nama_bidang')->get();
        return view('users.edit', compact('user', 'perans', 'bidangs'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $this->authorize('update', $user);

        $data = [
            'name'      => $request->name,
            'email'     => $request->username,
            'bidang_id' => $request->bidang_id,
            'is_active' => $request->boolean('is_active'),
        ];

        // Only super_admin can change role
        if (auth()->user()->isSuperAdmin()) {
            $data['peran_id'] = $request->peran_id;
        }

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index')
            ->with('success', "Data pengguna {$user->name} berhasil diperbarui.");
    }

    public function destroy(User $user)
    {
        $this->authorize('deactivate', $user);

        // Additional safety check
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak dapat menghapus akun sendiri.');
        }

        // Tidak boleh menonaktifkan super admin lain (hindari lockout total)
        if ($user->peran?->slug === 'super_admin') {
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
        $this->authorize('activate', $user);

        $user->update(['is_active' => true]);
        return back()->with('success', "Akun {$user->name} telah diaktifkan kembali.");
    }
}