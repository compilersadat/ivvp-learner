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
            'content'=>[
                "January"=>ContentResource::collection(Content::where('branch',$this->branch_id)->where("month","1")->get()),
                "Febuary"=>ContentResource::collection(Content::where('branch',$this->branch_id)->where("month","2")->get()),
                "March"=>ContentResource::collection(Content::where('branch',$this->branch_id)->where("month","3")->get()),
                "April"=>ContentResource::collection(Content::where('branch',$this->branch_id)->where("month","4")->get()),
                "May"=>ContentResource::collection(Content::where('branch',$this->branch_id)->where("month","5")->get()),
                "June"=>ContentResource::collection(Content::where('branch',$this->branch_id)->where("month","6")->get()),
                "July"=>ContentResource::collection(Content::where('branch',$this->branch_id)->where("month","7")->get()),
                "August"=>ContentResource::collection(Content::where('branch',$this->branch_id)->where("month","8")->get()),
                "September"=>ContentResource::collection(Content::where('branch',$this->branch_id)->where("month","9")->get()),
                "October"=>ContentResource::collection(Content::where('branch',$this->branch_id)->where("month","10")->get()),
                "November"=>ContentResource::collection(Content::where('branch',$this->branch_id)->where("month","11")->get()),
                "December"=>ContentResource::collection(Content::where('branch',$this->branch_id)->where("month","12")->get())

            ]
        ];
    }
}
