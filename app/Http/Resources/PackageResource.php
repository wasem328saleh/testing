<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PackageResource extends JsonResource
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
            'title'=>$this->title,
            'trans'=>$this->whenLoaded('translation',function(){
                $key=$this->translation->key;
                $file=$this->translation->file;
                return trans($file.'.'.$key);
            }),
            'price_history'=>$this->price_history,
            'validity_period'=>$this->validity_period,
            'number_of_advertisements'=>$this->number_of_advertisements,
            'validity_period_per_advertisement'=>$this->validity_period_per_advertisement,
            'description'=>$this->description
        ];
    }
}
