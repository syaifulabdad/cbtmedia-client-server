<?php

namespace App\Http\Middleware;

use App\Models\Peserta;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PesertaMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        if ($user?->type == 'siswa') {
            $peserta = Peserta::find($user->peserta_id);
            if ($peserta) {
                if ($user->status_login == 0) {
                    $request->session()->invalidate();
                    if ($request->ajax()) {
                        return response()->json(['status' => false, 'message' => "Login to access the Panel"]);
                    }
                    return redirect('/login')->with('message', 'Login to access the Panel');
                } else {
                    if ($user->login_uuid != session('login_uuid')) {
                        $request->session()->invalidate();
                        if ($request->ajax()) {
                            return response()->json(['status' => false, 'message' => "Terdeteksi Login Ganda.!!"]);
                        }
                        return redirect('/login')->with('message', 'Login to access the Panel')->withErrors([
                            'error' => "Terdeteksi Login Ganda.!!"
                        ]);
                    }
                }
            }
        } else {
            if (request()->ajax()) {
                return response()->json(['status' => false, 'message' => "Login to access the Panel"]);
            }
            return redirect('/login')->with('message', 'Login to access the Panel');
        }

        return $next($request);
    }
}
