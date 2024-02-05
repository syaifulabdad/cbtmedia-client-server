<?php

namespace App\Models\Ref;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wilayah extends Model
{
    use HasFactory;
    protected $table = 'ref_wilayah';
    protected $primaryKey = 'kode_wilayah';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [];

    public function selectWilayah()
    {
        $level = request('level');
        $id = request('id');
        $parent_id = request('parent_id');
        $wil = null;

        if ($level == 'prov') {
            $level = 1;
            $wil = "Provinsi";
        }
        if ($level == 'kab') {
            $level = 2;
            $wil = "Kabupaten/Kota";
        }
        if ($level == 'kec') {
            $level = 3;
            $wil = "Kecamatan";
        }
        if ($level == 'kel') {
            $level = 4;
            $wil = "Desa/Kelurahan";
        }

        $builder = $this->orderBy('nama', 'ASC');
        $builder->where('expired_date', null);
        $builder->where('id_level_wilayah', $level);

        if (in_array($level, ['3', '4'])) {
            $builder->where('mst_kode_wilayah', $parent_id);
        } else {
            if ($parent_id)
                $builder->where('mst_kode_wilayah', $parent_id);
        }


        $data = '<option value="">Pilih ' . $wil . '</option>';
        foreach ($builder->get() as $ref) {
            $selected = trim($ref->{$this->primaryKey}) == $id ? 'selected' : null;
            $data .= '<option value="' . trim($ref->{$this->primaryKey}) . '" ' . $selected . '>' . trim($ref->nama) . '</option>' . "\n";
        }
        return $data;
    }

    public function selectProvinsi($parent_id = null, $id = null)
    {
        $data = [];
        $data[null] = 'Pilih Provinsi';

        $builder = $this->orderBy('nama', 'ASC');
        $builder->where(['id_level_wilayah' => 1, 'expired_date' => null]);
        if ($id)
            $builder->where('kode_wilayah', $id);
        if ($parent_id)
            $builder->where('mst_kode_wilayah', $parent_id);

        if (request('q')) {
            $builder->where("nama", 'LIKE', "%" . request("q") . "%");
        }

        foreach ($builder->get() as $ref) {
            $data[$ref->{$this->primaryKey}] = $ref->nama;
        }
        return $data;
    }

    public function selectKabupaten($parent_id = null, $id = null)
    {
        $builder = $this->orderBy('nama', 'ASC');
        $builder->where(['id_level_wilayah' => 2, 'expired_date' => null]);

        if ($id)
            $builder->where('kode_wilayah', $id);
        if ($parent_id)
            $builder->where('mst_kode_wilayah', $parent_id);

        if (request('q')) {
            $builder->where("nama", 'LIKE', "%" . request("q") . "%");
        }

        if (request()->ajax()) {
            $data = '<option value="">Pilih Kabupaten</option>';
            foreach ($builder->get() as $ref) {
                $data .= '<option value="' . $ref->{$this->primaryKey} . '">' . $ref->nama . '</option>';
            }
            return $data;
        } else {
            $data = [];
            $data[null] = 'Pilih Kabupaten';
            foreach ($builder->get() as $ref) {
                if (request()->ajax()) {
                } else {
                    $data[$ref->{$this->primaryKey}] = $ref->nama;
                }
            }
            return $data;
        }
    }

    public function selectKecamatan($parent_id = null, $id = null)
    {
        $builder = $this->orderBy('nama', 'ASC');
        $builder->where(['id_level_wilayah' => 3, 'expired_date' => null]);

        if ($id)
            $builder->where('kode_wilayah', $id);
        if ($parent_id)
            $builder->where('mst_kode_wilayah', $parent_id);

        if (request('q')) {
            $builder->where("nama", 'LIKE', "%" . request("q") . "%");
        }

        if (request()->ajax()) {
            $data = '<option value="">Pilih Kecamatan</option>';
            foreach ($builder->get() as $ref) {
                $data .= '<option value="' . $ref->{$this->primaryKey} . '">' . $ref->nama . '</option>';
            }
            return $data;
        } else {
            $data = [];
            $data[null] = 'Pilih Kecamatan';

            foreach ($builder->get() as $ref) {
                $Kab = $this->find($ref->mst_kode_wilayah);
                $data[$ref->{$this->primaryKey}] = $ref->nama . " " . $Kab['nama'];
            }
            return $data;
        }
    }

    public function selectKelurahan($parent_id = null, $id = null)
    {
        $builder = $this->orderBy('nama', 'ASC');
        $builder->where(['id_level_wilayah' => 4, 'expired_date' => null]);

        if ($id)
            $builder->where('kode_wilayah', $id);
        if ($parent_id)
            $builder->where('mst_kode_wilayah', $parent_id);

        if (request('q')) {
            $builder->where("nama", 'LIKE', "%" . request("q") . "%");
        }

        if (request()->ajax()) {
            $data = '<option value="">Pilih Desa/Kelurahan</option>';
            foreach ($builder->get() as $ref) {
                $data .= '<option value="' . $ref->{$this->primaryKey} . '">' . $ref->nama . '</option>';
            }
            return $data;
        } else {
            $data = [];
            $data[null] = 'Pilih Desa/Kelurahan';

            foreach ($builder->get() as $ref) {
                $Kec = $this->find($ref->mst_kode_wilayah);
                $data[$ref->{$this->primaryKey}] = $ref->nama . " " . $Kec['nama'];
            }
            return $data;
        }
    }
}
