<?php

namespace App\Http\Controllers\CbtServer;

use App\Http\Controllers\Controller;
use App\Models\TarikData;
use App\Models\TarikData as Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class SettingTokenController extends Controller
{
    // public $serverUrl = 'https://cbt.aplikasimedia.com/public';
    public function __construct()
    {
        $this->model = new Model;
        $this->primaryKey = (new Model)->getKeyName();
        $this->title = 'Pengaturan Token CBT Server';
        $this->cUrl = url()->current();
    }

    private function formData()
    {
        $formData['host'] = ['validation' => 'required'];
        $formData['token'] = ['validation' => 'required'];
        return $formData;
    }

    public function postStore(Request $request)
    {
        // variable validasi
        foreach ($this->formData() as $key => $value) {
            if (isset($value['validation']) && $value['validation']) {
                $validate[$key] = $value['validation'];
            }
        }

        if (isset($validate)) {
            $validator = Validator::make($request->all(), $validate);
            if ($validator->fails()) {
                return response()->json([
                    'inputerror' => $validator->errors()->keys(),
                    'error_string' => $validator->errors()->all()
                ]);
            }
        }
        // variable data
        foreach ($this->formData() as $key => $value) {
            $data[$key] = $request->{$key};
        }
        if ($request->token)
            $data['token'] = Crypt::encryptString($request->token);

        $cekData = TarikData::where('nama', 'cbt-server')->first();
        if ($cekData) {
            TarikData::where('id', $cekData->id)->update($data);
        } else {
            $data['nama'] = 'cbt-server';
            TarikData::create($data);
        }
        return response()->json(['status' => TRUE]);
    }

    public function postToken(Request $request)
    {
        $apiData = Http::post(env('CBTMEDIA_URL') . "/api/login", [
            'email' => $request->email,
            'password' => $request->password,
            'ip_address' => request()->ip(),
        ]);

        if ($apiData['success']) {
            $cekData = TarikData::where('nama', 'cbt-server')->first();

            $data['nama'] = 'cbt-server';
            // $data['host'] = env('CBTMEDIA_URL');
            $data['token'] = ($apiData['data']['token']);
            if ($cekData) {
                $cekData->update($data);
            } else {
                TarikData::create($data);
            }

            Auth::user()->update(['sekolah_id' => $apiData['sekolah_id']]);
            session(['sekolah_id' => $apiData['sekolah_id']]);

            return response()->json(['status' => TRUE, 'message' => 'Data berhasil disimpan.!']);
        }
    }
}
