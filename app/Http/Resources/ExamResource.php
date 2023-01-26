<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\QuestionResource;
use App\Models\Question;
class ExamResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'=>$this->id,
            'title'=>$this->title,
            'total_questions'=>$this->no_question,
            'marks'=>$this->marks,
            'month'=>$this->month,
            'questions'=>QuestionResource::collection(Question::where('exam_id',$this->id)->get()),
        ];
    }
}
