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
class DataApiController extends ResponseController
{
    public function homeData(Request $request){

        $slider=Slider::get();
         $data['sliders']=$slider;
         $data['subscriptions']=PackageResource::collection(Package::all());
         $data['study_materials']=StudyMaterial::collection(Faculty::all());
         $data['paid_plan']=new StudentSubscriptionResource(StudentPackage::where('student_id',$request->user()->id)->first());
        $success['message'] = "Here is data";
        $success['data']=$data;
        return $this->sendResponse($success);

    }
}
