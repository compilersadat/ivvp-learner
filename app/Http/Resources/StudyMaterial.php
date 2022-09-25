<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\BranchResource;
use App\Models\Branch;
class StudyMaterial extends JsonResource
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
            'number'=>$this->number,
            'faculty_id'=>$this->faculty_id,
            'name'=>$this->name,
            'duration'=>$this->duration,
            'branches'=>BranchResource::collection(Branch::where('wrtf',$this->faculty_id)->get()),
        ];
    }
}
