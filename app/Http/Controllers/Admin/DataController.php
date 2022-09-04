<?php

namespace App\Http\Controllers\Admin;

use App\Data;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DataController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $datas=Data::all();
        return view('admin.datas.index', compact('datas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.datas.create');
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
            'chapter_id'=>'required',
            'page_no'=>'required',
            'data'=>'required',

            
            

        ]);
        $data=new Data();
        $data->chapter_id=$request->chapter_id;
        $data->page_no=$request->page_no;
        $data->data=$request->data;
       
        if($data->save()){
            return redirect()->route('data.index')->with('success',' Data Added successfully.');
        }
        else{
            return redirect()->back()->with('unsuccess','Failed try again.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Data  $data
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Data  $data
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data=Data::where('id', $id)->first();
        return view('admin.datas.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Data  $data
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'chapter_id'=>'required',
            'page_no'=>'required',
            'data'=>'required',

            
            

        ]);
        $data=Data::where('id', $id)->first();
        $data->chapter_id=$request->chapter_id;
        $data->page_no=$request->page_no;
        $data->data=$request->data;
       
        if($data->update()){
            return redirect()->route('data.index')->with('success',' Data Update successfully.');
        }
        else{
            return redirect()->back()->with('unsuccess','Failed try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Data  $data
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $data=Data::findOrFail($id);
        if($data::where('id',$id)->delete()){
            return redirect()->back()->with('success',' Data deleted successfully.');
        }
        else{
            return redirect()->back()->with('unsuccess','Failed try again.');
        }
    }
}
