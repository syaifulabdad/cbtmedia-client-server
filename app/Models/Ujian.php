<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Ujian extends Model
{
    use HasFactory, SoftDeletes, HasUuids;
    protected $table = 'ujian';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [];

    protected static function booted()
    {
        static::addGlobalScope('ujian', function (Builder $builder) {
            // $builder->where('ujian.sekolah_id', session('sekolah_id'));
        });
    }

    function semester()
    {
        return $this->belongsTo(Semester::class);
    }

    function selectFormInput($where = null)
    {
        $query = $this->orderBy('tanggal', 'desc');
        if ($where)
            $query->where($where);

        if (request()->ajax()) {
            $data = [];
            $data[null] = '.:: Ujian ::.';
        } else {
            $data = '<option value="">.:: Ujian ::.</option>';
        }

        foreach ($query->get() as $ref) {
            $id = $ref->id;
            $val = $ref->nama;

            if (request()->ajax()) {
                $data[$id] =  $val;
            } else {
                $data .= '<option value="' . $id . '" ' . ($ref->status == 1 ? 'selected' : null) . '>' . $val . '</option>';
            }
        }
        return $data;
    }
}
