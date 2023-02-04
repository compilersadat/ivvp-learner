<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\ResponseController as ResponseController;
use App\Models\Faculty;
use App\Http\Resources\StudyMaterial;
use App\Http\Resources\PackageResource;
use App\Http\Resources\StudentSubscriptionResource;
use App\Models\Package;
use App\Models\StudentPackage;
use App\Models\Content;
use App\Http\Resources\ContentResource;
use App\Http\Resources\SlidersResource;
use App\Http\Resources\ExamResource;
use App\Models\Exam;

use App\Models\Student;
class DataApiController extends ResponseController
{
    public function homeData(Request $request){
        $student=Student::where('id',$request->user()->id)->first();
        $slider=SlidersResource::collection(Slider::get());
        $data['sliders']=$slider;

        //check premium user.
        $student_pro=StudentPackage::where('student_id',$request->user()->id)->first();
        if($student_pro && $student_pro->status==2){
                
                $prime_content=Content::where('branch',$student->branch)->where('year',$student->year)->whereIn('month',$this->calculateRangeOfMonths($student_pro->start_month,$student_pro->number_of_months))->get();
                $data['prime_content']=ContentResource::collection($prime_content);
                $data['free_content']=[];
                $data['study_materials']=[];
                $data['subscriptions']=[];
                $data['paid_plan']=(Object)new StudentSubscriptionResource($student_pro);
                $data['is_prime']=true;
        }else{
            $free_content=Content::where('branch',$student->branch)->where('year',$student->year)->where(function($query){
                $query->where('type','free_pdf')->orWhere('type','free_video');
            })->get();
             $data['study_materials']=StudyMaterial::collection(Faculty::all());
             $data['free_content']=ContentResource::collection($free_content);   
             $data['is_prime']=false;
        }


         
        $success['message'] = "Here is data";
        $success['data']=$data;
        return $this->sendResponse($success);
    }

    public function fetchExams(Request $request){
    $student=Student::where('id',$request->user()->id)->first();
    $data['exams']=ExamResource::collection(Exam::where('branch',$student->branch)->where('year',$student->year)->get());
    $success['message'] = "Here is data";
    $success['data']=$data;
    return $this->sendResponse($success);
    }

    public function calculateRangeOfMonths($start,$no_months){
        $range=array();
        for($i=$start,$i<=$no_months;$i++){
            if($i>12){
                $p=sprintf("%u",(12-$i))
                array_push($p);
            }
            array_push($i);
        }
        return $range;
    }
}