<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'country'=>new IdNameResource($this->city()->first()->country->load('translation')),
            'city'=>new IdNameResource($this->city->load('translation')),
            'region'=>[
                'id'=>$this->id,
                'name'=>$this->name,
                'trans'=>$this->whenLoaded('translation',function(){
                    $key=$this->translation->key;
                    $file=$this->translation->file;
                    return trans($file.'.'.$key);
                })
            ]
        ];
    }
}
