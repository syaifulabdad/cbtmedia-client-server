<?php

namespace App\Models;

use App\Models\Ref\Agama;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Peserta extends Model
{
    use HasFactory, HasUuids, SoftDeletes;
    protected $table = 'peserta';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [];

    protected static function booted()
    {
        static::addGlobalScope('peserta', function (Builder $builder) {
            // $builder->where('peserta.sekolah_id', session('sekolah_id'));
        });
    }

    function ujian()
    {
        return $this->belongsTo(Ujian::class);
    }

    function agama()
    {
        return $this->belongsTo(Agama::class);
    }

    function ruang()
    {
        return $this->belongsTo(Ruang::class, 'ruang_id', 'id');
    }
}
