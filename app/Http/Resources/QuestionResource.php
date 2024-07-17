<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\TestSeriesSection;

class QuestionResource extends JsonResource
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
            'question'=>$this->question,
            'option1'=>$this->option1,
            'option2'=>$this->option2,
            'option3'=>$this->option3,
            'option4'=>$this->option4,
            'setion' => TestSeriesSection::where('id',$this->test_series_section_id)->value('name')
        ];
    }
}
