<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\TestSeriesStudentAnswer;
class QuestionWithAnswerResource extends JsonResource
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
        $student_answer=TestSeriesStudentAnswer::where('exam_id',$this->test_id)->where('student_id',$request->user()->id)->where('question_id',$this->id)->where('attempt',$request->attempt)->first();

        return [
            'id'=>$this->id,
            'question'=>$this->question,
            'option1'=>$this->option1,
            'option2'=>$this->option2,
            'option3'=>$this->option3,
            'option4'=>$this->option4,
            'answer'=>$this->answer,
            'student_answer'=>$student_answer->answer==0?0:$ABCD[$student_answer->answer-1],
            'solution' => $this->solution,
            'is_right'=>$this->answer==$student_answer->answer
        ];
    }
}
