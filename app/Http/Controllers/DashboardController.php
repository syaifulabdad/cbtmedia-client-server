<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->title = 'Dashboard';
        $this->cUrl = url()->current();
    }

    public function index(Request $request)
    {
        session(['syaiful2' => 'abdad']);
        return view('dashboard')->with([
            'title' => $this->title,
            'cUrl' => $this->cUrl,
        ]);
    }
}
