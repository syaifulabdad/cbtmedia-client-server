<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pengaturan extends Model
{
    use HasFactory, HasUuids, SoftDeletes;
    protected $table = 'pengaturan';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [];

    protected static function booted()
    {
        static::addGlobalScope('pengaturan', function (Builder $builder) {
            // if (session('sekolah_id'))
            //     $builder->where('pengaturan.sekolah_id', session('sekolah_id'));
        });
    }
}
