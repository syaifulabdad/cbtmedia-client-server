<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ApiToken as Model;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class ApiTokenController extends Controller
{
    public function __construct()
    {
        $this->model = new Model;
        $this->primaryKey = (new Model)->getKeyName();
        $this->title = 'API Token';
        $this->cUrl = url()->current();

        // data table
        $this->dataTableOrder = ['name desc'];

        $this->dataTable['user.email'] = ['orderable' => true, 'searchable' => true, 'width' => '350px'];
        $this->dataTable['name'] = ['orderable' => true, 'searchable' => true];
        // $this->dataTable['abilities'] = [];
        // $this->dataTable['expires_at'] = [];
    }

    public function index(Request $request)
    {
        return view('api-token')->with([
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
        $builder = Model::with('user');
        if (isset($this->dataTableFilter)) {
            foreach ($this->dataTableFilter as $key => $value) {
                $key2 = isset(explode('.', $key)[1]) ? explode('.', $key)[1] : $key;
                if (request()->has($key2)) {
                    $builder->where("$key", 'like', "%" . request("$key2") . "%");
                }
            }
        }
        $datatables = DataTables::of($builder)->smart(true)->addIndexColumn()
            ->rawColumns(['action', 'tanggal']);
        $datatables->editColumn('email', function ($row) {
            return $row->user->email;
        });
        $datatables->addColumn('status', function ($row) {
            return $row->status ? "Aktif" : "-";
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
        if (session('admin')) {
            $formData['user_id'] = [
                'label' => "User",
                'type' => 'select',
                'options' => (new User())->selectFormInput(['type' => 'admin']),
                'validation' => 'required'
            ];
        }
        $formData['name'] = ['validation' => 'required'];
        // $formData['expires_at'] = ['type' => 'date'];

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

        $user_id = session('user_id');
        if (session('admin')) {
            $user_id = $request->user_id;
        }

        if ($request->id) {
            Model::where('id', $request->id)->update(['name' => $request->name]);
            return response()->json(['status' => TRUE, 'message' => "Data berhasil diubah.!", 'token' => null]);
        } else {
            $dataUser = User::where('id', $user_id)->first();
            $token = $dataUser->createToken($request->name)->plainTextToken;
            return response()->json(['status' => TRUE, 'message' => "Token berhasil dibuat.!", 'token' => $token]);
        }
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
