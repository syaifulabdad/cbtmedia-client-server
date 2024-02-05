<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RombonganBelajar extends Model
{
    use HasFactory, HasUuids, SoftDeletes;
    protected $table = 'rombongan_belajar';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [];

    protected static function booted()
    {
        static::addGlobalScope('rombongan_belajar', function (Builder $builder) {
            // $builder->where('rombongan_belajar.sekolah_id', session('sekolah_id'));
        });
    }

    function semester()
    {
        return $this->belongsTo(Semester::class);
    }

    function jurusan()
    {
        return $this->belongsTo(Jurusan::class);
    }

    function jurusan_dapodik()
    {
        return $this->belongsTo(Jurusan::class, 'jurusan_id', 'dapodik_id');
    }

    function ptk()
    {
        return $this->belongsTo(Ptk::class);
    }

    function ptk_dapodik()
    {
        return $this->belongsTo(Ptk::class, 'ptk_id', 'dapodik_id');
    }

    function mata_pelajaran()
    {
        return $this->belongsTo(MataPelajaran::class);
    }


    function selectFormInput($where = null)
    {
        $query = $this->orderBy('semester_id', 'ASC')->orderBy('tingkat_id', 'ASC')->orderBy('nama', 'ASC');
        if ($where)
            $query->where($where);

        if (request()->ajax()) {
            $data = [];
            $data[] = '.:: Rombel / Kelas ::.';
        } else {
            $data = '<option value="">.:: Rombel / Kelas ::.</option>';
        }

        foreach ($query->get() as $ref) {
            $id = $ref->id;
            $val = $ref->nama;

            if (request()->ajax()) {
                $data[$id] =  $val;
            } else {
                $data .= '<option value="' . $id . '">' . $val . '</option>';
            }
        }
        return $data;
    }
}
