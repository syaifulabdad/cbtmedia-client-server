<?php

namespace App\Http\Controllers\Client;

use Carbon\Carbon;
use App\Models\Soal;
use App\Models\Ujian;
use App\Models\StatusUjian;
use Illuminate\Http\Request;
use App\Models\StatusPesertaUjian;
use Illuminate\Support\Facades\Auth;

class UjianController extends Controller
{
     public function __construct()
     {
          parent::__construct();
          $this->title = 'Ujian';
          $this->cUrl = url()->current();
     }

     public function postCekToken(Request $request)
     {
          $statusUjian = StatusUjian::find($request->status_ujian_id);

          if (!$statusUjian) {
               return response()->json(['status' => false, 'message' => 'Ujian tidak ditemukan.']);
          }

          // contoh validasi token
          if (strtolower($statusUjian->token) === strtolower(trim($request->token))) {
               return response()->json(['status' => true, 'message' => 'Token valid.']);
          }

          return response()->json(['status' => false, 'message' => 'Token tidak valid.']);
     }

     function pilihSoal($status_ujian_id)
     {
          $getStatusUjian = StatusUjian::find($status_ujian_id);

          $data = [];
          $data['sekolah_id'] = session('sekolah_id');
          $data['ip_address'] = request()->ip();

          $data['ujian_id'] = $this->dataUjian->id;
          $data['status_ujian_id'] = $status_ujian_id;
          $data['peserta_id'] = session('peserta_id');
          $data['tingkat_id'] = $this->dataPeserta->tingkat_id;
          $data['jurusan_id'] = $this->dataPeserta->jurusan_id;
          $data['rombongan_belajar_id'] = $this->dataPeserta->rombongan_belajar_id;
          $data['ruang_id'] = $this->dataPeserta->ruang_id;
          $data['bank_soal_id'] = $getStatusUjian->bank_soal_id;
          $data['tanggal'] = $getStatusUjian->tanggal;
          $data['alokasi_waktu_peserta'] = $getStatusUjian->alokasi_waktu_peserta;

          // Ambil alokasi waktu dalam menit
          $alokasiWaktuMenit = $getStatusUjian->alokasi_waktu_peserta;

          // Waktu mulai (saat ini)
          $waktuMulai = Carbon::now();
          $data['waktu_mulai'] = $waktuMulai->format('Y-m-d H:i:s');

          // Menghitung Waktu Harus Selesai
          $waktuHarusSelesai = $waktuMulai->addMinutes($alokasiWaktuMenit);

          // Hasil: Format waktu selesai ke string database
          $data['waktu_harus_selesai'] = $waktuHarusSelesai->format('Y-m-d H:i:s');

          // ambil soal
          $dataSoal = [];

          $getSoal_tidak_acak = Soal::where('bank_soal_id', $getStatusUjian->bank_soal_id)
               ->where('acak_soal', 0)
               ->where('status', 1)
               ->orderBy('urutan', 'asc')->get();
          $getSoal_acak = Soal::where('bank_soal_id', $getStatusUjian->bank_soal_id)
               ->where('acak_soal', 1)
               ->where('status', 1)
               ->inRandomOrder()->get();

          foreach ($getSoal_tidak_acak as $soal) {
               $soalArray = [];
               $soalArray['id'] = $soal->id;
               $soalArray['soal_teks'] = $soal->soal_teks;
               $soalArray['urutan'] = $soal->urutan;
               $soalArray['jenis_soal'] = $soal->jenis_soal;
               $soalArray['acak_soal'] = $soal->acak_soal;
               $soalArray['acak_jawaban'] = $soal->acak_jawaban;

               $jawabanArray = [];
               if ($soal->jenis_soal == 'pilihan') {
                    $opsiJawaban = [];
                    // 1. Kumpulkan semua opsi jawaban yang tidak kosong
                    for ($i = 1; $i <= 5; $i++) {
                         $key = "jawaban_$i";
                         if (!empty($soal->{$key})) {
                              $opsiJawaban[$key] = $soal->{$key};
                         }
                    }

                    if ($soal->acak_jawaban == 1) {
                         // 2. Ambil semua kunci dari opsi jawaban
                         $keys = array_keys($opsiJawaban);

                         // 3. Acak urutan kunci (variabel $keys)
                         shuffle($keys);

                         // 4. Buat array baru ($opsiJawabanAcak) berdasarkan urutan kunci yang sudah diacak
                         $opsiJawabanAcak = [];
                         foreach ($keys as $key) {
                              $opsiJawabanAcak[$key] = $opsiJawaban[$key];
                         }

                         // 5. Ganti $opsiJawaban dengan hasil acakan
                         $opsiJawaban = $opsiJawabanAcak;
                    }

                    // 6. Masukkan hasil acakan (atau yang tidak diacak) ke dalam $jawabanArray
                    $jawabanArray[] = $opsiJawaban;
                    $soalArray['opsi_jawaban'] = $jawabanArray;
               } else {
                    $soalArray['opsi_jawaban'] = null;
               }

               // $soalArray['jawaban'] = null;
               $dataSoal[] = $soalArray;
          }

          $nomorAcak = [];
          foreach ($getSoal_acak as $val) {
               $nomorAcak[] = $val['urutan'];
          }
          shuffle($nomorAcak);

          foreach ($getSoal_acak as $key => $soal) {
               $soalArray = [];
               $soalArray['id'] = $soal->id;
               $soalArray['soal_teks'] = $soal->soal_teks;
               $soalArray['urutan'] = $nomorAcak[$key];
               $soalArray['jenis_soal'] = $soal->jenis_soal;
               $soalArray['acak_soal'] = $soal->acak_soal;
               $soalArray['acak_jawaban'] = $soal->acak_jawaban;

               $jawabanArray = [];
               if ($soal->jenis_soal == 'pilihan') {
                    $opsiJawaban = [];
                    // 1. Kumpulkan semua opsi jawaban yang tidak kosong
                    for ($i = 1; $i <= 5; $i++) {
                         $key = "jawaban_$i";
                         if (!empty($soal->{$key})) {
                              $opsiJawaban[$key] = $soal->{$key};
                         }
                    }

                    if ($soal->acak_jawaban == 1) {
                         // 2. Ambil semua kunci dari opsi jawaban
                         $keys = array_keys($opsiJawaban);

                         // 3. Acak urutan kunci (variabel $keys)
                         shuffle($keys);

                         // 4. Buat array baru ($opsiJawabanAcak) berdasarkan urutan kunci yang sudah diacak
                         $opsiJawabanAcak = [];
                         foreach ($keys as $key) {
                              $opsiJawabanAcak[$key] = $opsiJawaban[$key];
                         }

                         // 5. Ganti $opsiJawaban dengan hasil acakan
                         $opsiJawaban = $opsiJawabanAcak;
                    }

                    // 6. Masukkan hasil acakan (atau yang tidak diacak) ke dalam $jawabanArray
                    $jawabanArray[] = $opsiJawaban;
                    $soalArray['opsi_jawaban'] = $jawabanArray;
               } else {
                    $soalArray['opsi_jawaban'] = null;
               }

               // $soalArray['jawaban'] = null;
               $dataSoal[] = $soalArray;
          }

          usort($dataSoal, function ($a, $b) {
               return $a['urutan'] <=> $b['urutan'];
          });
          $soalJson = json_encode($dataSoal);
          $data['soal_json'] = $soalJson;

          $cekStatusPesertaUjian = StatusPesertaUjian::where('peserta_id', session('peserta_id'))
               ->where('status_ujian_id', $status_ujian_id)
               ->first();

          if ($cekStatusPesertaUjian) {
               // $data['updated_at'] = date('Y-m-d H:i:s');
               // $cekStatusPesertaUjian->update($data);
          } else {
               $data['created_at'] = date('Y-m-d H:i:s');
               StatusPesertaUjian::create($data);
          }

          return response()->json(['status' => true]);
     }

     function postUjian(Request $request)
     {
          $status_ujian_id = $request->status_ujian_id;
          $ujian = Ujian::first();
          $statusUjian = StatusUjian::find($status_ujian_id);
          $statusPesertaUjian = StatusPesertaUjian::where('peserta_id', session('peserta_id'))->where('status_ujian_id', $status_ujian_id)->first();

          $data = [];
          $data['title'] = $ujian?->nama;
          $data['cUrl'] = $this->cUrl;
          $data['ujian'] = $ujian;
          $data['statusUjian'] = $statusUjian;
          $data['statusPesertaUjian'] = $statusPesertaUjian;

          return view('client.ujian')->with($data);
     }

     function postAmbilSoal(Request $request)
     {
          $status_peserta_ujian_id = $request->status_peserta_ujian_id;
          $statusPesertaUjian = StatusPesertaUjian::find($status_peserta_ujian_id);

          if ($statusPesertaUjian?->soal_json) {
               $statusUjian = StatusUjian::find($statusPesertaUjian->status_ujian_id);
               if ($statusPesertaUjian->status == 0) {
                    return response([
                         'status' => false,
                         'status_ujian' => $statusPesertaUjian->status,
                         'soal' => null,
                         'message' => "Soal Selesai Dikerjakan.!! <br><a href='/home' class='btn btn-primary mt-4'><i class='ri-home-line'></i> Kembali</a>"
                    ]);
               }

               if ($statusUjian->status == 0) {
                    return response([
                         'status' => false,
                         'status_ujian' => $statusUjian->status,
                         'soal' => null,
                         'message' => "Waktu Habis.!! <br><a href='javascript:void(0)' class='btn btn-success mt-4 btnFinish'><i class='ri-home-line'></i> Selesai</a>"
                    ]);
               }

               if (date('Y-m-d H:i:s', strtotime($statusPesertaUjian->waktu_harus_selesai)) > date('Y-m-d H:i:s')) {
                    return response([
                         'status' => true,
                         'status_ujian' => $statusPesertaUjian->status,
                         'soal' => json_decode($statusPesertaUjian->soal_json),
                         'message' => null
                    ]);
               } else {
                    return response([
                         'status' => false,
                         'status_ujian' => $statusPesertaUjian->status,
                         'soal' => null,
                         'message' => "Waktu Habis.!! <br><a href='javascript:void(0)' class='btn btn-success mt-4 btnFinish'><i class='ri-home-line'></i> Selesai</a>"
                    ]);
               }
          }
          return response(['status' => false, 'soal' => null, 'message' => "Data tidak tersedia.!!"]);
     }

     public function postSimpanJawaban(Request $request)
     {
          $koreksiJawaban = false;

          $status_ujian_id = $request->status_ujian_id;
          // 🔹 Ambil status peserta ujian
          $statusPesertaUjian = StatusPesertaUjian::where('peserta_id', session('peserta_id'))
               ->where('status_ujian_id', $status_ujian_id)
               ->first();

          if (!$statusPesertaUjian) {
               return response()->json(['status' => false, 'message' => 'Status ujian tidak ditemukan.']);
          }

          if ($koreksiJawaban) {
               // 🔹 Ambil semua kunci jawaban sekaligus dengan pluck()
               $kunciArray = Soal::where('bank_soal_id', $statusPesertaUjian->bank_soal_id)
                    ->pluck('kunci_jawaban', 'id')
                    ->mapWithKeys(fn($val, $key) => [$key => $val])
                    ->toArray();

               // 🔹 Decode jawaban peserta (string JSON)
               $jawabanArray = json_decode($request->jawaban, true);

               if (!is_array($jawabanArray)) {
                    return response()->json(['status' => false, 'message' => 'Format jawaban tidak valid.']);
               }

               // 🔹 Cek benar/salah untuk tiap jawaban
               foreach ($jawabanArray as &$item) {
                    $soalId = $item['soal_id'] ?? null;
                    $jawaban = $item['jawaban'] ?? '';

                    // $item['kunci_jawaban'] = $kunciArray[$soalId];
                    $item['benar'] = 0; // default salah

                    if ($soalId && $jawaban && isset($kunciArray[$soalId])) {
                         $replaceJawaban = str_replace('jawaban_', '', $jawaban);
                         if ($replaceJawaban == $kunciArray[$soalId]) {
                              $item['benar'] = 1;
                         }
                    }
               }
               unset($item);

               // 🔹 Simpan kembali hasil JSON
               $statusPesertaUjian->update([
                    'jawaban_json' => json_encode($jawabanArray),
                    'updated_at' => now(),
               ]);

               // 🔹 Opsional: hitung jumlah benar
               $jumlahBenar = collect($jawabanArray)->where('benar', 1)->count();

               return response()->json([
                    'status' => true,
                    'message' => 'Jawaban berhasil disimpan.',
                    // 'jumlah_benar' => $jumlahBenar,
                    // 'data' => $jawabanArray
               ]);
          } else {
               if ($statusPesertaUjian) {
                    $statusPesertaUjian->update([
                         'jawaban_json' => $request->jawaban,
                         'updated_at' => date('Y-m-d H:i:s')
                    ]);
                    return response()->json([
                         'status' => true,
                         'message' => 'Jawaban berhasil disimpan.',
                    ]);
               }

          }
     }

     function postAkhiriUjian(Request $request)
     {
          $status_ujian_id = $request->status_ujian_id;
          $statusPesertaUjian = StatusPesertaUjian::where('peserta_id', session('peserta_id'))
               ->where('status_ujian_id', $status_ujian_id)
               ->first();
          if ($statusPesertaUjian?->jawaban_json) {
               // update status ujian
               $statusPesertaUjian->update([
                    'status' => 0,
                    'updated_at' => date('Y-m-d H:i:s')
               ]);

               // // update user
               // auth()->user()->update([
               //      'status_login' => 0,
               //      'login_uuid' => null,
               //      'ip_address' => null,
               // ]);

               // Auth::logout();

               return response()->json(['status' => true]);
          } else {
               return response()->json(['status' => false, 'message' => "Jawaban belum tersimpan.!!"]);
          }
     }

     function cekStatusUjian(Request $request)
     {

          $status_peserta_ujian_id = $request->status_peserta_ujian_id;
          $statusPesertaUjian = StatusPesertaUjian::find($status_peserta_ujian_id);

          if ($statusPesertaUjian?->soal_json) {
               $statusUjian = StatusUjian::find($statusPesertaUjian->status_ujian_id);
               if ($statusPesertaUjian->status == 0) {
                    return response([
                         'status' => false,
                         'status_ujian' => $statusPesertaUjian->status,
                         'message' => "Soal Selesai Dikerjakan.!! <br><a href='/home' class='btn btn-primary mt-4'><i class='ri-home-line'></i> Kembali</a>"
                    ]);
               }

               if ($statusUjian->status == 0) {
                    return response([
                         'status' => false,
                         'status_ujian' => $statusUjian->status,
                         'message' => "Waktu Habis.!! <br><a href='javascript:void(0)' class='btn btn-success mt-4 btnFinish'><i class='ri-home-line'></i> Selesai</a>"
                    ]);
               }

               if (date('Y-m-d H:i:s', strtotime($statusPesertaUjian->waktu_harus_selesai)) > date('Y-m-d H:i:s')) {
                    return response([
                         'status' => true,
                         'status_ujian' => $statusPesertaUjian->status,
                         'soal' => json_decode($statusPesertaUjian->soal_json),
                         'message' => null
                    ]);
               } else {
                    return response([
                         'status' => false,
                         'status_ujian' => $statusPesertaUjian->status,
                         'message' => "Waktu Habis.!! <br><a href='javascript:void(0)' class='btn btn-success mt-4 btnFinish'><i class='ri-home-line'></i> Selesai</a>"
                    ]);
               }
          }
     }

     function postLogStatus(Request $request)
     {
          $status_peserta_ujian_id = $request->status_peserta_ujian_id;
          $status = $request->status;
          $statusPesertaUjian = StatusPesertaUjian::find($status_peserta_ujian_id);
          if ($statusPesertaUjian) {
               $data['idle'] = $request->idle;
               if ($request->hidden)
                    $data['hidden'] = ($statusPesertaUjian->hidden + 1);

               $statusPesertaUjian->update($data);
          }

          return response()->json(['status' => true]);
     }
}
