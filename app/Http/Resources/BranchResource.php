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
            'contents'=>(Array)[
                [
                    "month"=>"January",
                    "content"=>ContentResource::collection(Content::where('branch',$this->branch_id)->where("month","1")->get()),        
                ],
                [
                    "month"=>"Febuary",
                    "content"=>ContentResource::collection(Content::where('branch',$this->branch_id)->where("month","2")->get()),

                ],
                [
                   "month"=>"March",
                    "content"=>ContentResource::collection(Content::where('branch',$this->branch_id)->where("month","3")->get()),
                ],
                [
                    "month"=>"April",
                     "content"=>ContentResource::collection(Content::where('branch',$this->branch_id)->where("month","4")->get()),
                ],
                 [
                    "month"=>"May",
                     "content"=>ContentResource::collection(Content::where('branch',$this->branch_id)->where("month","5")->get()),
                 ],
                 [
                    "month"=>"June",
                     "content"=>ContentResource::collection(Content::where('branch',$this->branch_id)->where("month","6")->get()),
                 ],
                 [
                    "month"=>"July",
                     "content"=>ContentResource::collection(Content::where('branch',$this->branch_id)->where("month","7")->get()),
                 ],
                 [
                    "month"=>"August",
                     "content"=>ContentResource::collection(Content::where('branch',$this->branch_id)->where("month","8")->get()),
                 ],
                 [
                    "month"=>"September",
                     "content"=>ContentResource::collection(Content::where('branch',$this->branch_id)->where("month","9")->get()),
                 ],
                 [
                    "month"=>"October",
                     "content"=>ContentResource::collection(Content::where('branch',$this->branch_id)->where("month","10")->get()),
                 ],
                 [
                    "month"=>"November",
                     "content"=>ContentResource::collection(Content::where('branch',$this->branch_id)->where("month","11")->get()),
                 ],
                 [
                    "month"=>"December",
                     "content"=>ContentResource::collection(Content::where('branch',$this->branch_id)->where("month","12")->get()),
                 ],

            ]
        ];
    }
}
