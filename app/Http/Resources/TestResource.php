<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\TestSeriesQuestion;


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
       return [
        "id" => $this->id,
        "title" => $this->title,
        "questions" => TestSeriesQuestion::where('test_id',$this->id)->count(),
        "marks" => TestSeriesQuestion::where('test_id',$this->id)->sum('marks')
       ];
    }
}