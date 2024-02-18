<?php

namespace App\Http\Controllers\Admin\TestSeries;

use App\Models\TestSeries;
use App\Models\TestSeriesTest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Illuminate\Support\Facades\Storage;


class TestSeriesController extends Controller
{
    public function index()
    {
        $tests=TestSeries::get();
        return view('admin.test_series.index', compact('tests'));
    }

    public function create()
    {
        return view('admin.test_series.create');
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
            'name'=> 'required',
            'price' => 'required'
        ]);
        
        $tests = TestSeries::create([
            'name' => isset($request->name) ? ($request->name) : '',
            'price' => isset($request->price) ? ($request->price) : 0,
        ]);
        session()->flash('status', 'Test Series Create Successfully');
        return redirect()->route('testseries.index');
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $test=TestSeries::where('id', $id)->first();
        return view('admin.test_series.edit', compact('test'));
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
            'name'=> 'required',
            'price' => 'required'
        ]);
        $content = [
            'name' => isset($request->name) ? ($request->name) : '',
            'price' => isset($request->price) ? ($request->price) : 0,
           ];

        TestSeries::where('id', $id)->first()->update($content);
        session()->flash('status', 'Test Series Update Successfully');
        return redirect()->route('testseries.index');
    }

    public function show($id)
    {
        $testseries=TestSeries::where('id', $id)->first();
        $tests=TestSeriesTest::where('testseries_id',$testseries->id)->get();
        return view('admin.test_series.view', compact('testseries','tests'));
    }
    public function delete($id){

        if(TestSeries::where('id',$id)->delete()){
            session()->flash('status', 'Test Series Deleted Successfully');
            return redirect()->route('testseries.index');
        }else{
            session()->flash('status', 'Test Series in Deleting Slider');
            return redirect()->route('testseries.index');
        }

}
public function changeStatus($id){
    $content=TestSeries::where('id', $id)->first();
    if($content->is_featured==0){
        $content->is_featured=1;
    }else{
        $content->is_featured=0;
    }
    if($content->update()){
        session()->flash('status', 'Test Series Update Successfully');
        return redirect()->back();

    }
   }

}