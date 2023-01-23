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
use App\Models\Student;
class DataApiController extends ResponseController
{
    public function homeData(Request $request){
        $student=Student::where('id',$request->user()->id)->first();
        $slider=Slider::get();
         $free_content=Content::where('branch',$student->branch)->where('year',$student->year)->where(function($query){
            $query->where('type','free_pdf')->orWhere('type','free_video');
        })->get();
         $data['sliders']=$slider;
         $data['subscriptions']=PackageResource::collection(Package::all());
         $data['study_materials']=StudyMaterial::collection(Faculty::all());
         $data['free_content']=ContentResource::collection($free_content);
         if(StudentPackage::where('student_id',$request->user()->id)->count()){
            $data['paid_plan']=(Object)new StudentSubscriptionResource(StudentPackage::where('student_id',$request->user()->id)->first());
         }
        $success['message'] = "Here is data";
        $success['data']=$data;
        return $this->sendResponse($success);

    }
}
