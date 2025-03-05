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

        $validate['username'] = 'required';
        $validate['password'] = 'required';
        $validator = Validator::make($request->all(), $validate);
        if ($validator->fails()) {
            return back()->with(['username' => $request->username])->withErrors([
                'error' => "Username dan Password wajib diisi."
            ]);
        }

        $username = $request->input('username');
        $getUserEmail = User::where('email', $username)->first();
        $getUserUsername = User::where('username', $username)->first();
        if ($getUserEmail) {
            $getUser = $getUserEmail;
            $kredensial = ['email' => $getUserEmail->email, 'password' => $request->password];
        } elseif ($getUserUsername) {
            $getUser = $getUserUsername;
            $kredensial = ['username' => $getUserUsername->username, 'password' => $request->password];
        } else {
            return back()->with(['username' => $request->username])->withErrors([
                'error' => "User tidak ditemukan.!!"
            ]);
        }

        // $kredensial = $request->only('email', 'password');
        if (Auth::attempt($kredensial)) {
            Auth::loginUsingId($getUser->id);
            $request->session()->regenerate();
            $user = Auth::user();
            if (in_array($user->status, ['active', 'aktif'])) {
                $loginUuid = Str::uuid()->toString();

                $sessData['user_id'] = $user->id;
                $sessData['name'] = $user->name;
                $sessData['username'] = $user->username;
                $sessData['email'] = $user->email;
                $sessData['user_image'] = $user->avatar;
                $sessData['type'] = $user->type;
                $sessData['status'] = $user->status;
                $sessData['sekolah_id'] = $user->sekolah_id ?? null;
                $sessData['ptk_id'] = $user->ptk_id ?? null;
                $sessData['pengawas_id'] = $user->pengawas_id ?? null;
                $sessData['peserta_id'] = $user->peserta_id ?? null;
                $sessData['login_uuid'] = $loginUuid;

                $sekolah = Sekolah::find($user->sekolah_id);
                $sessData['timezone'] = $sekolah ? $sekolah->timezone : 'Asia/Jakarta';
                $sessData['nama_sekolah'] = $sekolah ? $sekolah->nama : null;

                if (in_array($user->type, ['siswa'])) {
                    $sessData['siswa'] = $user->type == 'siswa' ? true : false;
                } else {
                    $sessData['admin'] = $user->type == 'admin' ? true : false;
                    $sessData['ops'] = $user->type == 'ops' ? true : false;
                    $sessData['ptk'] = $user->type == 'ptk' ? true : false;
                    $sessData['proktor'] = $user->type == 'proktor' ? true : false;
                    $sessData['pengawas'] = $user->type == 'pengawas' ? true : false;
                }

                session($sessData);
                User::where('id', $user->id)->update([
                    'last_login' => date('Y-m-d H:i:s'),
                    'status_login' => 1,
                    'login_uuid' => $loginUuid,
                    'ip_address' => request()->ip(),
                ]);

                if ($user->type == 'siswa') {
                    return redirect()->intended('/home');
                    // dd(session());
                } else {
                    return redirect()->intended('/dashboard');
                }
            } else {
                return back()->withErrors([
                    'error' => "User tidak aktif.!"
                ]);
            }
        }

        // return back()->withErrors([
        //     'error' => "Maaf username atau password anda salah.!"
        // ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
