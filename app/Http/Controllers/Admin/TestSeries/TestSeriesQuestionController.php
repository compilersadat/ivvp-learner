<?php

namespace App\Http\Controllers\Admin\TestSeries;

use App\Models\Exam;
use App\Models\TestSeriesQuestion;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Illuminate\Support\Facades\Storage;


class TestSeriesQuestionController extends Controller
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
            'option5' => 'required',
            'solution' => 'required',
            'answer' => 'required',
            'marks'=>'required',
            'negative_marks'=>'required',

        ]);
        
        $content = TestSeriesQuestion::create([
            'question' => isset($request->question) ? ($request->question) : '',
            'option1' => isset($request->option1) ? ($request->option1) : '',
            'option2' => isset($request->option2) ? ($request->option2) : '',
            'option3' => isset($request->option3) ? ($request->option3) : '',
            'option4' => isset($request->option4) ? ($request->option4) : '',
            'option5' => isset($request->option5) ? ($request->option5) : '',
            'solution' => isset($request->solution) ? ($request->solution) : '',
            'test_id' => isset($request->exam) ? ($request->exam) : '',
            'answer' => isset($request->answer) ? ($request->answer) : '',
            'marks' => isset($request->marks) ? ($request->marks) : 0,
            'negative_marks' => isset($request->negative_marks) ? ($request->negative_marks) : 0,
            'section_id' => isset($request->section_id) ? ($request->section_id) : 0

        ]);
        session()->flash('status', 'Question added Successfully');
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
        $content=TestSeriesQuestion::where('id', $id)->first();
        $sections = TestSeriesSection::where('test_series_id',$test->test_seriesid)->get();

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
            'marks'=>'required',
            'negative_marks'=>'required',

        ]);
        $content = [
            'question' => isset($request->question) ? ($request->question) : '',
            'option1' => isset($request->option1) ? ($request->option1) : '',
            'option2' => isset($request->option2) ? ($request->option2) : '',
            'option3' => isset($request->option3) ? ($request->option3) : '',
            'option4' => isset($request->option4) ? ($request->option4) : '',
            'option5' => isset($request->option5) ? ($request->option5) : '',
            'solution' => isset($request->solution) ? ($request->solution) : '',
            'test_id' => isset($request->exam) ? ($request->exam) : '',
            'answer' => isset($request->answer) ? ($request->answer) : '',
            'marks' => isset($request->marks) ? ($request->marks) : 0,
            'negative_marks' => isset($request->negative_marks) ? ($request->negative_marks) : 0,
            'section_id' => isset($request->section_id) ? ($request->section_id) : 0

        ];
        TestSeriesQuestion::where('id', $id)->first()->update($content);
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

           if(TestSeriesQuestion::where('id',$id)->delete()){
               session()->flash('status', 'Content Deleted Successfully');
               return redirect()->route('content.index');
           }else{
               session()->flash('status', 'Content in Deleting Slider');
               return redirect()->route('admin.test_series_test.view');
           }

   }
}
