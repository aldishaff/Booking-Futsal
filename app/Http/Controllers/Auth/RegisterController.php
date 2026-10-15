<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function show()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'username' => 'required|unique:users,username',
                'email' => 'required|email|unique:users,email',
                'no_telepon' => 'required|min:10',
                'password' => 'required|min:8|confirmed',
            ],
            [
                'username.required' => 'Nama tidak boleh kosong',
                'username.unique' => 'Username sudah digunakan',

                'email.required' => 'Email tidak boleh kosong',
                'email.email' => 'Format email tidak valid',
                'email.unique' => 'Email sudah terdaftar',

                'no_telepon.required' => 'Nomor telepon tidak boleh kosong',
                'no_telepon.min' => 'Nomor telepon minimal 10 digit',

                'password.required' => 'Password tidak boleh kosong',
                'password.min' => 'Password minimal 8 karakter',
                'password.confirmed' => 'Password dan konfirmasi password tidak sama',
            ]
        );

        User::create([
            'username' => $request->username,
            'email' => $request->email,
            'no_telepon' => $request->no_telepon,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('login')
            ->with('success', 'Akun berhasil dibuat, silakan login');
    }
}
