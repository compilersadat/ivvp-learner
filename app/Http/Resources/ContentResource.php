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
        return [
            "title"=>$this->title,
            "description"=>$this->description,
            "type"=>$this->type,
            "file_url"=>S3upload::where('id',$this->file_url)->first()->value("url"),
            "thumbnail"=>env('S3_STORAGE_BASE_URL').$this->thumbnail

        ];
    }
}
