<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\StatusUjian as Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class StatusUjianController extends Controller
{
    public function __construct()
    {
        $this->model = new Model;
        $this->primaryKey = (new Model)->getKeyName();
        $this->title = 'Status Ujian';
        $this->cUrl = url()->current();

        // data table
        $this->dataTableOrder = ['status desc'];

        $this->dataTable['dataCheck'] = ['label' => "<input type='checkbox' class='check-all' value='check-all'>", 'className' => 'text-center', 'width' => '10px'];
        $this->dataTable['status'] = [];
    }

    public function index(Request $request)
    {
        return view('status-ujian')->with([
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
        $datatables->addColumn('action', function ($row) {
            $btn = null;
            $btn .= '<a href="javascript:void(0)" class="btn btn-primary btn-sm m-1 btnEdit" data-id="' . $row->id . '"><i class="ri-pencil-fill"></i></a> ';
            $btn .= '<a href="javascript:void(0)" class="btn btn-danger btn-sm m-1 btnDelete" data-id="' . $row->id . '"><i class="ri-delete-bin-fill"></i></a> ';

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
        $formData['tahun_ajaran'] = ['validation' => 'required'];
        $formData['semester'] = [
            'type' => 'select',
            'options' => [1 => 'Ganji', 2 => "Genap"],
            'colWidth' => "col-md-6",
            'validation' => 'required'
        ];
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

    public function deleteDestroy(string $id)
    {
        if (is_array(request('id'))) {
            foreach (request('id') as $key => $value) {
                Model::find($value)->delete();
            }
        } else {
            Model::find($id)->delete();
        }
        return response()->json(['status' => TRUE]);
    }
}
