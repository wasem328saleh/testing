<?php

namespace App\Http\Resources;

use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;
use App\Http\Resources\PropertyResource;

class MyFavoriteResource extends JsonResource
{
    use GeneralTrait;
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $favoriteable_type=$this->favoriteable_type;
        $favoriteable_type_name=Str::lower($this->after_last('\\',$favoriteable_type));
        switch ($favoriteable_type_name) {
            case 'property':{
                return [
                    'id'=>$this->id,
                    'user_information'=>$this->whenLoaded('favoriteable', function () {
                        return new UserResource($this->favoriteable->order->user->load('region'));
                    }),
                    // 'user_information'=>new UserResource($this->favoriteable->order->user->load(['region'])),
                    'property'=>$this->whenLoaded('favoriteable', function (){
                        return new PropertyResource($this->favoriteable->load([
                        'advertisement',
                        'ratings',
                        'directions',
                        'region',
                        'pledge_type',
                        'ownership_type',
                        'rooms',
                        'features',
                        'detailed_attributes',
                        'medias'
                    ]));
                    })
                ];
            }
            default:return [];
        }

    }
}
