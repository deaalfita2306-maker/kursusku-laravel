<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // 1. Tampilkan Halaman Login
    public function showLogin()
    {
        return view('auth.login');
    }

    // 2. Proses Login
    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ], [
            'username.required' => 'Username wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        // Cari user di database berdasarkan username dan status Aktif
        $user = DB::table('users')
            ->where('username', $request->username)
            ->where('status', 'Aktif')
            ->first();

        // Cek apakah user ditemukan dan password cocok
        if ($user && Hash::check($request->password, $user->password)) {
            
            // Simpan data user ke Session
            session([
                'user_id' => $user->id,
                'username' => $user->username,
                'nama_lengkap' => $user->nama_lengkap,
                'email' => $user->email,
                'role' => $user->role,
            ]);

            // Update waktu login terakhir
            DB::table('users')->where('id', $user->id)->update([
                'last_login' => now(),
            ]);

            // Redirect ke halaman profil
            return redirect()->route('profile')->with('success', 'Login berhasil! Selamat datang, ' . $user->nama_lengkap);
        }

        // Jika gagal, kembali ke login dengan pesan error
        return back()->with('error', 'Username atau password salah, atau akun tidak aktif!')->withInput();
    }

    // 3. Tampilkan Halaman Profil
    public function showProfile()
    {
        // Cek apakah user sudah login
        if (!session('user_id')) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Ambil data user terbaru dari database
        $user = DB::table('users')->where('id', session('user_id'))->first();
        
        return view('auth.profile', compact('user'));
    }

    // 4. Update Profil
    public function updateProfile(Request $request)
    {
        if (!session('user_id')) {
            return redirect()->route('login');
        }

        $request->validate([
            'nama_lengkap' => 'required|string|max:100',
            'email' => 'required|email|max:100',
            'nomor_hp' => 'nullable|string|max:20',
            'jabatan' => 'nullable|string|max:100',
        ]);

        // Update database
        DB::table('users')->where('id', session('user_id'))->update([
            'nama_lengkap' => $request->nama_lengkap,
            'email' => $request->email,
            'nomor_hp' => $request->nomor_hp,
            'jabatan' => $request->jabatan,
        ]);

        // Update session agar nama yang tampil di navbar ikut berubah
        session([
            'nama_lengkap' => $request->nama_lengkap,
            'email' => $request->email,
        ]);

        return back()->with('success', 'Profil berhasil diperbarui!');
    }

    // 5. Ganti Password
    public function changePassword(Request $request)
    {
        if (!session('user_id')) {
            return redirect()->route('login');
        }

        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed', // 'confirmed' otomatis cek field 'new_password_confirmation'
        ], [
            'new_password.confirmed' => 'Konfirmasi password baru tidak cocok.',
            'new_password.min' => 'Password baru minimal 8 karakter.',
        ]);

        $user = DB::table('users')->where('id', session('user_id'))->first();

        // Cek password lama
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Password saat ini salah!');
        }

        // Update password baru
        DB::table('users')->where('id', session('user_id'))->update([
            'password' => Hash::make($request->new_password),
        ]);

        return back()->with('success', 'Password berhasil diubah!');
    }

    // 6. Logout
    public function logout()
    {
        session()->flush(); // Hapus semua session
        return redirect()->route('login')->with('success', 'Anda berhasil logout.');
    }

    // 7. Tampilkan Halaman Lupa Password
    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    // 8. Proses Lupa Password (Simulasi)
    public function processForgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = DB::table('users')->where('email', $request->email)->first();

        if ($user) {
            // Di dunia nyata, di sini kita kirim email. 
            // Untuk UAS, kita tampilkan token saja sebagai bukti fitur berjalan.
            $token = bin2hex(random_bytes(16));
            
            DB::table('users')->where('id', $user->id)->update([
                'remember_token' => $token,
            ]);

            return back()->with('success', 'Link reset password telah dikirim ke email Anda. (Token simulasi: ' . $token . ')');
        }

        return back()->with('error', 'Email tidak terdaftar dalam sistem kami.');
    }
}