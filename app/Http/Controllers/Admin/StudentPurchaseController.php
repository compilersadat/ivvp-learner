<?php

namespace App\Http\Controllers\Admin;

use App\StudentPurchase;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StudentPurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $students=StudentPurchase::all();
        return view('admin.student_purchase.index', compact('students'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.student_purchase.create');
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
            'date'=>'required',
            
            
            

        ]);
        $student=new StudentPurchase();
        $student->date=$request->date;
        if($student->save()){
            return redirect()->route('student-purchase.index')->with('success',' Student Purchase Added successfully.');
        }
        else{
            return redirect()->back()->with('unsuccess','Failed try again.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\StudentPurchase  $studentPurchase
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\StudentPurchase  $studentPurchase
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $student=StudentPurchase::where('id', $id)->first();
        return view('admin.student_purchase.edit', compact('student'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\StudentPurchase  $studentPurchase
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'date'=>'required',
            
            
            

        ]);
        $student=StudentPurchase::where('id', $id)->first();
        $student->date=$request->date;
        if($student->update()){
            return redirect()->route('student-purchase.index')->with('success',' Student Purchase Update successfully.');
        }
        else{
            return redirect()->back()->with('unsuccess','Failed try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\StudentPurchase  $studentPurchase
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $student=StudentPurchase::findOrFail($id);
        if($student::where('id',$id)->delete()){
            return redirect()->back()->with('success',' Student Purchase deleted successfully.');
        }
        else{
            return redirect()->back()->with('unsuccess','Failed try again.');
        }
    }
}
