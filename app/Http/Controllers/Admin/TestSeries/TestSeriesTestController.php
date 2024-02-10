<?php

namespace App\Http\Controllers\Admin\TestSeries;

use App\Models\TestSeriesTest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Illuminate\Support\Facades\Storage;
use App\Models\TestSeriesQuestion;


class TestSeriesTestController extends Controller
{
    public function index()
    {
        $tests=TestSeriesTest::get();
        return view('admin.test_series_test.index', compact('tests'));
    }

    public function create()
    {
        return view('admin.test_series_test.create');
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
            'test_series_id' => 'required'
        ]);
        
        $tests = TestSeriesTest::create([
            'title' => isset($request->title) ? ($request->title) : '',
            'testseries_id' => $request->test_series_id,
        ]);
        session()->flash('status', 'Test  Create Successfully');
        return redirect()->route('testseries.test.index');
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $test=TestSeriesTest::where('id', $id)->first();
        return view('admin.test_series_test.edit', compact('test'));
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
            'test_series_id' => 'required'
        ]);
        $content = [
            'title' => isset($request->title) ? ($request->title) : '',
            'testseries_id' => isset($request->test_series_id) ? ($request->test_series_id) : 0,
           ];

        TestSeriesTest::where('id', $id)->first()->update($content);
        session()->flash('status', 'Test Series Update Successfully');
        return redirect()->route('testseries.test.index');
    }

    public function show($id)
    {
        $test=TestSeriesTest::where('id', $id)->first();
        $questions=TestSeriesQuestion::where('test_id',$test->id)->get();
        return view('admin.test_series.test.view', compact('questions','test'));
    }
    public function delete($id){

        if(TestSeriesTest::where('id',$id)->delete()){
            session()->flash('status', 'Test Series Deleted Successfully');
            return redirect()->route('testseries.index');
        }else{
            session()->flash('status', 'Test Series in Deleting Slider');
            return redirect()->route('testseries.index');
        }

}
public function changeStatus($id){
    $content=TestSeriesTest::where('id', $id)->first();
    if($content->is_published==0){
        $content->is_published=1;
    }else{
        $content->is_published=0;
    }
    if($content->update()){
        session()->flash('status', 'Test Update Successfully');
        return redirect()->back();

    }
   }

}