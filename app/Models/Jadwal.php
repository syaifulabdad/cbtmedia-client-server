<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Jadwal extends Model
{
    use HasFactory, HasUuids, SoftDeletes;
    protected $table = 'jadwal';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [];

    protected static function booted()
    {
        static::addGlobalScope('jadwal', function (Builder $builder) {
            if (session('sekolah_id'))
                $builder->where('jadwal.sekolah_id', session('sekolah_id'));
        });
    }

    function selectFormInput($where = null)
    {
        $query = $this->orderBy('hari_ke')->orderBy('tanggal', 'ASC');
        if ($where)
            $query->where($where);

        if (request()->ajax()) {
            $data = [];
            $data[null] = '.:: Jadwal ::.';
        } else {
            $data = '<option value="">.:: Jadwal ::.</option>';
        }

        foreach ($query->get() as $ref) {
            $id = $ref->id;
            $val = $ref->nama . " Hari Ke-$ref->hari_ke Tgl. $ref->tanggal";

            if (request()->ajax()) {
                $data[$id] =  $val;
            } else {
                $data .= '<option value="' . $id . '" data-tanggal="' . $ref->tanggal . '" data-hari_ke="' . $ref->hari_ke . '" data-bank_soal_id="' . $ref->bank_soal_id . '">' . $val . '</option>';
            }
        }
        return $data;
    }
}
