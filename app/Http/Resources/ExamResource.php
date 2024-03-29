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
        $months = [
            'January',
            'February',
            'March',
            'April',
            'May',
            'June',
            'July',
            'August',
            'September',
            'October',
            'November',
            'December'
            ];
        return [
            'id'=>$this->id,
            'title'=>$this->title,
            'total_questions'=>$this->no_questions,
            'marks'=>$this->marks,
            'month'=>$months[$this->month-1],
            'questions'=>QuestionResource::collection(Question::where('exam_id',$this->id)->get()),
            'is_active'=>$this->is_published==1
        ];
    }
}
