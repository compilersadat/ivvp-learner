<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\TestSeriesTest;

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
            'tests' => $tests
        ];
    }
}