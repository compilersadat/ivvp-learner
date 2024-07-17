<?php
namespace App\Http\Controllers\Admin\Practice;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Illuminate\Support\Facades\Storage;
use App\Models\PracticeQuestion;
use App\Models\Branch;
use App\Models\Subject;

class QuestionController extends Controller{

    public function index(){
     $questions = PracticeQuestion::where('wrtb','BRC1')->get();
     $branch = Branch::where('wrtf','FAC-1')->get();
     return view('admin.practice.questions.index', compact('questions','branch'));
    }

    public function filter(Request $request){
        $questions = PracticeQuestion::where('wrtb',$request->branch)->get();
        $branch = Branch::where('wrtf','FAC-1')->get();
        return view('admin.practice.questions.index', compact('questions','branch'));
    }

    public function create()
    {
        $branches = Branch::where('wrtf','FAC-1')->get();
        $branches_ids= Branch::where('wrtf','FAC-1')->pluck('branch_id');
        $subjects = Subject::whereIn('wrtb',$branches_ids)->get();
        return view('admin.practice.questions.create',compact('branches','subjects'));
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'question'=> 'required',
            'A' => 'required',
            'B' => 'required',
            'C' => 'required',
            'D' => 'required',
            
            'answer' => 'required',
           
        ]);
        
        $content = PracticeQuestion::create([
            'question' => isset($request->question) ? ($request->question) : '',
            'A' => isset($request->A) ? ($request->A) : '',
            'B' => isset($request->B) ? ($request->B) : '',
            'C' => isset($request->C) ? ($request->C) : '',
            'D' => isset($request->D) ? ($request->D) : '',
            'ans' => isset($request->answer) ? ($request->answer) : '',
            'wrtf' => isset($request->wrtf) ? ($request->wrtf) : 'FAC-1',
            'wrtb' => isset($request->wrtb) ? ($request->wrtb) : '',
            'wrts' => isset($request->wrts) ? ($request->wrts) : '',
        ]);
        session()->flash('status', 'Question added Successfully');
        return redirect()->back();
    }

    public function edit($id)
    {
        $branches = Branch::where('wrtf','FAC-1')->get();
        $branches_ids = Branch::where('wrtf','FAC-1')->pluck('branch_id');
        $subjects = Subject::whereIn('wrtb',$branches_ids)->get();
        $question = PracticeQuestion::where('id',$id)->first();
        return view('admin.practice.questions.edit',compact('branches','subjects','question'));
    }    

    public function update(Request $request, $id){
        $this->validate($request,[
            'question'=> 'required',
            'A' => 'required',
            'B' => 'required',
            'C' => 'required',
            'D' => 'required',
            
            'answer' => 'required',
           
        ]);
        $question = [
            'question' => isset($request->question) ? ($request->question) : '',
            'A' => isset($request->A) ? ($request->A) : '',
            'B' => isset($request->B) ? ($request->B) : '',
            'C' => isset($request->C) ? ($request->C) : '',
            'D' => isset($request->D) ? ($request->D) : '',
            'ans' => isset($request->answer) ? ($request->answer) : '',
            'wrtf' => isset($request->wrtf) ? ($request->wrtf) : 'FAC-1',
            'wrtb' => isset($request->wrtb) ? ($request->wrtb) : '',
            'wrts' => isset($request->wrts) ? ($request->wrts) : '',
        ];
        PracticeQuestion::where('id',$id)->first()->update($question);
        session()->flash('status', 'Question updated Successfully');
        return redirect()->back();
    }

}