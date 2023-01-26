<?php

namespace App\Http\Controllers\Admin;

use App\Models\Exam;
use App\Models\Question;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Illuminate\Support\Facades\Storage;


class QuestionController extends Controller
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
            'question'=> 'required',
            'option1' => 'required',
            'option2' => 'required',
            'option3' => 'required',
            'option4' => 'required',
            'answer' => 'required',

        ]);
        
        $content = Question::create([
            'question' => isset($request->question) ? ($request->question) : '',
            'option1' => isset($request->option1) ? ($request->option1) : '',
            'option2' => isset($request->option2) ? ($request->option2) : '',
            'option3' => isset($request->option3) ? ($request->option3) : '',
            'option4' => isset($request->option4) ? ($request->option4) : '',
            'exam_id' => isset($request->exam) ? ($request->exam) : '',
            'answer' => isset($request->answer) ? ($request->answer) : '',
            
           
        ]);
        session()->flash('status', 'Content Create Successfully');
        return redirect()->back();
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
        $content=Question::where('id', $id)->first();
        return view('admin.questions.edit', compact('content'));
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
            'question'=> 'required',
            'option1' => 'required',
            'option2' => 'required',
            'option3' => 'required',
            'option4' => 'required',
            'answer' => 'required',

        ]);
        $content = [
            'question' => isset($request->question) ? ($request->question) : '',
            'option1' => isset($request->option1) ? ($request->option1) : '',
            'option2' => isset($request->option2) ? ($request->option2) : '',
            'option3' => isset($request->option3) ? ($request->option3) : '',
            'option4' => isset($request->option4) ? ($request->option4) : '',
            'exam_id' => isset($request->exam) ? ($request->exam) : '',
            'answer' => isset($request->answer) ? ($request->answer) : '',
        ];
        Question::where('id', $id)->first()->update($content);
        session()->flash('status', 'Content Update Successfully');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id){

           if(Question::where('id',$id)->delete()){
               session()->flash('status', 'Content Deleted Successfully');
               return redirect()->route('content.index');
           }else{
               session()->flash('status', 'Content in Deleting Slider');
               return redirect()->route('content.index');
           }

   }
}
