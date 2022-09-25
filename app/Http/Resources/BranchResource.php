<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ContentResource;
use App\Models\Content;
class BranchResource extends JsonResource
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
            'branch_id'=>$this->branch_id,
            'name'=>$this->name,
            'content'=>ContentResource::collection(Content::where('branch',$this->branch_id)->get())
        ];
    }
}
