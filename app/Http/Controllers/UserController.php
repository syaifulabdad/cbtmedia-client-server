<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Peserta;
use App\Models\Ptk;
use App\Models\Ujian;
use App\Models\User as Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public $user_type;

    public function __construct()
    {
        $this->model = new Model;
        $this->primaryKey = (new Model)->getKeyName();
        $this->title = 'Daftar User';
        $this->cUrl = url()->current();
        $this->user_type = explode('-', request()->segment(1))[1];

        // data table
        $this->dataTableOrder = ['last_login desc', 'name asc'];
    }

    private function generatePassword($length = 6)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyz0123456789abcdefghijklmnopqrstuvwxyz';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function index(Request $request)
    {

        $this->dataTable['name'] = ['orderable' => true, 'searchable' => true];
        $this->dataTable['username'] = ['label' => "Username", 'orderable' => true, 'searchable' => true];
        $this->dataTable['password'] = ['label' => "Password"];
        // $this->dataTable['google_id'] = ['label' => "Google Auth", 'width' => "100px", 'className' => "text-center"];
        $this->dataTable['type'] = ['orderable' => true, 'width' => "100px", 'className' => ""];
        $this->dataTable['status'] = ['orderable' => true, 'width' => "100px", 'className' => ""];
        $this->dataTable['last_login'] = ['orderable' => true,  'className' => "nowrap", 'width' => "150px"];

        return view('user')->with([
            'title' => $this->title,
            'cUrl' => $this->cUrl,
            'model' => $this->model,
            'dataTable' => $this->dataTable,
            'dataTableOrder' => $this->dataTableOrder,
            'dataTableFilter' => $this->dataTableFilter,
            'formData' => $this->formData(),
            'getUjian' => Ujian::orderBy('semester_id', 'desc')->where('status', 1)->get(),
            'user_type' => $this->user_type,
        ]);
    }

    public function dataTables(Request $request)
    {
        $builder = Model::select('*');
        $builder->whereNotIn('type', ['admin', 'ops']);
        if ($this->user_type)
            $builder->where('type', $this->user_type);

        if ($request->type == 'siswa')
            if ($request->ujian_id)
                $builder->whereRelation('peserta', 'ujian_id', $request->ujian_id);

        if (isset($this->dataTableFilter)) {
            foreach ($this->dataTableFilter as $key => $value) {
                $key2 = isset(explode('.', $key)[1]) ? explode('.', $key)[1] : $key;
                if (request()->has($key2)) {
                    $builder->where("$key", 'like', "%" . request("$key2") . "%");
                }
            }
        }
        $datatables = DataTables::of($builder)->smart(true)->addIndexColumn()
            ->rawColumns(['action', 'google_id', 'status']);

        $datatables->editColumn('email', function ($row) {
            return $row->email ?? $row->username;
        });
        $datatables->addColumn('password', function ($row) {
            return $row->peserta ? $row->peserta->password : null;
        });
        $datatables->addColumn('type', function ($row) {
            return strtoupper(str_replace('_', ' ', $row->type));
        });
        $datatables->addColumn('status', function ($row) {
            return strtoupper(str_replace('_', ' ', $row->status));
        });
        $datatables->addColumn('google_id', function ($row) {
            $data = null;
            $data .= '<div class="form-check form-check-info form-switch form-switch-lg text-center" style="margin-left: 20px">
                <input type="checkbox" class="form-check-input ml-3"' . ($row->google_id ? 'checked' : null) . ' disabled>
                </div>';
            return $data;
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
        $getData->password = null;
        return response()->json($getData);
    }

    private function formData()
    {
        $formData['name'] = ['validation' => 'required'];
        if ($this->user_type == 'ptk') {
            $formData['email'] = [];
        }
        if (in_array($this->user_type, ['siswa', 'pengawas'])) {
            $formData['username'] = [];
        }
        $formData['password'] = [];
        $formData['status'] = [
            'type' => 'select',
            'options' => [
                'active' => "Aktif",
                'pending' => "Pending",
                'non-active' => "Non Aktif",
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

        if ($request->email == null && $request->username == null) {
            $validate['username'] = 'required';
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
                    $data[$key] = bcrypt($request->{$key});
            } else {
                $data[$key] = $request->{$key};
            }
        }

        if ($request->id) {
            Model::where('id', $request->id)->update($data);
        } else {
            $data['type'] = $this->user_type;
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

    public function postGenerateSiswa(Request $request)
    {
        if ($this->user_type == 'siswa') {
            $ujian_id = $request->ujian_id;
            $getPeserta = Peserta::where('ujian_id', $ujian_id)->get();
            foreach ($getPeserta as $peserta) {
                $username = $peserta->username;
                if ($peserta->username && $peserta->password) {
                    $data['name'] =  strtoupper($peserta->nama);
                    $data['email'] = $username;
                    $data['username'] = $username;
                    $data['status'] = $peserta->status == 1 ? 'active' : null;

                    $cekUser = Model::where('peserta_id', $peserta->id)->first();
                    if ($cekUser) {
                        $cekUser->update($data);
                    } else {
                        $data['id'] = $peserta->id;
                        $data['sekolah_id'] = session('sekolah_id');
                        $data['peserta_id'] = $peserta->id;

                        $password = $peserta->password;
                        $data['password'] = bcrypt($password);
                        $data['type'] = 'siswa';
                        Model::create($data);
                    }
                }
            }
            return response()->json(['status' => TRUE]);
        }
    }
}
