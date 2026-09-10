<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * Login LOKAL dashboard (session-based, guard default web).
 * Bedanya dengan auth API (Sanctum, tugas PKL #5): ini pakai session +
 * cookie, bukan token — khusus halaman /dashboard.
 *
 * User yang boleh login = yang kolom `access`-nya 'admin'.
 */
class LoginController extends Controller
{
    public function show(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Email atau password salah.']);
        }

        $user = Auth::user();

        // Pembeda simple: kolom access. Selain 'admin' → tolak & logout.
        if ($user->access !== 'admin') {
            Auth::logout();
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Akun ini bukan Admin — tidak bisa masuk dashboard.']);
        }

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard.home'));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
