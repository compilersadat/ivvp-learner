<?php

namespace App\Http\Controllers\Admin;

use App\Models\Content;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Illuminate\Support\Facades\Storage;


class ContentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $contents=Content::get();
        return view('admin.contents.index', compact('contents'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.contents.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'title'=> 'required',
            'description' => 'required',
            'file_url' => 'required',
            'type' => 'required',
            'branch' => 'required',
            'faculty' => 'required',
            'month' => 'required',
            'year' => 'required',
        ]);
        $path='';
        if(isset($request->thumbnail)){
            $path = Storage::disk('s3')->put('thumbnail', $request->thumbnail);
        }

        $content = Content::create([
            'title' => isset($request->title) ? ($request->title) : '',
            'description' => isset($request->description) ? ($request->description) : '',
            'file_url' => isset($request->file_url) ? ($request->file_url) : '',
            'type' => isset($request->type) ? ($request->type) : '',
            'branch' => isset($request->branch) ? ($request->branch) : '',
            'faculty' => isset($request->faculty) ? ($request->faculty) : '',
            'month' => isset($request->month) ? ($request->month) : '',
            'year' => isset($request->year) ? ($request->year) : '',
            'status' => 1,
            'barcode'=>isset($request->barcode) ? ($request->barcode) : '',
            'thumbnail'=>$path

        ]);
        session()->flash('status', 'Content Create Successfully');
        return redirect()->route('content.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $content=Content::where('id', $id)->first();
        return view('admin.contents.edit', compact('content'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'title'=> 'required',
            'description' => 'required',
            'file_url' => 'required',
            'type' => 'required',
            'branch' => 'required',
            'faculty' => 'required',
            'month' => 'required',
            'year' => 'required',
        ]);
        $path='';
        $cont=Content::where('id', $id)->first();
        if($request->thumbnail){
            if(isset($request->thumbnail)){
                $path = Storage::disk('s3')->put('thumbnail', $request->thumbnail);
            }  
        }
        $content = [
            'title' => isset($request->title) ? ($request->title) : '',
            'description' => isset($request->description) ? ($request->description) : '',
            'file_url' => isset($request->file_url) ? ($request->file_url) : '',
            'type' => isset($request->type) ? ($request->type) : '',
            'branch' => isset($request->branch) ? ($request->branch) : '',
            'faculty' => isset($request->faculty) ? ($request->faculty) : '',
            'month' => isset($request->month) ? ($request->month) : '',
            'year' => isset($request->year) ? ($request->year) : '',
            'status' => 1,
            'barcode'=>isset($request->barcode) ? ($request->barcode) : '',
            'thumbnail'=>$path

        ];

        Content::where('id', $id)->first()->update($content);
        session()->flash('status', 'Content Update Successfully');
        return redirect()->route('content.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id){

           if(Content::where('id',$id)->delete()){
               session()->flash('status', 'Content Deleted Successfully');
               return redirect()->route('content.index');
           }else{
               session()->flash('status', 'Content in Deleting Slider');
               return redirect()->route('content.index');
           }

   }
}
