<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends ClientController
{
    public function __construct()
    {
        $this->title = 'Home';
        $this->cUrl = url()->current();
    }

    public function index(Request $request)
    {
        return view('client.home')->with([
            'title' => $this->title,
            'cUrl' => $this->cUrl,
        ]);
    }
}
