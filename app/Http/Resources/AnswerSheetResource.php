<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\QuestionWithAnswerResource;
use App\Models\TestSeriesStudentAttempt;

class AnswerSheetResource extends JsonResource
{
    public function toArray($request)
    {
        request()->add(['attempt'=>$this->id]);
        $marks=TestSeriesStudentAnswer::where('exam_id',$this->exam_id)->where('student_id',$request->user()->id)->where('attempt',$this->id)->sum('');

        return[
         'id' => $this->id,
         "questions" => QuestionWithAnswerResource::collection(TestSeriesQuestion::where('test_id',$this->test_id)->get()),
         "date" => date('d-m-yyyy',$this->created_at),
        ];
    }
}