<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PropertyFeatureResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->feature->id,
            'name'=>$this->feature->name,
            'trans'=>$this->when(true,function(){
                $key=$this->feature->translation->key;
                $file=$this->feature->translation->file;
                return trans($file.'.'.$key);
            }),
        ];
    }
}
