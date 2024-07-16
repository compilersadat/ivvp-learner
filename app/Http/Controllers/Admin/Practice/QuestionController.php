<?php
namespace App\Http\Controllers\Admin\Practice;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Illuminate\Support\Facades\Storage;
use App\Models\PracticeQuestion;

class QuestionController extends Controller{

    public function index(){
     $questions = PracticeQuestion :: all();
     return view('admin.practice.questions.index', compact('questions'));

    }
}