<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StudentSubscriptionResource extends JsonResource
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
            "id"=>$this->id,
            "status"=>$this->status==1?"Pending":"Active",
           "package_name"=>$this->package_name,
           "number_of_months"=>$this->number_of_months,
           "start_date"=>$this->start_date,
           "start_month"=>$this->start_month,
           "price"=>$this->price

        ];
    }
}
