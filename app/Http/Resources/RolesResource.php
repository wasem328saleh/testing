<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RolesResource extends JsonResource
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
            'permissions'=>$this->whenLoaded('permissions',function (){
                return PermissionResource::collection($this->permissions->load('translation'));
            })
        ];
    }
}
