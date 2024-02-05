<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class BankSoal extends Model
{
    use HasFactory, SoftDeletes, HasUuids;
    protected $table = 'bank_soal';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [];

    protected static function booted()
    {
        static::addGlobalScope('bank_soal', function (Builder $builder) {
            // $builder->where('bank_soal.sekolah_id', session('sekolah_id'));
            if (session('ptk_id'))
                $builder->where('bank_soal.ptk_id', session('ptk_id'));
        });
    }

    function ujian()
    {
        return $this->belongsTo(Ujian::class);
    }

    function mata_pelajaran()
    {
        return $this->belongsTo(MataPelajaran::class, 'mata_pelajaran_id', 'id');
    }

    function ptk()
    {
        return $this->belongsTo(Ptk::class);
    }

    function jurusan()
    {
        return $this->belongsTo(Jurusan::class);
    }
}
