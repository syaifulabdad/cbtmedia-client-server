<?php

namespace App\Http\Controllers\CbtServer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\TarikData;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Schema;

class CbtMediaConf extends Controller
{
    public $serverAuthType = 'Bearer';
    public $serverUrl;
    public $serverToken;

    public function __construct()
    {
        $this->cUrl = url()->current();
    }

    public function getAuth(string $id = null)
    {
        $id = session('sekolah_id') ?? $id;
        $getToken = TarikData::where('nama', 'cbt-server')->first();
        $this->serverUrl = (strpos($getToken->host, 'http') === false ? 'http://' : null) . "$getToken->host";
        $this->serverToken = Crypt::decryptString($getToken->token);
    }

    public function cekKoneksi()
    {
        $this->getAuth();
        $response = Http::withToken("$this->serverToken", "$this->serverAuthType")->get("$this->serverUrl/api/tarik-data/sekolah");
        return $response;
    }

    public function getSekolah()
    {
        $this->getAuth();
        $response = Http::withToken("$this->serverToken", "$this->serverAuthType")->get("$this->serverUrl/api/tarik-data/sekolah");
        return $response;
    }
}
