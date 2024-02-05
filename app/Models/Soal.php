<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Soal extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'soal';
    // protected $keyType = 'string';
    // public $incrementing = false;
    protected $guarded = [];

    protected static function booted()
    {
        static::addGlobalScope('soal', function (Builder $builder) {
            // $builder->where('soal.sekolah_id', session('sekolah_id'));
            if (request('bank_soal_id'))
                $builder->where('soal.bank_soal_id', request('bank_soal_id'));
        });
    }

    function ujian()
    {
        return $this->belongsTo(Ujian::class);
    }

    function bank_soal()
    {
        return $this->belongsTo(BankSoal::class);
    }

    function getUrutan($bank_soal_id)
    {
        $urutan = $this->where('bank_soal_id', $bank_soal_id)->max('urutan');
        $newUrutan = $urutan ? $urutan + 1 : 1;
        return $newUrutan;
    }
}
