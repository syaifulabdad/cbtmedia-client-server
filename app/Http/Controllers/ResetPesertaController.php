<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Peserta as Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class ResetPesertaController extends Controller
{
    public function __construct()
    {
        $this->model = new Model;
        $this->primaryKey = (new Model)->getKeyName();
        $this->title = 'Reset Peserta';
        $this->cUrl = url()->current();

        // data table
        $this->dataTableOrder = ['terakhir_login desc', 'status desc'];

        $this->dataTable['dataCheck'] = ['label' => "<input type='checkbox' class='check-all' value='check-all'>", 'className' => 'text-center', 'width' => '10px'];
        $this->dataTable['nama'] = [];
        $this->dataTable['nis'] = ['label' => "NIS / NISN"];
        $this->dataTable['rombel'] = [];
        $this->dataTable['ruang'] = [];
        $this->dataTable['terakhir_login'] = ['orderable' => true, 'searchable' => true];
        $this->dataTable['status'] = ['orderable' => true, 'searchable' => true];
    }

    public function index(Request $request)
    {
        return view('reset-peserta')->with([
            'title' => $this->title,
            'cUrl' => $this->cUrl,
            'model' => $this->model,
            'dataTable' => $this->dataTable,
            'dataTableOrder' => $this->dataTableOrder,
            'dataTableFilter' => $this->dataTableFilter,
            'formData' => $this->formData(),
        ]);
    }

    public function dataTables(Request $request)
    {
        $builder = Model::select('*');
        $builder->where('status_login', 1);

        if (isset($this->dataTableFilter)) {
            foreach ($this->dataTableFilter as $key => $value) {
                $key2 = isset(explode('.', $key)[1]) ? explode('.', $key)[1] : $key;
                if (request()->has($key2)) {
                    $builder->where("$key", 'like', "%" . request("$key2") . "%");
                }
            }
        }

        $datatables = DataTables::of($builder)->smart(true)->addIndexColumn()
            ->rawColumns(['action', 'dataCheck']);

        $datatables->addColumn('dataCheck', function ($row) {
            return "<input type='checkbox' value='" . $row->id . "' name='id[]' class='data-check'>";
        });
        $datatables->editColumn('status', function ($row) {
            return $row->status ? "Aktif" : "-";
        });
        $datatables->editColumn('nis', function ($row) {
            $dt = $row->nis;
            $dt .= $row->nis && $row->nisn ? ' / ' . $row->nisn : $row->nisn;
            return $dt;
        });
        $datatables->addColumn('action', function ($row) {
            $btn = null;
            $btn .= '<a href="javascript:void(0)" class="btn btn-warning btn-sm m-1 btnReset" data-id="' . $row->id . '"><i class="ri-refresh-line"></i> Reset</a> ';

            return $btn;
        });
        return $datatables->make(true);
    }

    public function edit($id)
    {
        $getData = Model::find($id);
        return response()->json($getData);
    }

    private function formData()
    {
        $formData['status'] = [
            'type' => 'select',
            'options' => [
                '1' => "Aktif",
                '0' => "Non Aktif"
            ],
            'colWidth' => "col-md-4",
            'validation' => 'required'
        ];
        return $formData;
    }

    public function postStore(Request $request)
    {
        // variable validasi
        foreach ($this->formData() as $key => $value) {
            if (isset($value['validation']) && $value['validation']) {
                $validate[$key] = $value['validation'];
            }
        }

        if (isset($validate)) {
            $validator = Validator::make($request->all(), $validate);
            if ($validator->fails()) {
                return response()->json([
                    'inputerror' => $validator->errors()->keys(),
                    'error_string' => $validator->errors()->all()
                ]);
            }
        }

        // variable data
        foreach ($this->formData() as $key => $value) {
            if ($key == 'password') {
                if ($request->{$key})
                    $data[$key] = ($request->{$key});
            } else {
                $data[$key] = $request->{$key};
            }
        }

        if ($request->id) {
            Model::where('id', $request->id)->update($data);
        } else {
            $data['sekolah_id'] = session('sekolah_id');
            Model::create($data);
        }
        return response()->json(['status' => TRUE]);
    }

    public function postReset(string $id = null)
    {
        if (is_array(request('id'))) {
            foreach (request('id') as $key => $value) {
                Model::where('id', $value)->update(['status_login' => 0, 'ip_address' => null, 'login_uuid' => null]);
            }
        } else {
            Model::where('id', $id)->update(['status_login' => 0, 'ip_address' => null, 'login_uuid' => null]);
        }
        return response()->json(['status' => TRUE]);
    }
}
