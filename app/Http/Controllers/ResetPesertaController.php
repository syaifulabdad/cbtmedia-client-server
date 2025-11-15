<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Ruang;
use App\Models\User as Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class ResetPesertaController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new Model;
        $this->primaryKey = (new Model)->getKeyName();
        $this->title = 'Reset Peserta';
        $this->cUrl = url()->current();

        // data table
        $this->dataTableOrder = ['last_login desc', 'status_login desc'];

        $this->dataTable['dataCheck'] = ['label' => "<input type='checkbox' class='check-all' value='check-all'>", 'className' => 'text-center', 'width' => '10px'];
        $this->dataTable['name'] = [];
        // $this->dataTable['username'] = ['label' => "NIS / NISN"];
        $this->dataTable['peserta.nama_rombel'] = ['label' => "Rombel", 'orderable' => true, 'searchable' => true];
        $this->dataTable['peserta.nama_ruang'] = ['label' => "Ruang", 'orderable' => true, 'searchable' => true];
        $this->dataTable['last_login'] = ['orderable' => true, 'searchable' => true];
        $this->dataTable['status_login'] = ['orderable' => true, 'searchable' => true, 'width' => '100px'];
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
            'getRuang' => Ruang::orderBy('nama', 'asc')->get(),
        ]);
    }

    public function dataTables(Request $request)
    {
        $builder = Model::select('*')->with(['peserta']);
        $builder->where('status_login', 1);

        if ($request->ruang_id)
            $builder->whereRelation('peserta', 'ruang_id', $request->ruang_id);


        $datatables = DataTables::of($builder)->smart(true)->addIndexColumn()
            ->rawColumns(['action', 'dataCheck']);

        $datatables->addColumn('dataCheck', function ($row) {
            return "<input type='checkbox' value='" . $row->id . "' name='id[]' class='data-check'>";
        });
        $datatables->editColumn('status_login', function ($row) {
            return $row->status_login ? "Login" : "-";
        });
        $datatables->addColumn('nama_rombel', function ($row) {
            return $row->peserta ? $row->peserta->nama_rombel : null;
        });
        $datatables->addColumn('nama_ruang', function ($row) {
            return $row->peserta ? $row->peserta->nama_ruang : null;
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
