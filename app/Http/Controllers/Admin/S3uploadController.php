<?php

namespace App\Http\Controllers\Admin;


use App\Models\S3upload;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Error;
use Illuminate\Support\Facades\Storage;

class S3uploadController extends Controller
{
    public function index()
    {
        $uploads=S3upload::get();
        return view('admin.uploads.index', compact('uploads'));
    }
    public function create()
    {
        return view('admin.uploads.create');
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'title'=> 'required',
            'type' => 'required',
        ]);
        $path="";
             if($request->type==="video"){
                // $path = Storage::disk('s3')->put('videos', $request->content);
                $path=$request->link;
             }else{
                $path = Storage::disk('s3')->put('pdf', $request->content);
             }


        // $path = Storage::url($path);
        /* Store $imageName name in DATABASE from HERE */

        $content = S3upload::create([
            'title' => isset($request->title) ? ($request->title) : '',
             'url'=>$path,
             'type'=>$request->type,
        ]);

        session()->flash('status', 'Content Uploaded Successfully');
        return redirect()->route('upload.index');
}


}
