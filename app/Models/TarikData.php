<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TarikData extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'tarik_data';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [];

    protected static function booted()
    {
        static::addGlobalScope('tarik_data', function (Builder $builder) {
            // $builder->where('tarik_data.sekolah_id', session('sekolah_id'));
        });
    }
}
