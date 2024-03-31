<?php
namespace App\Http\Controllers\Api;

use App\Models\TestSeriesQuestion;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\ResponseController as ResponseController;
use App\Http\Resources\QuestionResource;

class TestSeriesController extends ResponseController
{
    public function fetchQuestion($id){
        $questions = QuestionResource::collection(TestSeriesQuestion::where('test_id',$id)->get());
        $success['message'] = "Please Subscribe.";
        $success['questions'] =  $questions;
        return $this->sendResponse($success);  
    }
}