<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ComplaintResponseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if ($this->response_media){
            return [
                'id'=>$this->id,
                'text'=>$this->response_text,
                'medias'=>$this->when($this->response_media,function(){
                    return UrlResource::collection($this->response_media);
                })
            ];
        }
        return [
            'id'=>$this->id,
            'text'=>$this->response_text,
            'medias'=>[]
        ];
    }
}
