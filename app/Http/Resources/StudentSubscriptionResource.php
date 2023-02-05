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
        $months = [
            'January',
            'February',
            'March',
            'April',
            'May',
            'June',
            'July',
            'August',
            'September',
            'October',
            'November',
            'December'
            ];
       
        return [
            "status"=>$this->status==1?"Pending":"Active",
           "package_name"=>$this->package_name,
           "number_of_months"=>$this->number_of_months,
           "start_date"=>$this->start_date,
           "start_month"=>$this->start_month,
           "start_month_name"=>implode(" ",$this->calculateRangeOfMonths($this->start_month,$this->number_of_months)),
           "price"=>$this->price

        ];
    }

    public function calculateRangeOfMonths($start,$no_months){
        $months = [
            'January',
            'February',
            'March',
            'April',
            'May',
            'June',
            'July',
            'August',
            'September',
            'October',
            'November',
            'December'
            ];
        $range=array();
        $i=1;
        $next_month=$start;
        while($i<=$no_months){
            if($next_month>12){
                $p=0-(12-$next_month);
                array_push($range,$months[$p]);
            }else{
                array_push($range,$months[$next_month]);
            }
            $i++;
            $next_month=$next_month+1;
        }
        return $range;
    }
}
