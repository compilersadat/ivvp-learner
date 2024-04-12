<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\QuestionWithAnswerResource;
use App\Models\TestSeriesStudentAttempt;
use App\Models\TestSeriesQuestion;

class AnswerSheetResource extends JsonResource
{
    public function toArray($request)
    {
        $request->request->add(['attempt'=>$this->id]);
        return[
         'id' => $this->id,
         "questions" => QuestionWithAnswerResource::collection(TestSeriesQuestion::where('test_id',$this->test_id)->get()),
         "date" => date('d-m-y',strtotime($this->created_at)),
        ];
    }
}