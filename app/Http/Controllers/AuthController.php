<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::check()) {
            return $this->redirectByRole();
        }

        return view('auth.login');
    }

    public function showRegister(): View|RedirectResponse
    {
        if (Auth::check()) {
            return $this->redirectByRole();
        }

        return view('auth.register');
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        if (! Auth::attempt($request->credentials(), $request->boolean('remember'))) {
            return back()
                ->withErrors(['email' => 'Email atau password tidak valid.'])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        return $this->redirectByRole();
    }

    public function register(RegisterRequest $request): RedirectResponse
    {
        $user = User::query()->create(array_merge(
            $request->safe()->only(['name', 'email', 'phone', 'address', 'password']),
            ['role' => 'customer'],
        ));

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('products')
            ->with('success', 'Akun berhasil dibuat. Silakan mulai belanja.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    private function redirectByRole(): RedirectResponse
    {
        $user = Auth::user();

        return $user && $user->isAdmin()
            ? redirect()->route('admin.dashboard')
            : redirect()->route('products');
    }
}
