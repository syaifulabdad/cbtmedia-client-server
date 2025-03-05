<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class StatusUjian extends Model
{
    use HasFactory, HasUuids, SoftDeletes;
    protected $table = 'status_ujian';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [];

    protected static function booted()
    {
        static::addGlobalScope('status_ujian', function (Builder $builder) {
            // if (session('sekolah_id'))
            //     $builder->where('status_ujian.sekolah_id', session('sekolah_id'));
        });
    }

    function ujian()
    {
        return $this->belongsTo(Ujian::class);
    }

    function ptk()
    {
        return $this->belongsTo(Ptk::class);
    }

    function mata_pelajaran()
    {
        return $this->belongsTo(MataPelajaran::class);
    }

    function jurusan()
    {
        return $this->belongsTo(Jurusan::class);
    }

    function bank_soal()
    {
        return $this->belongsTo(BankSoal::class);
    }

    function jadwal()
    {
        return $this->belongsTo(Jadwal::class);
    }

    function ruang()
    {
        return $this->belongsTo(Ruang::class);
    }

    function status_peserta_ujian()
    {
        return $this->hasMany(StatusPesertaUjian::class);
    }
}
