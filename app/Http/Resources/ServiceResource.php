<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=> $this->id ,
            'name'=> $this->name,
            'trans'=>$this->whenLoaded('translation',function(){
                $key=$this->translation->key;
                $file=$this->translation->file;
                return trans($file.'.'.$key);
            }),
            'image_url'=>url($this->image_url),
            'providers'=>$this->whenLoaded('service_providers',function (){
                if ($this->service_providers()->where('status','accept')
                    ->whereHas('order',function($q){
                        $q->where('status','posted');
                    })->exists()){
                    return ProviderResource::collection($this->service_providers->load([
                        'region',
                        'ratings'
                    ]));
                }
                return [];
            })
        ];
    }
}
