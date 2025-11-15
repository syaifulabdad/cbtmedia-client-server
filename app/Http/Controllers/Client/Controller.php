<?php

namespace App\Http\Controllers\Client;

use App\Models\Ujian;
use App\Models\Peserta;
use App\Models\Sekolah;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends \App\Http\Controllers\Controller
{
    use AuthorizesRequests, ValidatesRequests;

    public $sekolah;
    public $peserta_id;
    public $dataUjian;
    public $dataPeserta;
    public function __construct()
    {
        parent::__construct();
        $this->middleware(function ($request, $next) {
            $this->sekolah = Sekolah::first();
            $this->peserta_id = session('peserta_id');
            $this->dataUjian = Ujian::first();
            $this->dataPeserta = Peserta::find(session('peserta_id'));

            View::share('ujian', Ujian::first());
            View::share('peserta', $this->dataPeserta);
            View::share('sekolah', $this->sekolah);
            return $next($request);
        });

    }
}
