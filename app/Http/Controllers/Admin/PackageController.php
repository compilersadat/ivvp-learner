<?php

namespace App\Http\Controllers\Admin;

use App\Models\Package;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PackageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $datas=Package::all();
        return view('admin.packages.index', compact('datas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.packages.create');
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
            'number'=>'required',
            'price'=>'required',
            'month'=>'required'
        ]);
        $data=new Package();
        $data->name=$request->name;
        $data->number=$request->number;
        $data->price=$request->price;
        $data->description=$request->description;
        $data->month=$request->month;

        if($data->save()){
            return redirect()->route('packages.index')->with('success',' Data Added successfully.');
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
        $data=Package::where('id', $id)->first();
        return view('admin.packages.edit', compact('data'));
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
            'number'=>'required',
            'price'=>'required',
            'month'=>'required'

        ]);
        $data=Package::where('id', $id)->first();
        $data->name=$request->name;
        $data->number=$request->number;
        $data->price=$request->price;
        $data->description=$request->description;
        $data->month=$request->month;
        if($data->update()){
            return redirect()->route('packages.index')->with('success',' Data Update successfully.');
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

        if(Package::where('id',$id)->delete()){
            return redirect()->back()->with('success',' Data deleted successfully.');
        }
        else{
            return redirect()->back()->with('unsuccess','Failed try again.');
        }
    }
}
