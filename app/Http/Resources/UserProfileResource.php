<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserProfileResource extends JsonResource
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
            'advertising'=>AdvertisementResource::collection(collect($this->orders()
                ->where('for_service_provider',0)
                ->where('status','posted')
                ->whereHasMorph(
                    'orderable',
                    ['App\Models\Property'], // تحدید المودیلات المسموحة
                    function ($query) {
                        $query->where('status', 'active')
                            ->whereHas('advertisement', function ($query) {
                                $query->where('active', true);
                            });
                    }
                )
                ->with('orderable.advertisement.advertisementable')->get())->pluck('orderable.advertisement')),
        ];
    }
}
