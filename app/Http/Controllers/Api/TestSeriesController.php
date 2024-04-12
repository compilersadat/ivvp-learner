<?php
namespace App\Http\Controllers\Api;

use App\Models\TestSeriesQuestion;
use App\Models\TestSeriesStudentAttempt;
use App\Models\TestSeriesStudentAnswer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\ResponseController as ResponseController;
use App\Http\Resources\QuestionResource;

class TestSeriesController extends ResponseController
{
    public function fetchQuestion($id){
        $questions = QuestionResource::collection(TestSeriesQuestion::where('test_id',$id)->get());

        $success['message'] = "Please Subscribe.";
        $success['test_id'] = $id;
        $success['questions'] =  $questions;
        return $this->sendResponse($success);  
    }

    public function submitExam(Request $request){
        $student_attempt=TestSeriesStudentAttempt::where('test_id',$request->exam_id)->where('student_id',$request->user()->id)->count();
        foreach($request->answers as $answer){
            $answerDb=new TestSeriesStudentAnswer();
            $answerDb->question_id=$answer['question_id'];
            $answerDb->answer=$answer['answer'];
            $answerDb->student_id=$request->user()->id;
            $answerDb->exam_id=$request->exam_id;
            $answerDb->save();
        }
        $attempt = new TestSeriesStudentAttempt();
        $attempt->student_id = $request->user()->id;
        $attempt->test_id= $request->exam_id;
        $attempt->attempt= $student_attempt++;
        if($student_result->save()){
            $success['message'] = "Exam Submitted.";
            return $this->sendResponse($success);   
        }

    }
}