<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Slider;
use App\Models\StudentResult;
use App\Models\Exam;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\ResponseController as ResponseController;
use App\Models\StudentAnswer;
use App\Models\StudentPackage;
use App\Http\Resources\ResultResource;


class ExamController extends ResponseController
{
    public function startExam(Request $request){
        $validator = Validator::make($request->all(), [
            'exam_id'=>'required',
        ]);
        if($validator->fails()){
            return $this->sendError($validator->errors());
        }
        $student_pro=StudentPackage::where('student_id',$request->user()->id)->first();
        if($student_pro){
            if($student_pro->status==2){
                $exam=Exam::where('id',$request->exam_id)->first();
                $student_result=StudentResult::where('exam_id',$exam->id)->where('student_id',$request->user()->id)->first();
                if($student_result){
                    $success['message'] = "Already Stared or Submitted.";
                    return $this->sendResponse($success);
                }else{
                    $result=new StudentResult();
                    $result->exam_id=$exam->id;
                    $result->student_id=$request->user()->id;
                    $result->status="started";
                    if($result->save()){
                        $success['message'] = "Exam Stared.";
                        return $this->sendResponse($success);   
                    }
                }
            }else{
                $success['message'] = "Please Subscribe.";
                return $this->sendResponse($success);  
            }
        }
    

    

    }

    public function submitExam(Request $request){
        $student_result=StudentResult::where('exam_id',$request->exam_id)->where('student_id',$request->user()->id)->first();
        foreach($request->answers as $answer){
            $answerDb=new StudentAnswer();
            $answerDb->question_id=$answer['question_id'];
            $answerDb->answer=$answer['answer'];
            $answerDb->student_id=$request->user()->id;
            $answerDb->exam_id=$request->exam_id;
            $answerDb->save();
        }
        $student_result->status="completed";
        if($student_result->update()){
            $success['message'] = "Exam Submitted.";
            return $this->sendResponse($success);   
        }

    }

    

    public function fetchResult(Request $request){
        $result=StudentResult::where('student_id',$request->user()->id)->where('status','completed')->get();
        $exams=ResultResource::collection($result);
        $data['exams']=$exams;
        $success['message'] = "Exam Submitted.";
        $success['data']=$data;
        return $this->sendResponse($success); 

        
    }

}