<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Question;
use App\Models\StudentAnswer;
use App\Models\Exam;
use App\Http\Resources\QuestionWithResource;


class ResultResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $exam=Exam::where('id',$this->exam_id)->first();

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
            'exam_title'=>$exam->title,
            'total_questions'=>$exam->no_questions,
            'total_marks'=>$exam->marks,
            'questions'=>QuestionWithResource::collection(Question::where('exam_id',$exam->id)->get()),


        ];
    }
}
