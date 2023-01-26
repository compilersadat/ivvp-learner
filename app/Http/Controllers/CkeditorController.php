<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CkeditorController extends Controller
{

    public function index()
    {
        return view('admin.data.create');
    }

    public function editIndex()
    {
        return view('admin.data.edit');
    }


    public function upload(Request $request)
    {
        if($request->hasFile('upload')) {
            // $originName = $request->file('upload')->getClientOriginalName();
            // $fileName = pathinfo($originName, PATHINFO_FILENAME);
            // $extension = $request->file('upload')->getClientOriginalExtension();
            // $fileName = $fileName.'_'.time().'.'.$extension;

            // $request->file('upload')->move(public_path('images'), $fileName);
            $path = Storage::disk('s3')->put('questions', $request->upload);

            $CKEditorFuncNum = $request->input('CKEditorFuncNum');
            $url = env('S3_STORAGE_BASE_URL').$path; 
            $msg = 'Image uploaded successfully'; 
            $response = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$msg')</script>";

            @header('Content-type: text/html; charset=utf-8'); 
            echo $response;
        }
    }
}
