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
use App\Models\StudentResult;
use App\Models\Student;
use App\Models\AppUpdate;
class DataApiController extends ResponseController
{
    public function homeData(Request $request){
        $student=Student::where('id',$request->user()->id)->first();
        $slider=SlidersResource::collection(Slider::get());
        $data['sliders']=$slider;

        //check premium user.
        $student_pro=StudentPackage::where('student_id',$request->user()->id)->first();
        if($student_pro){
                if($student_pro->status==2){
                    $prime_content=Content::where('branch',$student->branch)->where('year',$student->year)->whereIn('month',$this->calculateRangeOfMonths($student_pro->start_month,$student_pro->number_of_months))->get();
                    $current_month_videos=Content::where('branch',$student->branch)->where('year',$student->year)->where('month',date('m'))->get();
                    $data['prime_content']=ContentResource::collection($prime_content);
                    $data['current_month_videos']=ContentResource::collection($current_month_videos);
                    $data['paid_plan']=(Object)new StudentSubscriptionResource($student_pro);
                    $data['is_prime']=true;
                    $data['study_materials']=[];
                    $data['free_content']=[];
                    $data['subscriptions']=[];
                    $data['month']=date('m');


                }
        }else{
            $free_content=Content::where('branch',$student->branch)->where('year',$student->year)->where(function($query){
                $query->where('type','free_pdf')->orWhere('type','free_video');
            })->get();
             $data['study_materials']=StudyMaterial::collection(Faculty::all());
             $data['free_content']=ContentResource::collection($free_content);   
             $data['is_prime']=false;
             $data['subscriptions']=PackageResource::collection(Package::all());

        }


         
        $success['message'] = "Here is data";
        $success['data']=$data;
        return $this->sendResponse($success);
    }

    public function primeContent(Request $request){
        $student=Student::where('id',$request->user()->id)->first();
        $student_pro=StudentPackage::where('student_id',$request->user()->id)->first();
        $prime_content=Content::where('branch',$student->branch)->where('year',$student->year)->whereIn('month',$this->calculateRangeOfMonths($student_pro->start_month,$student_pro->number_of_months))->get();
        $data['prime_content']=ContentResource::collection($prime_content);
        $success['message'] = "Here is data";
        $success['data']=$data;
        return $this->sendResponse($success);

    }

    public function fetchExams(Request $request){
        $student=Student::where('id',$request->user()->id)->first();
        $student_pro=StudentPackage::where('student_id',$request->user()->id)->first();
        if($student_pro){
                if($student_pro->status==2){
                    $exclude_exams=StudentResult::where('student_id',$request->user()->id)->where('status','completed')->pluck('exam_id');
                    $data['exams']=ExamResource::collection(Exam::where('branch',$student->branch)->where('year',$student->year)->whereNotIn('id',$exclude_exams)->get());
                    $success['message'] = "Here is data";
                    $success['data']=$data;
                    return $this->sendResponse($success);
                }
        }
       
        $success['message'] = "Here is data";
                    $data['exams']=[];
                    $success['data']=$data;
                    return $this->sendResponse($success);
    }

    public function calculateRangeOfMonths($start,$no_months){
        $range=array();
        $i=1;
        $next_month=$start;
        while($i<=$no_months){
            if($next_month==7 || $next_month==8){
                $no_months=$no_months+1;
            }
            if($next_month!=7 || $next_month!=8){

                if($next_month>12){
                    $p=0-(12-$next_month);
                    
                    array_push($range,$p);
                }else{
                    array_push($range,$next_month);
                }
                
            }
            $i++;
            $next_month=$next_month+1;
        }
        return $range;
    }

    public function appUpdate(Request $request){
        $appUpdate=AppUpdate::where('device',$request->device)->first();
        $data['is_update_required']=$appUpdate->version_number!=$request->version;
        $success['message'] = "Here is data";
        $success['data']=$data;
        return $this->sendResponse($success);
    }


}