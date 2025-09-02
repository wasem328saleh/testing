<?php

namespace App\Http\Resources;

use App\Traits\GeneralTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class AdvertisementResource extends JsonResource
{
    use GeneralTrait;
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'classification_id'=>$this->classification_id,
            'type'=>Str::lower($this->after_last('\\',$this->advertisementable_type)),
            'date'=>Carbon::parse($this->created_at)->format('Y-m-d H:i:s'),
            // 'user_information'=>new UserResource($this->advertisementable->order()->first()->user->load(['region'])),
            'user_information'=>$this->whenLoaded('advertisementable', function () {
                return new UserResource($this->advertisementable->order->user->load(['region']));
            }),
            'advertisement'=>$this->whenLoaded('advertisementable', function () {
                $type=Str::lower($this->after_last('\\',$this->advertisementable_type));

                if ($type==='property')
                {
//                    return $this->advertisementable;
                    return new PropertyResource($this->advertisementable->load([
                        'advertisement',
                        'ratings',
                        'directions',
                        'region',
                        'pledge_type',
                        'ownership_type',
                        'rooms',
                        'features',
                        'detailed_attributes',
                        'medias',
                        'order'
                    ]));
                }
            })
        ];
    }
}
