<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\TestSeriesQuestion;
use App\Models\TestSeriesStudentAttempt;

class TestResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $attempts = TestSeriesStudentAttempt::where('test_id',$this->id)->where('student_id',$request->user()->id)->count();
       return [
        "id" => $this->id,
        "title" => $this->title,
        "questions" => TestSeriesQuestion::where('test_id',$this->id)->count(),
        "marks" => TestSeriesQuestion::where('test_id',$this->id)->sum('marks'),
        "attempts" => $attempts
       ];
    }
}