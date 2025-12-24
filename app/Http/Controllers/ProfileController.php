<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        return view('profile.index', [
            'user' => Auth::user()
        ]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        // Validasi dasar
        $request->validate([
            'name'      => 'required',
            'username'  => 'required',
        ]);

        // Update nama & username
        $user->name = $request->name;
        $user->username = $request->username;

        // UPDATE PASSWORD BARU JIKA DIISI
        if ($request->password_new != null) {

            $user->password = Hash::make($request->password_new);
            $user->password_plain = $request->password_new;
        }

        // UPDATE EMAIL BARU JIKA DIISI
        if ($request->email_new != null) {

            $user->email = $request->email_new;
        }

        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui!');
    }

    public function updatePhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user = auth()->user();

        // hapus foto lama
        if ($user->photo && file_exists(public_path('uploads/photos/' . $user->photo))) {
            unlink(public_path('uploads/photos/' . $user->photo));
        }

        // simpan foto baru
        $file = $request->file('photo');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('uploads/photos'), $filename);

        $user->photo = $filename;
        $user->save();

        return back()->with('success', 'Foto profil berhasil diperbarui!');
    }
}
