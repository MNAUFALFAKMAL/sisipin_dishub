<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Redirect based on role
        return redirect()->intended($this->getDashboardRoute(Auth::user()));
    }

    /**
     * Get dashboard route based on user role.
     */
    protected function getDashboardRoute($user): string
    {
        $role = $user->peran?->slug ?? $user->role ?? 'staff';

        return match ($role) {
            'super_admin' => '/admin',
            'tata_usaha' => '/tata-usaha',
            'pegawai' => '/pegawai',
            'kepala_dinas' => '/kepala-dinas',
            'kepala_bidang' => '/kepala-bidang',
            default => '/pegawai',
        };
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
