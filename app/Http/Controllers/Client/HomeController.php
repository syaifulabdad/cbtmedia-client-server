<?php

namespace App\Http\Controllers\Client;

use Carbon\Carbon;
use App\Models\Soal;
use App\Models\Ujian;
use App\Models\StatusUjian;
use Illuminate\Http\Request;
use App\Models\StatusPesertaUjian;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->title = 'Home';
        $this->cUrl = url()->current();
    }

    public function index(Request $request)
    {
        $ujian = Ujian::first();

        $data['title'] = $ujian?->nama;
        $data['cUrl'] = $this->cUrl;
        $data['ujian'] = $ujian;
        $data['statusUjianModel'] = new StatusUjian();

        return view('client.home')->with($data);
    }

    public function daftarSoal(Request $request)
    {
        $todayDate = date('Y-m-d');
        $ujian = Ujian::first();

        $data['cUrl'] = $this->cUrl;
        $data['ujian'] = $ujian;
        $data['statusUjianModel'] = new StatusUjian();
        $data['statusPesertaUjianModel'] = new StatusPesertaUjian();
        $data['rombel_id_peserta'] = auth()->user()->peserta?->rombongan_belajar_id;

        $where = [];
        $where['status'] = 1;

        $getStatusUjian = StatusUjian::where($where);
        $getStatusUjian->whereDate('waktu_ujian', $todayDate);
        $data['getStatusUjian'] = $getStatusUjian->get();

        return view('client.daftar-soal')->with($data);
    }
}
