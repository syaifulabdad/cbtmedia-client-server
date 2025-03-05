<?php

namespace App\Http\Controllers\CbtServer;

use App\Http\Controllers\Controller;
use App\Models\AnggotaRombel;
use App\Models\BankSoal;
use App\Models\Jadwal;
use App\Models\Jurusan;
use App\Models\MataPelajaran;
use App\Models\Pengaturan;
use App\Models\Pengawas;
use App\Models\Peserta;
use App\Models\PesertaDidik;
use App\Models\Ptk;
use App\Models\Ref\Agama;
use App\Models\Ref\JenjangPendidikan;
use App\Models\Ref\TingkatKelas;
use App\Models\RombonganBelajar;
use App\Models\Ruang;
use App\Models\Sekolah;
use App\Models\Semester;
use App\Models\Server;
use App\Models\Soal;
use App\Models\TarikData;
use App\Models\Ujian;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class TarikDataController extends Controller
{
    public $semester_id;

    public function __construct()
    {
        $this->title = 'Tarik Data';
        $this->cUrl = url()->current();
    }

    public function index(Request $request)
    {
        $getData = TarikData::where('nama', 'cbt-server')->first();
        return view('tarik-data')->with([
            'title' => $this->title,
            'cUrl' => $this->cUrl,
            'model' => $this->model,
            'token' => $getData,
        ]);
    }

    function getJumlahData()
    {
        $cbtconf = new CbtMediaConf;
        $cbtconf->getAuth();
        $dataPusat = Http::withToken("$cbtconf->serverToken", "$cbtconf->serverAuthType")->get("$cbtconf->serverUrl/api/tarik-data/jumlah-data");

        $tarikData = TarikData::where('nama', 'cbt-server')->first();
        return response()->json([
            'status' => TRUE,
            'tarik_data_terakhir' => $tarikData ? $tarikData->tarik_data_terakhir : null,
            'pusat' => $dataPusat['data'] ?? null,
            'local' => [
                'sekolah' => Sekolah::count(),
                'jurusan' => Jurusan::count(),
                'mapel' => MataPelajaran::count(),
                'rombel' => RombonganBelajar::count(),
                'ptk' => Ptk::count(),
                'semester' => Semester::count(),
                'ujian' => Ujian::count(),
                'bank_soal' => BankSoal::count(),
                'soal' => Soal::count(),
                'jadwal' => Jadwal::count(),
                'server' => Server::count(),
                'ruang' => Ruang::count(),
                'peserta' => Peserta::count(),
                'pengaturan' => Pengaturan::count(),
                'user' => User::whereIn('type', ['siswa', 'pengawas', 'ops'])
                    ->whereIn('status', ['active', 'aktif'])
                    ->count(),
                'referensi' => (Agama::count() + JenjangPendidikan::count() + TingkatKelas::count()),
            ]
        ]);
    }

    public function dataReferensi(Request $request)
    {
        $cbtconf = new CbtMediaConf;
        $cbtconf->getAuth();
        $apiData = Http::withToken("$cbtconf->serverToken", "$cbtconf->serverAuthType")->get("$cbtconf->serverUrl/api/tarik-data/ref-agama");

        if ($apiData['success']) {
            $columns = Schema::getColumnListing('ref_agama');
            foreach ($apiData['data'] as $dt) {
                $cekData = Agama::find($dt['id']);
                $data = array();
                foreach ($columns as $col) {
                    if ($col != 'id') {
                        $data[$col] = isset($dt[$col]) ? $dt[$col] : null;
                    }
                }

                if ($cekData) {
                    $cekData->update($data);
                } else {
                    $data['id'] = $dt['id'];
                    Agama::create($data);
                }
            }

            TarikData::where('nama', 'cbt-server')->update(['tarik_data_terakhir' => date('Y-m-d H:i:s')]);
            return response()->json(['status' => TRUE, 'data' => 'referensi', 'message' => 'Data berhasil ditarik.!']);
        }
    }

    public function dataRefAgama(Request $request)
    {
        $cbtconf = new CbtMediaConf;
        $cbtconf->getAuth();
        $apiData = Http::withToken("$cbtconf->serverToken", "$cbtconf->serverAuthType")->get("$cbtconf->serverUrl/api/tarik-data/ref-agama");

        if ($apiData['success']) {
            $columns = Schema::getColumnListing('ref_agama');
            foreach ($apiData['data'] as $dt) {
                $cekData = Agama::find($dt['id']);
                $data = array();
                foreach ($columns as $col) {
                    if ($col != 'id') {
                        $data[$col] = isset($dt[$col]) ? $dt[$col] : null;
                    }
                }

                if ($cekData) {
                    $cekData->update($data);
                } else {
                    $data['id'] = $dt['id'];
                    Agama::create($data);
                }
            }

            TarikData::where('nama', 'cbt-server')->update(['tarik_data_terakhir' => date('Y-m-d H:i:s')]);
            return response()->json(['status' => TRUE, 'data' => 'referensi', 'message' => 'Data berhasil ditarik.!']);
        }
    }

    public function dataPengaturan(Request $request)
    {
        $cbtconf = new CbtMediaConf;
        $cbtconf->getAuth();
        $apiData = Http::withToken("$cbtconf->serverToken", "$cbtconf->serverAuthType")->get("$cbtconf->serverUrl/api/tarik-data/pengaturan");

        if ($apiData['success']) {
            $columns = Schema::getColumnListing('pengaturan');
            foreach ($apiData['data'] as $dt) {
                $cekData = Pengaturan::withTrashed()->find($dt['id']);
                $data = array();
                foreach ($columns as $col) {
                    if ($col != 'id') {
                        $data[$col] = isset($dt[$col]) ? $dt[$col] : null;
                    }
                }

                if ($cekData) {
                    $cekData->update($data);
                } else {
                    $data['id'] = $dt['id'];
                    Pengaturan::create($data);
                }
            }

            TarikData::where('nama', 'cbt-server')->update(['tarik_data_terakhir' => date('Y-m-d H:i:s')]);
            return response()->json(['status' => TRUE, 'data' => 'pengaturan', 'message' => 'Data Pengaturan berhasil ditarik.!']);
        }
    }

    public function dataSekolah(Request $request)
    {
        $cbtconf = new CbtMediaConf;
        // $getData = $cbtconf->getSekolah();
        $cbtconf->getAuth();
        $apiData = Http::withToken("$cbtconf->serverToken", "$cbtconf->serverAuthType")->get("$cbtconf->serverUrl/api/tarik-data/sekolah");

        if ($apiData['success']) {
            $columns = Schema::getColumnListing('sekolah');
            $cekData = Sekolah::find($apiData['data']['id']);

            $data = array();
            foreach ($columns as $col) {
                if ($col != 'id') {
                    $data[$col] = isset($apiData['data'][$col]) ? $apiData['data'][$col] : null;
                }
            }

            if ($cekData) {
                $cekData->update($data);
            } else {
                $data['id'] = $apiData['data']['id'];
                Sekolah::create($data);
            }

            TarikData::where('nama', 'cbt-server')->update(['tarik_data_terakhir' => date('Y-m-d H:i:s')]);
            return response()->json(['status' => TRUE, 'data' => 'sekolah', 'message' => 'Data Sekolah berhasil ditarik.!']);
        }
    }

    public function dataUser(Request $request)
    {
        $cbtconf = new CbtMediaConf;
        $cbtconf->getAuth();
        $apiData = Http::withToken("$cbtconf->serverToken", "$cbtconf->serverAuthType")->get("$cbtconf->serverUrl/api/tarik-data/user");

        if ($apiData['success']) {
            $columns = Schema::getColumnListing('users');
            foreach ($apiData['data']['ops'] as $dt) {
                $cekData = User::withTrashed()->find($dt['id']);
                $data = array();
                foreach ($columns as $col) {
                    if ($col != 'id') {
                        $data[$col] = isset($dt[$col]) ? $dt[$col] : null;
                    }
                }

                if ($cekData) {
                    $cekData->update($data);
                } else {
                    $data['id'] = $dt['id'];
                    User::create($data);
                }
            }

            foreach ($apiData['data']['pengawas'] as $dt) {
                $cekData = User::withTrashed()->find($dt['id']);
                $data = array();
                foreach ($columns as $col) {
                    if ($col != 'id') {
                        $data[$col] = isset($dt[$col]) ? $dt[$col] : null;
                    }
                }

                if ($cekData) {
                    $cekData->update($data);
                } else {
                    $data['id'] = $dt['id'];
                    User::create($data);
                }
            }

            foreach ($apiData['data']['siswa'] as $dt) {
                $cekData = User::withTrashed()->find($dt['id']);
                $data = array();
                foreach ($columns as $col) {
                    if ($col != 'id') {
                        $data[$col] = isset($dt[$col]) ? $dt[$col] : null;
                    }
                }

                if ($cekData) {
                    $cekData->update($data);
                } else {
                    $data['id'] = $dt['id'];
                    User::create($data);
                }
            }

            TarikData::where('nama', 'cbt-server')->update(['tarik_data_terakhir' => date('Y-m-d H:i:s')]);
            return response()->json(['status' => TRUE, 'data' => 'user', 'message' => 'Data User berhasil ditarik.!']);
        }
    }

    public function dataMataPelajaran(Request $request)
    {
        $cbtconf = new CbtMediaConf;
        $cbtconf->getAuth();
        $apiData = Http::withToken("$cbtconf->serverToken", "$cbtconf->serverAuthType")->get("$cbtconf->serverUrl/api/tarik-data/mata-pelajaran");

        if ($apiData['success']) {
            $columns = Schema::getColumnListing('mata_pelajaran');
            foreach ($apiData['data'] as $dt) {
                $cekData = MataPelajaran::withTrashed()->find($dt['id']);
                $data = array();
                foreach ($columns as $col) {
                    if ($col != 'id') {
                        $data[$col] = isset($dt[$col]) ? $dt[$col] : null;
                    }
                }

                if ($cekData) {
                    $cekData->update($data);
                } else {
                    $data['id'] = $dt['id'];
                    MataPelajaran::create($data);
                }
            }

            TarikData::where('nama', 'cbt-server')->update(['tarik_data_terakhir' => date('Y-m-d H:i:s')]);
            return response()->json(['status' => TRUE, 'data' => 'mata-pelajaran', 'message' => 'Data Mata Pelajaran berhasil ditarik.!']);
        }
    }

    public function dataSemester(Request $request)
    {
        $cbtconf = new CbtMediaConf;
        $cbtconf->getAuth();
        $apiData = Http::withToken("$cbtconf->serverToken", "$cbtconf->serverAuthType")->get("$cbtconf->serverUrl/api/tarik-data/semester");

        if ($apiData['success']) {
            $columns = Schema::getColumnListing('semester');
            foreach ($apiData['data'] as $dt) {
                $cekData = Semester::find($dt['id']);
                $data = array();
                foreach ($columns as $col) {
                    if ($col != 'id') {
                        $data[$col] = isset($dt[$col]) ? $dt[$col] : null;
                    }
                }

                if ($cekData) {
                    $cekData->update($data);
                } else {
                    $data['id'] = $dt['id'];
                    Semester::create($data);
                }
            }

            TarikData::where('nama', 'cbt-server')->update(['tarik_data_terakhir' => date('Y-m-d H:i:s')]);
            return response()->json(['status' => TRUE, 'data' => 'semester', 'message' => 'Data Semester berhasil ditarik.!']);
        }
    }

    public function dataJurusan(Request $request)
    {
        $cbtconf = new CbtMediaConf;
        $cbtconf->getAuth();
        $apiData = Http::withToken("$cbtconf->serverToken", "$cbtconf->serverAuthType")->get("$cbtconf->serverUrl/api/tarik-data/jurusan");

        if ($apiData['success']) {
            $columns = Schema::getColumnListing('jurusan');
            foreach ($apiData['data'] as $dt) {
                $cekData = Jurusan::withTrashed()->find($dt['id']);
                $data = array();
                foreach ($columns as $col) {
                    if ($col != 'id') {
                        $data[$col] = isset($dt[$col]) ? $dt[$col] : null;
                    }
                }

                if ($cekData) {
                    $cekData->update($data);
                } else {
                    $data['id'] = $dt['id'];
                    Jurusan::create($data);
                }
            }

            TarikData::where('nama', 'cbt-server')->update(['tarik_data_terakhir' => date('Y-m-d H:i:s')]);
            return response()->json(['status' => TRUE, 'data' => 'jurusan', 'message' => 'Data Jurusan berhasil ditarik.!']);
        }
    }

    public function dataRombonganBelajar(Request $request)
    {
        $cbtconf = new CbtMediaConf;
        $cbtconf->getAuth();
        $apiData = Http::withToken("$cbtconf->serverToken", "$cbtconf->serverAuthType")->get("$cbtconf->serverUrl/api/tarik-data/rombongan-belajar");

        if ($apiData['success']) {
            $columns = Schema::getColumnListing('rombongan_belajar');
            foreach ($apiData['data'] as $dt) {
                $cekData = RombonganBelajar::withTrashed()->find($dt['id']);
                $data = array();
                foreach ($columns as $col) {
                    if ($col != 'id') {
                        $data[$col] = isset($dt[$col]) ? $dt[$col] : null;
                    }
                }

                if ($cekData) {
                    $cekData->update($data);
                } else {
                    $data['id'] = $dt['id'];
                    RombonganBelajar::create($data);
                }
            }

            TarikData::where('nama', 'cbt-server')->update(['tarik_data_terakhir' => date('Y-m-d H:i:s')]);
            return response()->json(['status' => TRUE, 'data' => 'rombongan-belajar', 'message' => 'Data Rombel berhasil ditarik.!']);
        }
    }

    public function dataPtk(Request $request)
    {
        $cbtconf = new CbtMediaConf;
        $cbtconf->getAuth();
        $apiData = Http::withToken("$cbtconf->serverToken", "$cbtconf->serverAuthType")->get("$cbtconf->serverUrl/api/tarik-data/ptk");

        if ($apiData['success']) {
            $columns = Schema::getColumnListing('ptk');
            foreach ($apiData['data'] as $dt) {
                $cekData = Ptk::withTrashed()->find($dt['id']);
                $data = array();
                foreach ($columns as $col) {
                    if ($col != 'id') {
                        $data[$col] = isset($dt[$col]) ? $dt[$col] : null;
                    }
                }

                if ($cekData) {
                    $cekData->update($data);
                } else {
                    $data['id'] = $dt['id'];
                    Ptk::create($data);
                }
            }

            TarikData::where('nama', 'cbt-server')->update(['tarik_data_terakhir' => date('Y-m-d H:i:s')]);
            return response()->json(['status' => TRUE, 'data' => 'ptk', 'message' => 'Data PTK berhasil ditarik.!']);
        }
    }

    public function dataUjian(Request $request)
    {
        $cbtconf = new CbtMediaConf;
        $cbtconf->getAuth();
        $apiData = Http::withToken("$cbtconf->serverToken", "$cbtconf->serverAuthType")->get("$cbtconf->serverUrl/api/tarik-data/ujian");

        if ($apiData['success']) {
            $columns = Schema::getColumnListing('ujian');
            foreach ($apiData['data'] as $dt) {
                $cekData = Ujian::withTrashed()->find($dt['id']);
                $data = array();
                foreach ($columns as $col) {
                    if ($col != 'id') {
                        $data[$col] = isset($dt[$col]) ? $dt[$col] : null;
                    }
                }

                if ($cekData) {
                    $cekData->update($data);
                } else {
                    $data['id'] = $dt['id'];
                    Ujian::create($data);
                }
            }

            TarikData::where('nama', 'cbt-server')->update(['tarik_data_terakhir' => date('Y-m-d H:i:s')]);
            return response()->json(['status' => TRUE, 'data' => 'ujian', 'message' => 'Data Ujian berhasil ditarik.!']);
        }
    }

    public function dataSoal(Request $request)
    {
        $cbtconf = new CbtMediaConf;
        $cbtconf->getAuth();
        $apiData = Http::withToken("$cbtconf->serverToken", "$cbtconf->serverAuthType")->get("$cbtconf->serverUrl/api/tarik-data/soal");

        if ($apiData['success']) {
            $columnBankSoal = Schema::getColumnListing('bank_soal');
            foreach ($apiData['data'] as $dt) {
                $cekData = BankSoal::withTrashed()->find($dt['id']);
                $data = array();
                foreach ($columnBankSoal as $col) {
                    if ($col != 'id') {
                        $data[$col] = isset($dt[$col]) ? $dt[$col] : null;
                    }
                }

                if ($cekData) {
                    $cekData->update($data);
                } else {
                    $data['id'] = $dt['id'];
                    BankSoal::create($data);
                }

                // Soal
                $columnSoal = Schema::getColumnListing('soal');
                foreach ($dt['soal'] as $dtSoal) {
                    $cekData = Soal::withTrashed()->find($dtSoal['id']);
                    $data = array();
                    foreach ($columnSoal as $colSoal) {
                        if ($colSoal != 'id') {
                            $data[$colSoal] = isset($dtSoal[$colSoal]) ? $dtSoal[$colSoal] : null;
                        }
                    }

                    if ($cekData) {
                        $cekData->update($data);
                    } else {
                        $data['id'] = $dtSoal['id'];
                        Soal::create($data);
                    }
                }
            }

            TarikData::where('nama', 'cbt-server')->update(['tarik_data_terakhir' => date('Y-m-d H:i:s')]);
            return response()->json(['status' => TRUE, 'data' => 'soal', 'message' => 'Data Soal berhasil ditarik.!']);
        }
    }

    public function dataJadwal(Request $request)
    {
        $cbtconf = new CbtMediaConf;
        $cbtconf->getAuth();
        $apiData = Http::withToken("$cbtconf->serverToken", "$cbtconf->serverAuthType")->get("$cbtconf->serverUrl/api/tarik-data/jadwal");

        if ($apiData['success']) {
            $columns = Schema::getColumnListing('jadwal');
            foreach ($apiData['data'] as $dt) {
                $cekData = Jadwal::withTrashed()->find($dt['id']);
                $data = array();
                foreach ($columns as $col) {
                    if ($col != 'id') {
                        $data[$col] = isset($dt[$col]) ? $dt[$col] : null;
                    }
                }

                if ($cekData) {
                    $cekData->update($data);
                } else {
                    $data['id'] = $dt['id'];
                    Jadwal::create($data);
                }
            }

            TarikData::where('nama', 'cbt-server')->update(['tarik_data_terakhir' => date('Y-m-d H:i:s')]);
            return response()->json(['status' => TRUE, 'data' => 'jadwal', 'message' => 'Data Jadwal berhasil ditarik.!']);
        }
    }

    public function dataServer(Request $request)
    {
        $cbtconf = new CbtMediaConf;
        $cbtconf->getAuth();
        $apiData = Http::withToken("$cbtconf->serverToken", "$cbtconf->serverAuthType")->get("$cbtconf->serverUrl/api/tarik-data/server", [
            'server_id' => $request->server_id
        ]);

        if ($apiData['success']) {
            $columns = Schema::getColumnListing('server');
            foreach ($apiData['data'] as $dt) {
                $cekData = Server::withTrashed()->find($dt['id']);
                $data = array();
                foreach ($columns as $col) {
                    if ($col != 'id') {
                        $data[$col] = isset($dt[$col]) ? $dt[$col] : null;
                    }
                }

                if ($cekData) {
                    $cekData->update($data);
                } else {
                    $data['id'] = $dt['id'];
                    Server::create($data);
                }
            }

            TarikData::where('nama', 'cbt-server')->update(['tarik_data_terakhir' => date('Y-m-d H:i:s')]);
            return response()->json(['status' => TRUE, 'data' => 'server', 'message' => 'Data Server berhasil ditarik.!']);
        }
    }

    public function dataRuang(Request $request)
    {
        $cbtconf = new CbtMediaConf;
        $cbtconf->getAuth();
        $apiData = Http::withToken("$cbtconf->serverToken", "$cbtconf->serverAuthType")->get("$cbtconf->serverUrl/api/tarik-data/ruang", [
            'server_id' => $request->server_id
        ]);

        if ($apiData['success']) {
            $columns = Schema::getColumnListing('ruang');
            foreach ($apiData['data'] as $dt) {
                $cekData = Ruang::withTrashed()->find($dt['id']);
                $data = array();
                foreach ($columns as $col) {
                    if ($col != 'id') {
                        $data[$col] = isset($dt[$col]) ? $dt[$col] : null;
                    }
                }

                if ($cekData) {
                    $cekData->update($data);
                } else {
                    $data['id'] = $dt['id'];
                    Ruang::create($data);
                }
            }

            TarikData::where('nama', 'cbt-server')->update(['tarik_data_terakhir' => date('Y-m-d H:i:s')]);
            return response()->json(['status' => TRUE, 'data' => 'ruang', 'message' => 'Data Ruang berhasil ditarik.!']);
        }
    }

    public function dataPeserta(Request $request)
    {
        $cbtconf = new CbtMediaConf;
        $cbtconf->getAuth();
        $apiData = Http::withToken("$cbtconf->serverToken", "$cbtconf->serverAuthType")->get("$cbtconf->serverUrl/api/tarik-data/peserta", [
            'server_id' => $request->server_id
        ]);

        if ($apiData['success']) {
            $columns = Schema::getColumnListing('peserta');
            foreach ($apiData['data'] as $dt) {
                $cekData = Peserta::withTrashed()->find($dt['id']);
                $data = array();
                foreach ($columns as $col) {
                    if ($col != 'id') {
                        $data[$col] = isset($dt[$col]) ? $dt[$col] : null;
                    }
                }

                if ($cekData) {
                    $cekData->update($data);
                } else {
                    $data['id'] = $dt['id'];
                    Peserta::create($data);
                }
            }

            TarikData::where('nama', 'cbt-server')->update(['tarik_data_terakhir' => date('Y-m-d H:i:s')]);
            return response()->json(['status' => TRUE, 'data' => 'peserta', 'message' => 'Data Peserta berhasil ditarik.!']);
        }
    }

    public function dataUserPeserta(Request $request)
    {
        $getPeserta = Peserta::get();
        foreach ($getPeserta as $dt) {
            $data['sekolah_id'] = $dt->sekolah_id;
            $data['peserta_id'] = $dt->id;
            $data['name'] = $dt->nama;
            $data['email'] = $dt->username;
            $data['username'] = $dt->username;
            $data['password'] = bcrypt($dt->password);
            $data['type'] = 'siswa';
            $data['status'] = $dt->status == 1 ? 'active' : null;

            $cekUser = User::find($dt->id);
            if (!$cekUser) {
                $data['id'] = $dt->id;
                User::create($data);
            }
        }
        return response()->json(['status' => TRUE, 'data' => 'peserta', 'message' => 'Data User Peserta berhasil dibuat.!']);
    }

    public function dataUserPengawas(Request $request)
    {
        $getPengawas = Pengawas::get();
        foreach ($getPengawas as $dt) {
            $data['sekolah_id'] = $dt->sekolah_id;
            $data['pengawas_id'] = $dt->id;
            $data['name'] = $dt->nama;
            $data['email'] = $dt->token;
            $data['username'] = $dt->token;
            $data['password'] = bcrypt($dt->token);
            $data['type'] = 'pengawas';
            $data['status'] = 'active';

            $cekUser = User::find($dt->id);
            if (!$cekUser) {
                $data['id'] = $dt->id;
                User::create($data);
            }
        }
        return response()->json(['status' => TRUE, 'data' => 'peserta', 'message' => 'Data User Pengawas berhasil dibuat.!']);
    }

    public function dataUserProktor(Request $request)
    {
        $getRuang = Ruang::get();
        foreach ($getRuang as $dt) {
            $data['sekolah_id'] = $dt->sekolah_id;
            $data['name'] = $dt->nama;
            $data['email'] = $dt->username;
            $data['username'] = $dt->username;
            $data['password'] = bcrypt($dt->password);
            $data['type'] = 'proktor';
            $data['status'] = 'active';

            $cekUser = User::find($dt->id);
            if (!$cekUser) {
                $data['id'] = $dt->id;
                User::create($data);
            }
        }
        return response()->json(['status' => TRUE, 'data' => 'peserta', 'message' => 'Data User Proktor berhasil dibuat.!']);
    }
}
