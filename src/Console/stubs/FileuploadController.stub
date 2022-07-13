<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FileUploadController extends Controller
{

    public function index(Request $request)
    {
        $image     = $request->file('upload');
        $name      = substr(str_shuffle('1234567890abcdefghijklmnopqrstuvwxyz'), 0, 12);
        $extension = $image->getClientOriginalExtension();
        $newname   = $name . '.' . $extension;

        // you can change third variable as filesystem you want.
        // $image->storeAs(storage_path(), $newname, 'public');
        // $url = public_path().$newname;
        $image->storeAs('/admin/image/', $newname, 'public');
        $url = asset("/storage/admin/image/" .$newname);

        $param = [
            'uploaded' => 1,
            'fileName' => $newname,
            'url'      => $url
        ];
        return response()->json($param, 200);
    }
}
