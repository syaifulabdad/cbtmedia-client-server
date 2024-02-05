<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Peserta;
use App\Models\Sekolah;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Ujian;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function index()
    {
        if (session('user_id')) {
            if (session('type') == 'siswa') {
                $peserta = Peserta::find(session('peserta_id'));
                if ($peserta) {
                    if ($peserta->status_login == 0) {
                        request()->session()->invalidate();
                        return redirect('/login')->with('message', 'Login to access the Panel');
                    } else {
                        if ($peserta->login_uuid != session('login_uuid')) {
                            request()->session()->invalidate();
                            return redirect('/login')->with('message', 'Login to access the Panel')->withErrors([
                                'error' => "Terdeteksi Login Ganda.!!"
                            ]);
                        }
                    }
                }
                return redirect()->intended('/home');
            } else {
                return redirect()->intended('/dashboard');
            }
        }

        $data['sekolah'] = Sekolah::first();
        return view('auth.login')->with($data);
    }

    public function proses(Request $request)
    {
        Auth::logout();

        $validate['email'] = 'required';
        $validate['password'] = 'required';
        $validator = Validator::make($request->all(), $validate);
        if ($validator->fails()) {
            return back()->with(['email' => $request->email])->withErrors([
                'error' => "Username dan Password wajib diisi."
            ]);
        }

        $username = $request->email;
        $password = $request->password;

        $type = filter_var($username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $request->merge([
            $type => $username,
            'password' => $request->password
        ]);

        $kredensial = $request->only('email', 'password');
        if (Auth::attempt($kredensial)) {
            $request->session()->regenerate();
            $user = Auth::user();
            if (in_array($user->status, ['active', 'aktif'])) {
                $sessData['user_id'] = $user->id;
                $sessData['name'] = $user->name;
                $sessData['username'] = $user->username;
                $sessData['email'] = $user->email;
                $sessData['user_image'] = $user->avatar;
                $sessData['type'] = $user->type;
                $sessData['status'] = $user->status;
                $sessData['sekolah_id'] = $user->sekolah_id ?? null;
                $sessData['ptk_id'] = $user->ptk_id ?? null;

                $sekolah = Sekolah::find($user->sekolah_id);
                $sessData['timezone'] = $sekolah ? $sekolah->timezone : 'Asia/Jakarta';
                $sessData['nama_sekolah'] = $sekolah ? $sekolah->nama : null;

                if (in_array($user->type, ['ops', 'ptk', 'admin'])) {
                    $sessData['admin'] = $user->type == 'admin' ? true : false;
                    $sessData['ops'] = $user->type == 'ops' ? true : false;
                    $sessData['ptk'] = $user->type == 'ptk' ? true : false;
                } elseif (in_array($user->type, ['siswa'])) {
                    $sessData['siswa'] = $user->type == 'siswa' ? true : false;
                }

                session($sessData);

                User::where('id', $user->id)->update(['last_login' => date('Y-m-d H:i:s')]);

                return redirect()->intended('/dashboard');
            } else {
                return back()->withErrors([
                    'error' => "User tidak aktif.!"
                ]);
            }
        }

        $getSiswaNis = Peserta::where('nis', $username)->first();
        $getSiswaNisn = Peserta::where('nisn', $username)->first();
        if ($getSiswaNis || $getSiswaNisn) {
            if ($getSiswaNis) {
                $getSiswa = $getSiswaNis;
            } elseif ($getSiswaNisn) {
                $getSiswa = $getSiswaNisn;
            }

            if ($getSiswa->password === $password) {
                $request->session()->regenerate();
                $user = $getSiswa;
                if (in_array($user->status, ['active', 'aktif', 1])) {
                    $loginUuid = Str::uuid()->toString();
                    $sekolah = Sekolah::find($user->sekolah_id);
                    $ujian = Ujian::where('sekolah_id', $user->sekolah_id)->first();

                    $sessData['user_id'] = $user->id;
                    $sessData['peserta_id'] = $user->id;
                    $sessData['nama'] = $user->nama;
                    $sessData['username'] = $username;
                    $sessData['type'] = 'siswa';
                    $sessData['user_image'] = $user->foto;
                    $sessData['status'] = $user->status;
                    $sessData['sekolah_id'] = $user->sekolah_id ?? null;
                    $sessData['ujian_id'] = $ujian ? $ujian->ujian_id : null;
                    $sessData['timezone'] = $sekolah ? $sekolah->timezone : 'Asia/Jakarta';
                    $sessData['nama_sekolah'] = $sekolah ? $sekolah->nama : null;
                    $sessData['login_uuid'] = $loginUuid;

                    session($sessData);

                    $user->update([
                        'status_login' => 1,
                        'terakhir_login' => date('Y-m-d H:i:s'),
                        'login_uuid' => $loginUuid,
                        'ip_address' => request()->ip(),
                    ]);

                    return redirect()->intended('/home');
                } else {
                    return back()->withErrors([
                        'error' => "User tidak aktif.!"
                    ]);
                }
            }
        }

        return back()->withErrors([
            'error' => "Maaf username atau password anda salah.!"
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
