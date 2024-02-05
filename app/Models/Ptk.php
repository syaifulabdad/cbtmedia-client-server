<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Ptk extends Model
{
    use HasFactory, HasUuids, SoftDeletes;
    protected $table = 'ptk';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [];

    protected static function booted()
    {
        static::addGlobalScope('ptk', function (Builder $builder) {
            // $builder->where('ptk.sekolah_id', session('sekolah_id'));
            if (session('ptk_id'))
                $builder->where('ptk.id', session('ptk_id'));
        });
    }

    function selectFormInput($where = null)
    {
        $query = $this->orderBy('id', 'ASC');
        if ($where)
            $query->where($where);

        if (request()->ajax()) {
            $data = [];
            $data[null] = '.:: Nama Guru ::.';
        } else {
            $data = '<option value="">.:: Nama Guru ::.</option>';
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
