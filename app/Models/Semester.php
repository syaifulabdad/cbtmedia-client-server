<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Semester extends Model
{
    use HasFactory;
    protected $table = 'semester';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [];

    protected static function booted()
    {
        static::addGlobalScope('semester', function (Builder $builder) {
            // $builder->where('semester.sekolah_id', session('sekolah_id'));
            $builder->orderBy('semester.id', 'desc');
        });
    }


    function semester_string($id = null, $upper = false)
    {
        $data = $this->find($id);
        if ($data) {
            $semester = $data->tahun_ajaran . " " . ($data->semester == 1 ? "Ganjil" : "Genap");
            return $upper ? strtoupper($semester) : $semester;
        }
    }

    function selectFormInput($where = null)
    {
        $query = $this->orderBy('id', 'ASC');
        if ($where)
            $query->where($where);

        if (request()->ajax()) {
            $data = [];
            $data[null] = '.:: Semester ::.';
        } else {
            $data = '<option value="">.:: Semester ::.</option>';
        }

        foreach ($query->get() as $ref) {
            $id = $ref->id;
            $val = $ref->tahun_ajaran . " " . ($ref->semester == 1 ? "Ganjil" : "Genap");

            if (request()->ajax()) {
                $data[$id] =  $val;
            } else {
                $data .= '<option value="' . $id . '">' . $val . '</option>';
            }
        }
        return $data;
    }
}
