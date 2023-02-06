<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\StudentAnswer;
class QuestionWithResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */

    public function toArray($request)
    {
        $ABCD=["A","B","C","D"];
        $student_answer=StudentAnswer::where('exam_id',$this->exam_id)->where('student_id',$request->user()->id)->where('question_id',$this->id)->first();

        return [
            'id'=>$this->id,
            'question'=>$this->question,
            'option1'=>$this->option1,
            'option2'=>$this->option2,
            'option3'=>$this->option3,
            'option4'=>$this->option4,
            'answer'=>$this->answer,
            'student_answer'=>$ABCD[$student_answer->answer-1],
            'is_right'=>$this->answer==$student_answer->answer
        ];
    }
}
