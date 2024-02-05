<?php

namespace App\Models\Ref;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenjangPendidikan extends Model
{
    use HasFactory;
    protected $table = 'ref_jenjang_pendidikan';
    protected $guarded = [];

    public function selectFormInput($where = null, $whereIn = null)
    {
        $data = [];
        $data[null] = '.:: Jenjang Pendidikan ::.';
        $query = $this->where('deleted_at', null)->orderBy('id', 'ASC');
        if ($where)
            $query->where($where);
        if ($whereIn)
            $query->whereIn('id', array_values($whereIn));
        foreach ($query->get() as $ref) {
            $data[$ref->id] = $ref->nama;
        }
        return $data;
    }
}
