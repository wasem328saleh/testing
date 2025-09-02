<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FeatureResource extends JsonResource
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
            'classification'=>$this->whenLoaded('classification', function () {
                return new IdNameResource($this->classification->load('translation'));
            }),
        ];
    }
}
