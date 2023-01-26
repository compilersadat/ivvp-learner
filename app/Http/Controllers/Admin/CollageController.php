<?php

namespace App\Http\Controllers\Admin;

use App\Models\Collage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CollageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $datas=Collage::all();
        return view('admin.collages.index', compact('datas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.collages.create');
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
            'name'=>'required',
            'district'=>'required',
        ]);
        $data=new Collage();
        $data->name=$request->name;
        $data->district=$request->district;
      
        if($data->save()){
            return redirect()->route('collages.index')->with('success',' Data Added successfully.');
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
        $data=Collage::where('id', $id)->first();
        return view('admin.collages.edit', compact('data'));
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
            'name'=>'required',
            'district'=>'required',
        ]);
        $data=Collage::where('id', $id)->first();
        $data->name=$request->name;
        $data->district=$request->district;
        if($data->update()){
            return redirect()->route('collages.index')->with('success',' Data Update successfully.');
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

        if(Collage::where('id',$id)->delete()){
            return redirect()->back()->with('success',' Data deleted successfully.');
        }
        else{
            return redirect()->back()->with('unsuccess','Failed try again.');
        }
    }
}
