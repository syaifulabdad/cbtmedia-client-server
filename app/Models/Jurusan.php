<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Jurusan extends Model
{
    use HasFactory, HasUuids, SoftDeletes;
    protected $table = 'jurusan';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [];

    protected static function booted()
    {
        static::addGlobalScope('jurusan', function (Builder $builder) {
            // $builder->where('jurusan.sekolah_id', session('sekolah_id'));
            // $builder->orWhereNull('jurusan.sekolah_id');
        });
    }

    function selectFormInput($where = null)
    {
        $query = $this->where('status', 1)->orderBy('urutan', 'ASC')->orderBy('nama', 'ASC');
        if ($where)
            $query->where($where);

        if (request()->ajax()) {
            $data = [];
            $data[null] = '.:: Jurusan ::.';
        } else {
            $data = '<option value="">.:: Jurusan ::.</option>';
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
