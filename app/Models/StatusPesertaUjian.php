<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class StatusPesertaUjian extends Model
{
    use HasFactory, HasUuids, SoftDeletes;
    protected $table = 'status_peserta_ujian';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [];

    protected static function booted()
    {
        static::addGlobalScope('status_peserta_ujian', function (Builder $builder) {
            // if (session('sekolah_id'))
            //     $builder->where('status_peserta_ujian.sekolah_id', session('sekolah_id'));
        });
    }

    function ujian()
    {
        return $this->belongsTo(Ujian::class);
    }
}
