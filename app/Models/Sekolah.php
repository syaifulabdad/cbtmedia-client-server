<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class Sekolah extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'sekolah';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [];

    protected static function booted()
    {
        static::addGlobalScope('sekolah', function (Builder $builder) {
            // if (session('sekolah_id'))
            //     $builder->where('sekolah.id', session('sekolah_id'));
        });
    }

    protected function logoSekolah(): Attribute
    {
        return Attribute::make(
            get: fn ($logo_sekolah) => asset('/storage/app/sekolah/' . $this->id . "/" . $logo_sekolah),
        );
    }
}
