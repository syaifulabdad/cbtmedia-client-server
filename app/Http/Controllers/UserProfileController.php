<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User as Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserProfileController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new Model;
        $this->primaryKey = $this->model->primaryKey;
        $this->title = 'Profile';
        $this->cUrl = url()->current();
    }

    public function index()
    {
        $user = Model::find(session('user_id'));
        return view('user-profile')->with([
            'title' => $this->title,
            'cUrl' => $this->cUrl,
            'model' => $this->model,
            'user' => $user,
        ]);
    }

    public function update(Request $request)
    {
        // variable validasi
        $validate['name'] = 'required';

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
        $data['name'] = $request->name;
        $data['address'] = $request->address;
        $data['phone_number'] = $request->phone_number;
        $data['whatsapp_number'] = $request->whatsapp_number;

        Model::where('id', session('user_id'))->update($data);
        return response()->json(['status' => TRUE]);
    }

    public function updatePass(Request $request)
    {
        if ($request->password != $request->password2)
            return response()->json(['status' => FALSE, 'message' => "Konfirmasi Password tidak sesuai.!!"]);

        if (!$request->password)
            return response()->json(['status' => FALSE, 'message' => "Tidak ada perubahan .!!"]);


        if ($request->password && $request->password2) {
            $data['password'] = bcrypt($request->password);
            Model::where('id', session('user_id'))->update($data);
            return response()->json(['status' => TRUE]);
        }
    }


    public function uploadFile(Request $request)
    {
        $data = array();
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:png,jpg,jpeg|max:500'
        ]);

        if ($validator->fails()) {
            $data['success'] = false;
            $data['error'] = $validator->errors()->first('file'); // Error response 
        } else {
            $user = Model::find(session('user_id'));

            if ($request->file('file')) {
                $file = $request->file('file');
                $filename = time() . '_' . $file->getClientOriginalName();

                // File extension
                $extension = $file->getClientOriginalExtension();

                // File upload location
                $location = 'storage/app/sekolah/' . $user->sekolah_id . '/users';

                if (!file_exists($location))
                    mkdir($location, 0777, true);

                if (session('user_image') && file_exists($location . "/" . session('user_image')))
                    unlink($location . "/" . session('user_image'));

                // Upload file
                // $result = Storage::disk('s3')->putFileAs($location, $file, $filename, 'public');
                $file->move($location, $filename);


                // File path
                $filepath = url($location . $filename);
                Model::where('id', session('user_id'))->update([
                    'avatar' => $filename
                ]);
                session(['user_image' => $filename]);

                // Response
                $data['success'] = true;
                $data['message'] = 'Uploaded Successfully!';
                $data['filepath'] = $filepath;
                $data['extension'] = $extension;
            } else {
                // Response
                $data['success'] = false;
                $data['message'] = 'File not uploaded.';
            }
        }

        return response()->json($data);
    }
}
