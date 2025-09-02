<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PropertyCategorisResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'name'=>$this->name,
            'trans'=>$this->whenLoaded('translation',function(){
                $key=$this->translation->key;
                $file=$this->translation->file;
                return trans($file.'.'.$key);
            }),
            'sub_categories'=>$this->whenLoaded('sub_categories', function () {
                return IdNameResource::collection($this->sub_categories->load('translation'));
            })
        ];
    }
}
