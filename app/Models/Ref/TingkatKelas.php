<?php

namespace App\Models\Ref;

use App\Models\Sekolah;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TingkatKelas extends Model
{
    use HasFactory;
    protected $table = 'ref_tingkat_kelas';
    protected $guarded = [];

    protected static function booted()
    {
        static::addGlobalScope('jenjang', function (Builder $builder) {
            $sekolah = Sekolah::find(session('sekolah_id'));
            if ($sekolah)
                $builder->where("ref_tingkat_kelas." . (strtolower($sekolah->jenjang)), 1);
        });
    }

    public function selectFormInput()
    {
        $data = [];
        $data[null] = '.:: Tingkat Kelas ::.';
        foreach ($this->orderBy('id', 'ASC')->get() as $ref) {
            $data[$ref->id] = $ref->nama;
        }
        return $data;
    }
}
