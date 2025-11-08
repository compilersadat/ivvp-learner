<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StudyMaterialFolderResource extends JsonResource
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
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'faculty_id' => $this->faculty_id,
            'branch_id' => $this->branch_id,
            'year' => $this->year,
            'documents' => StudyMaterialDocumentResource::collection($this->whenLoaded('documents')),
        ];
    }
}
