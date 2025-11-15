<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Ruang extends Model
{
    use HasFactory, SoftDeletes, HasUuids;
    protected $table = 'ruang';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [];

    protected static function booted()
    {
        static::addGlobalScope('ruang', function (Builder $builder) {
            // $builder->where('ruang_ujian.sekolah_id', session('sekolah_id'));
        });
    }

    function ujian()
    {
        return $this->belongsTo(Ujian::class);
    }

    function selectFormInput($where = null)
    {
        $query = $this->orderBy('nama', 'ASC');
        if ($where)
            $query->where($where);

        if (request()->ajax()) {
            $data = [];
            $data[null] = '.:: Ruang ::.';
        } else {
            $data = '<option value="">.:: Ruang ::.</option>';
        }

        foreach ($query->get() as $ref) {
            $id = $ref->id;
            $val = $ref->nama;

            if (request()->ajax()) {
                $data[$id] = $val;
            } else {
                $data .= '<option value="' . $id . '">' . $val . '</option>';
            }
        }
        return $data;
    }
}
