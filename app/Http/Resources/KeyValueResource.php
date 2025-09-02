<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class KeyValueResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'key'=>$this->key,
            'value'=>$this->value,
            'trans'=>$this->whenLoaded('translation',function(){
                $key=$this->translation->key;
                $file=$this->translation->file;
                return trans($file.'.'.$key);
            }),
        ];
    }
}
