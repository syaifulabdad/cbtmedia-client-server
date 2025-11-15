<?php

namespace App\Http\Controllers;

use App\Models\StatusUjian;
use App\Models\Ujian;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->title = 'Dashboard';
        $this->cUrl = url()->current();
    }

    public function index(Request $request)
    {
        $ujian = Ujian::first();

        $data['title'] = $ujian?->nama;
        $data['cUrl'] = $this->cUrl;
        $data['ujian'] = $ujian;
        $data['statusUjianModel'] = new StatusUjian();

        return view('dashboard')->with($data);
    }
}
