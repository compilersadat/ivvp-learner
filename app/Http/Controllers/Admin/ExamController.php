<?php

namespace App\Http\Controllers\Admin;

use App\Models\Exam;
use App\Models\Question;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Illuminate\Support\Facades\Storage;


class ExamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $contents=Exam::get();
        return view('admin.exams.index', compact('contents'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.exams.create');
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
            'branch' => 'required',
            'faculty' => 'required',
            'month' => 'required',
            'year' => 'required',
            'is_published'=>'required',
            'no_questions' => 'required',
            'marks'=>'required'

        ]);
        
        $content = Exam::create([
            'title' => isset($request->title) ? ($request->title) : '',
            'branch' => isset($request->branch) ? ($request->branch) : '',
            'faculty' => isset($request->faculty) ? ($request->faculty) : '',
            'month' => isset($request->month) ? ($request->month) : '',
            'year' => isset($request->year) ? ($request->year) : '',
            'is_published' => isset($request->is_published) ? ($request->is_published) : '',
            'no_questions' => isset($request->no_questions) ? ($request->no_questions) : '',
            'marks' => isset($request->marks) ? ($request->marks) : '',

           
        ]);
        session()->flash('status', 'Content Create Successfully');
        return redirect()->route('exams.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $content=Exam::where('id', $id)->first();
        $questions=Question::where('exam_id',$content->id)->get();
        return view('admin.exams.view', compact('content','questions'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $content=Exam::where('id', $id)->first();
        return view('admin.exams.edit', compact('content'));
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
            'branch' => 'required',
            'faculty' => 'required',
            'month' => 'required',
            'year' => 'required',
            'is_published'=>'required',
            'no_questions' => 'required',
            'marks'=>'required'

        ]);
        $content = [
            'title' => isset($request->title) ? ($request->title) : '',
            'branch' => isset($request->branch) ? ($request->branch) : '',
            'faculty' => isset($request->faculty) ? ($request->faculty) : '',
            'month' => isset($request->month) ? ($request->month) : '',
            'year' => isset($request->year) ? ($request->year) : '',
            'is_published' => isset($request->is_published) ? ($request->is_published) : '',
            'no_questions' => isset($request->no_questions) ? ($request->no_questions) : '',
            'marks' => isset($request->marks) ? ($request->marks) : '',

           
        ];

        Exam::where('id', $id)->first()->update($content);
        session()->flash('status', 'Content Update Successfully');
        return redirect()->route('exams.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id){

           if(Exam::where('id',$id)->delete()){
               session()->flash('status', 'Content Deleted Successfully');
               return redirect()->route('content.index');
           }else{
               session()->flash('status', 'Content in Deleting Slider');
               return redirect()->route('content.index');
           }

   }
}
