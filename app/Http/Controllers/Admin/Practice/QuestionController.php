<?php
namespace App\Http\Controllers\Admin\Practice;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Illuminate\Support\Facades\Storage;
use App\Models\PracticeQuestion;
use App\Models\Branch;

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
}