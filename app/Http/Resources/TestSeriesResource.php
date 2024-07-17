<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\TestSeriesTest;
use App\Http\Resources\TestResource;
use App\Models\TestSeriesSection;
class TestSeriesResource extends JsonResource
{
     /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $tests = TestSeriesTest::where('testseries_id',$this->id)->where('is_published',1)->get();
        return [
            'id'=>$this->id,
            'name'=>$this->name,
            'number_of_tests' => $tests->count(),
            'tests' => TestResource::collection($tests),
            'section_count' => TestSeriesSection::where('test_series_id',$this->id)->count(),
            'sections' => TestSeriesSection::where('test_series_id',$this->id)->pluck('name')
        ];
    }
}