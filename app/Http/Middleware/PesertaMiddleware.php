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
        if (session('peserta_id')) {
            $peserta = Peserta::find(session('peserta_id'));
            if ($peserta) {
                if ($peserta->status_login == 0) {
                    $request->session()->invalidate();
                    return redirect('/login')->with('message', 'Login to access the Panel');
                } else {
                    if ($peserta->login_uuid != session('login_uuid')) {
                        $request->session()->invalidate();
                        return redirect('/login')->with('message', 'Login to access the Panel')->withErrors([
                            'error' => "Terdeteksi Login Ganda.!!"
                        ]);
                    }
                }
            }
        } else {
            return redirect('/login')->with('message', 'Login to access the Panel');
        }

        return $next($request);
    }
}
