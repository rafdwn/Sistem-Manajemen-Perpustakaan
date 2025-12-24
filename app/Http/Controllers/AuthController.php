<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function forgotPassword()
    {
        return view('auth.forgot');
    }

    public function sendPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->with('error', 'Email tidak ditemukan');
        }

        // Data email — KIRIM password asli (disimpan di kolom password_plain)
        $details = [
            'username' => $user->username,
            'password' => $user->password_plain,
        ];

        // Kirim email
        Mail::send('emails.new-password', $details, function($message) use ($user) {
            $message->to($user->email);
            $message->subject('Informasi Akun Anda');
        });

        return redirect('/password/verify')->with('success', 'Silakan cek email Anda.');
    }

    public function verifyPassword()
    {
        return view('auth.verify'); // halaman seperti foto 2
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        if (auth()->attempt([
            'username' => $request->username,
            'password' => $request->password
        ])) {
            $request->session()->regenerate();
            return redirect()->route('dashboard');
        }

        return back()->with('error', 'Username atau password salah');
    }

    public function logout(Request $request)
    {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Anda berhasil logout');
    }

    public function processVerify(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        // Cek user berdasarkan username
        $user = User::where('username', $request->username)->first();

        if (!$user) {
            return back()->with('error', 'Akun tidak ditemukan');
        }

        // Cocokkan password_plain (password asli)
        if ($request->password !== $user->password_plain) {
            return back()->with('error', 'Password salah');
        }

        // Login otomatis
        Auth::login($user);

        return redirect('/dashboard');
    }
}
