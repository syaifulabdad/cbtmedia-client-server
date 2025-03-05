<?php

namespace App\Http\Controllers;

use App\Helpers\TanggalHelper;
use App\Http\Controllers\Controller;
use App\Models\BankSoal;
use App\Models\Jadwal;
use App\Models\RombonganBelajar;
use App\Models\Ruang;
use App\Models\StatusUjian as Model;
use App\Models\Ujian;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class StatusUjianController extends Controller
{
    public function __construct()
    {
        $this->model = new Model;
        $this->primaryKey = (new Model)->getKeyName();
        $this->title = 'Status Ujian';
        $this->cUrl = url()->current();

        // data table
        $this->dataTableOrder = ['waktu_ujian desc'];
    }

    public function index(Request $request)
    {
        $ujian = Ujian::orderBy('semester_id', 'desc')->where('status', 1)->first();
        $this->dataTable['dataCheck'] = ['label' => "<input type='checkbox' class='check-all' value='check-all'>", 'className' => 'text-center', 'width' => '10px'];

        $this->dataTable['nama_mapel'] = ['label' => "Mata Pelajaran & Guru"];
        $this->dataTable['tingkat'] = ['orderable' => true, 'className' => 'd-none', 'width' => '20px'];
        $this->dataTable['nama_jurusan'] = ['orderable' => true, 'className' => 'd-none'];
        $this->dataTable['kelas'] = [];

        $this->dataTable['sesi_ruang'] = ['label' => "Sesi & Ruang"];
        $this->dataTable['sesi_ke'] = ['orderable' => true, 'width' => '20px', 'className' => 'd-none'];
        $this->dataTable['waktu_ujian'] = ['orderable' => true, 'className' => 'd-none'];
        $this->dataTable['waktu'] = [];
        $this->dataTable['jumlah_peserta'] = [];
        $this->dataTable['token'] = [];
        $this->dataTable['status'] = ['className' => 'text-center'];

        return view('status-ujian')->with([
            'title' => $this->title .  ($ujian ? " - " . $ujian->nama : null),
            'title_sm' => $this->title,
            'cUrl' => $this->cUrl,
            'model' => $this->model,
            'dataTable' => $this->dataTable,
            'dataTableOrder' => $this->dataTableOrder,
            'dataTableFilter' => $this->dataTableFilter,
            'ujian' => $ujian,
            'getJadwal' => Jadwal::orderBy('hari_ke', 'asc')->orderBy('tanggal', 'asc')->whereRelation('bank_soal', 'status', 1)->get(),
            'getRuang' => Ruang::orderBy('nama', 'asc')->get(),
        ]);
    }

    public function dataTables(Request $request)
    {
        $builder = Model::select('*');
        if ($request->ruang_id)
            $builder->where('ruang_id', $request->ruang_id);

        $datatables = DataTables::of($builder)->smart(true)->addIndexColumn()
            ->rawColumns(['action', 'dataCheck', 'kelas', 'nama_mapel', 'sesi_ruang', 'jumlah_peserta', 'waktu', 'nama_jurusan', 'token', 'status']);

        $datatables->addColumn('dataCheck', function ($row) {
            return "<input type='checkbox' value='" . $row->id . "' name='id[]' class='data-check'>";
        });
        $datatables->addColumn('nama_mapel', function ($row) {
            $dt = "<span class='fw-bold text-uppercase'>" . $row->bank_soal->mata_pelajaran->nama . "</span>";
            $dt .= "<br>Jumlah Soal: <b>" . ($row->jumlah_soal_ditampilkan) . "</b>";
            $dt .= "<br>Guru: <b>" . ($row->ptk ? $row->ptk->nama : $row->nama_ptk) . "</b>";
            return $dt;
        });
        $datatables->addColumn('tingkat', function ($row) {
            return $row->bank_soal->tingkat;
        });
        $datatables->addColumn('nama_jurusan', function ($row) {
            return $row->bank_soal->jurusan ? $row->bank_soal->jurusan->nama : 'Semua';
        });
        $datatables->addColumn('kelas', function ($row) {
            $dt = '<table class="table-dt">';
            $dt .= "<tr><td>Tingkat </td><td> : <b>" . $row->bank_soal->tingkat . '</b></td></tr>';
            $dt .= "<tr><td>Jurusan </td><td> : <b>" . ($row->jurusan ? $row->jurusan->nama : 'Semua') . '</b></td></tr>';
            $rombelArr = [];
            if ($row->rombongan_belajar_id) {
                foreach (json_decode($row->rombongan_belajar_id) as $rombel_id => $rombel) {
                    $rombelArr[] = $rombel;
                }
            }
            $dt .= "<tr><td>Kelas </td><td> : <b>" . ($row->rombongan_belajar_id == null ? "Semua" : "<a href='javascript:void(0)' data-id='" . $row->id . "' onclick=\"tampilkanRombel('" . (implode(', ', $rombelArr)) . "')\">Lihat Kelas</a>") . '</b></td></tr>';
            $dt .= '</table>';
            return $dt;
        });
        $datatables->addColumn('sesi_ruang', function ($row) {
            $dt = '<table class="table-dt">';
            $dt .= "<tr><td>Sesi Ke </td><td> : <b>" . $row->sesi_ke . '</b></td></tr>';
            $dt .= "<tr><td>Ruang </td><td> : <b>" . ($row->ruang ? $row->ruang->nama : 'Semua') . '</b></td></tr>';
            $dt .= '</table>';
            return $dt;
        });
        $datatables->addColumn('waktu', function ($row) {
            $dt = '<table class="table-dt">';
            $dt .= "<tr><td>Mulai </td><td>: <b>" . date('d-m-Y H:i', strtotime($row->waktu_ujian)) . '</b></td></tr>';
            $dt .= "<tr><td>Selesai </td><td>: <b>" . date('d-m-Y H:i', strtotime($row->waktu_selesai_soal)) . '</b></td></tr>';
            $dt .= "<tr><td>Alokasi </td><td>: <b>" . $row->alokasi_waktu_peserta . ($row->mode_waktu == 'waktu-peserta' ? ' / ' . $row->alokasi_waktu_soal : '') . "</b> Menit</td></tr>";
            $dt .= '</table>';
            return $dt;
        });
        $datatables->addColumn('jumlah_peserta', function ($row) {
            $dt = '<table class="table-dt">';
            $dt .= "<tr><td>Semua </td><td> : <a href='javascript:void(0)'><b>" . ($row->status_peserta_ujian ? $row->status_peserta_ujian->count() : null) . "</b></a></td></tr>";
            $dt .= "<tr><td>Aktif </td><td> : <a href='javascript:void(0)'><b>" . ($row->status_peserta_ujian ? $row->status_peserta_ujian->where('status', 1)->count() : null) . "</b></a></td></tr>";
            $dt .= "<tr><td>Selesai </td><td> : <a href='javascript:void(0)'><b>" . ($row->status_peserta_ujian ? $row->status_peserta_ujian->where('status', 0)->count() : null) . "</b></a></td></tr>";
            $dt .= '</table>';
            return $dt;
        });
        $datatables->addColumn('token', function ($row) {
            return "<b class='fs-5'>" . strtoupper($row->token) . "</b>";
        });
        $datatables->addColumn('status', function ($row) {
            if ($row->status == 1) {
                return '<a href="javascript:void(0)" class="btn btn-info mt-1 mb-1 btnStatus" data-id="' . $row->id . '">AKTIF</a>';
            } else {
                return '<a href="javascript:void(0)" class="btn btn-warning mt-1 mb-1 btnStatus btnSelesai" data-id="' . $row->id . '">SELESAI</a>';
            }
        });


        $datatables->addColumn('action', function ($row) {
            $btn = null;
            $btn .= '<a href="javascript:void(0)" class="btn btn-primary mt-1 mb-1 btnEdit" data-id="' . $row->id . '"><i class="ri-pencil-fill"></i></a> ';
            $btn .= '<a href="javascript:void(0)" class="btn btn-danger mt-1 mb-1 btnDelete" data-id="' . $row->id . '"><i class="ri-delete-bin-fill"></i></a> ';

            return $btn;
        });
        return $datatables->make(true);
    }

    public function edit($id)
    {
        $getData = Model::with(['bank_soal', 'jadwal'])->find($id);
        $bankSoal = BankSoal::with('jadwal')->whereRelation('jadwal', 'id', $getData->jadwal_id)->find($getData->bank_soal_id);
        $rombelArr = null;
        if ($bankSoal->rombongan_belajar_id) {
            foreach (json_decode($bankSoal->rombongan_belajar_id) as $soalRombel_id) {
                $getRombel = RombonganBelajar::find($soalRombel_id);
                if ($getRombel) {
                    $selected = null;
                    if ($getData->rombongan_belajar_id) {
                        foreach (json_decode($getData->rombongan_belajar_id) as $rombel_id => $rombel) {
                            $selected .= $soalRombel_id == $rombel_id ? 'selected' : null;
                        }
                    } else {
                        $selected .= 'selected';
                    }

                    $rombelArr .= "<option value='$soalRombel_id' $selected>$getRombel->nama</option>";
                }
            }
        } else {
            $rombelArr .= "<option value='' selected>Semua Rombel</option>";
        }
        $getData->selectRombel = $rombelArr;
        $getData->nama_jurusan = $bankSoal->jurusan_id ? $bankSoal->jurusan->nama : "Semua Jurusan";
        $getData->nama_mapel = $bankSoal->mata_pelajaran ? $bankSoal->mata_pelajaran->nama : null;
        return response()->json($getData);
    }

    public function postStore(Request $request)
    {
        $jadwal_id = $request->jadwal_id;
        $bank_soal_id = $request->bank_soal_id;

        $jadwal = Jadwal::find($jadwal_id);
        $bankSoal = BankSoal::find($bank_soal_id);
        $ruang = Ruang::find($request->ruang_id);

        // validasi
        $validate['sesi_ke'] = 'required';
        $validate['mode_waktu'] = 'required';
        $validate['alokasi_waktu'] = 'required';
        $validate['waktu_mulai'] = 'required';
        if ($request->mode_waktu == 'waktu-peserta') {
            $validate['alokasi_waktu_soal'] = 'required';
        }

        $validator = Validator::make($request->all(), $validate);
        if ($validator->fails()) {
            return response()->json([
                'inputerror' => $validator->errors()->keys(),
                'error_string' => $validator->errors()->all()
            ]);
        }
        // end validasi

        $data['sekolah_id'] = $bankSoal->sekolah_id;
        $data['ujian_id'] = $bankSoal->ujian_id;
        $data['jadwal_id'] = $jadwal_id;

        $data['bank_soal_id'] = $bank_soal_id;
        $data['mata_pelajaran_id'] = $bankSoal->mata_pelajaran_id;
        $data['nama_mapel'] = $bankSoal->mata_pelajaran ? $bankSoal->mata_pelajaran->nama : null;
        $data['jumlah_soal'] = $bankSoal->soal->count();
        $data['jumlah_soal_ditampilkan'] = $bankSoal->soal->where('status', 1)->count();

        $data['ptk_id'] = $bankSoal->ptk_id;
        $data['nama_ptk'] = $bankSoal->nama ? $bankSoal->ptk->nama : $bankSoal->nama_ptk;

        $data['hari_ke'] = $jadwal->hari_ke;
        $data['sesi_ke'] = $request->sesi_ke ?? null;

        $data['ruang_id'] = $request->ruang_id ?? null;
        $data['nama_ruang'] = $request->ruang_id ? $ruang->nama : null;

        // rombel, jurusan & tingkat
        $data['tingkat_id'] = $bankSoal->tingkat_id;
        $data['tingkat'] = $bankSoal->tingkat;
        $data['jurusan_id'] = $bankSoal->jurusan_id;
        $data['nama_jurusan'] = $bankSoal->jurusan ? $bankSoal->jurusan->nama : null;

        $getRombel = RombonganBelajar::select('id', 'nama')->whereIn('id', $request->rombongan_belajar_id)->orderBy('nama')->get();
        if (count($getRombel)) {
            $rombelArr = [];
            foreach ($getRombel as $rombel) {
                $rombelArr[$rombel->id] = $rombel->nama;
            }
            $data['rombongan_belajar_id'] = json_encode($rombelArr);
        } else {
            $data['rombongan_belajar_id'] = null;
        }
        // end rombel


        $tanggal = $jadwal->tanggal;
        $jamMulai = $request->waktu_mulai;

        $data['mode_waktu'] = $request->mode_waktu;
        $data['waktu_ujian'] = "$tanggal $jamMulai";
        $data['tanggal'] = $tanggal;
        $data['jam'] = $jamMulai;
        $data['tgl'] = date('d', strtotime($tanggal));
        $data['bulan'] = date('m', strtotime($tanggal));
        $data['bulan_string'] = (new TanggalHelper)->bulan(date('m', strtotime($tanggal)));
        $data['tahun'] = date('Y', strtotime($tanggal));
        $data['tahun_string'] = null;

        if ($request->mode_waktu == 'waktu-peserta') {
            $data['alokasi_waktu_peserta'] = $request->alokasi_waktu;
            $data['alokasi_waktu_soal'] = $request->alokasi_waktu_soal;
            $data['waktu_selesai_soal'] = Carbon::parse("$tanggal $jamMulai")->addMinutes($request->alokasi_waktu_soal)->toDateTimeString();
            $data['waktu_selesai_peserta'] = null;
        } else {
            $data['alokasi_waktu_peserta'] = $request->alokasi_waktu;
            $data['alokasi_waktu_soal'] = $request->alokasi_waktu;
            $data['waktu_selesai_soal'] = Carbon::parse("$tanggal $jamMulai")->addMinutes($request->alokasi_waktu)->toDateTimeString();
            $data['waktu_selesai_peserta'] = Carbon::parse("$tanggal $jamMulai")->addMinutes($request->alokasi_waktu)->toDateTimeString();
        }

        $data['batas_masuk'] = $request->batas_masuk ?? null;
        $data['waktu_minimal'] = $request->waktu_minimal ?? null;

        if ($request->batas_masuk) {
            $data['waktu_terlambat'] = Carbon::parse("$tanggal $jamMulai")->addMinutes($request->batas_masuk)->toDateTimeString();
        }

        $cekStatusUjian = Model::where([
            'hari_ke' => $jadwal->hari_ke,
            'tanggal' => $jadwal->tanggal,
            'sesi_ke' => $request->sesi_ke
        ])->first();

        if ($request->id) {
            Model::where('id', $request->id)->update($data);
        } else {
            $data['token'] = $this->getToken();

            if ($cekStatusUjian && $cekStatusUjian->ruang_id == null)
                return response()->json(['status' => FALSE, 'message' => "Soal sudah diaktifkan.!!"]);

            $data['sekolah_id'] = session('sekolah_id');
            Model::create($data);
        }
        return response()->json(['status' => TRUE, 'message' => null]);
    }

    private function getToken($length = 6)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyz0123456789abcdefghijklmnopqrstuvwxyz';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function deleteDestroy(string $id)
    {
        if (is_array(request('id'))) {
            foreach (request('id') as $key => $value) {
                Model::find($value)->delete();
            }
        } else {
            Model::find($id)->delete();
        }
        return response()->json(['status' => TRUE]);
    }

    function getSelectBankSoal(Request $request)
    {
        $bankSoal = BankSoal::whereRelation('jadwal', 'id', $request->jadwal_id)
            ->where('status', 1);

        $result = null;
        foreach ($bankSoal->get() as $soal) {
            $result .= "<option value='$soal->id'>" . $soal->mata_pelajaran->nama . "</option>";
        }

        return $result ?? "<option value=''>Tidak Ada Soal Yang Aktif</option>";
    }

    public function getBankSoal(Request $request)
    {
        $getData = BankSoal::with(['jadwal', 'mata_pelajaran', 'jurusan'])->whereRelation('jadwal', 'id', $request->jadwal_id)->find($request->bank_soal_id);
        $getData->waktu_mulai = date('H:i');
        $rombelArr = null;
        if ($getData->rombongan_belajar_id) {
            foreach (json_decode($getData->rombongan_belajar_id) as $rombel_id) {
                $rombel = RombonganBelajar::find($rombel_id);
                $rombelArr .= "<option value='$rombel_id' selected>" . $rombel->nama . "</option>";
            }
        } else {
            $rombelArr = "<option value='' selected>Semua Rombel</option>";
        }
        $getData->selectRombel = $rombelArr;
        $getData->nama_jurusan = $getData->jurusan_id ? $getData->jurusan->nama : "Semua Jurusan";
        $getData->nama_mapel = $getData->mata_pelajaran ? $getData->mata_pelajaran->nama : null;
        return response()->json($getData);
    }
}
