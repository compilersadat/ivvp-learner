<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\ResponseController as ResponseController;

class DataApiController extends ResponseController
{
    public function homeData(){

        $slider=Slider::get();
         $data['sliders']=$slider;
         $data['subscriptions']=[];
         $data['study_materials']=[];
        $success['message'] = "Here is data";
        $success['data']=$data;
        return $this->sendResponse($success);

    }
}
