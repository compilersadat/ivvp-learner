<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\QuestionWithAnswerResource;
use App\Models\TestSeriesStudentAttempt;

class AnswerSheetResource extends JsonResource
{
    public function toArray($request)
    {
        return[
         'id' => $this->id,
         "questions" => QuestionWithAnswerResource::collection(TestSeriesQuestion::where('test_id',$this->test_id)->get())->attempt($this->id),
         "date" => date('d-m-yyyy',$this->created_at),
        ];
    }
}