<?php

namespace App\Http\Resources;

use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class OrderResource extends JsonResource
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
            'serial_number'=>$this->serial_number,
            'date'=>$this->date,
            'status'=>$this->status,
            'user'=>$this->whenLoaded('user', function () {
                return new UserResource($this->user->load(['region','personal_identification_papers']));
            }),
            'reply'=>$this->reply,
//            'classification_id'=>$this->classification_id,
            'type'=>Str::lower($this->after_last('\\',$this->orderable_type)),
            'order'=>$this->whenLoaded('orderable', function () {
                $type=Str::lower($this->after_last('\\',$this->orderable_type));
                $order=null;
                if ($type==='property')
                {
//                    return $this->orderable;
                    $order=new PropertyResource($this->orderable->load([
                        'ratings',
                        'advertisement',
                        'ownership_papers',
                        'directions',
                        'region',
                        'pledge_type',
                        'ownership_type',
                        'rooms',
                        'features',
                        'detailed_attributes',
                        'medias'
                    ]));
                }
                if ($type==='serviceprovider')
                {
                    $order=new ProviderResource($this->orderable->load([
                        'region',
                        'categories',
                        'ratings'
                    ]));
                }

                return $order;
            })
        ];
    }
}
