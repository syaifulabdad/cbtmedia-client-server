<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class MataPelajaran extends Model
{
    use HasFactory, HasUuids, SoftDeletes;
    protected $table = 'mata_pelajaran';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [];

    protected static function booted()
    {
        static::addGlobalScope('mata_pelajaran', function (Builder $builder) {
            // $builder->where('mata_pelajaran.sekolah_id', session('sekolah_id'));
            // $builder->select(['mata_pelajaran.*', 'mata_pelajaran.nama as mapel']);
        });
    }

    function selectFormInput($where = null)
    {
        $query = $this->orderBy('urutan', 'ASC')->orderBy('nama', 'ASC');
        if ($where)
            $query->where($where);

        if (request()->ajax()) {
            $data = [];
            $data[null] = '.:: Mata Pelajaran ::.';
        } else {
            $data = '<option value="">.:: Mata Pelajaran ::.</option>';
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
