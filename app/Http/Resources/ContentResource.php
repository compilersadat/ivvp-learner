<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\S3upload;
class ContentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $cdn_url=substr_replace(S3upload::where('id',$this->file_url)->value("url"),"https://d2wjfv6ktdr3yg.cloudfront.net",0,47);
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
            "title"=>$this->title,
            "description"=>$this->description,
            "type"=>$this->type,
            "file_url"=>$cdn_url,
            "thumbnail"=>env('S3_STORAGE_BASE_URL').$this->thumbnail,
            "month"=>$months[$this->month-1]
        ];
    }
}
