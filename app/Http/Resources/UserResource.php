<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'first_name'=>$this->first_name,
            'last_name'=>$this->last_name,
            'email'=>$this->email,
            'phone_number'=>$this->phone_number,
            'address'=>$this->whenLoaded('region',function(){
                return collect(new AddressResource($this->region->load('translation')))->put('secondary_address',$this->secondary_address);
            }),
            'image_url'=>url($this->image_url),
            'roles'=>$this->whenLoaded('roles', function () {
//                return IdTitleResource::collection($this->roles);
                return $this->roles->pluck('title')->toArray();
            }),
            'permissions'=>$this->whenLoaded('permissions', function () {
//                return IdTitleResource::collection($this->permissions);
                return $this->permissions->pluck('title')->toArray();
            }),
            'personal_identification_papers'=>$this->whenLoaded('personal_identification_papers', function () {
                return IdUrlResource::collection($this->personal_identification_papers);
            })
        ];
    }
}
