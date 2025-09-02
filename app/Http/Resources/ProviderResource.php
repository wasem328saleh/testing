<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class ProviderResource extends JsonResource
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
            'serial_number'=>$this->order->serial_number,
            'date'=>Carbon::parse($this->order->created_at)->format('Y-m-d H:i:s'),
            'first_name'=>$this->first_name,
            'last_name'=>$this->last_name,
            'business_image_url'=>url($this->business_image_url),
            'address'=>$this->whenLoaded('region',function(){
                return collect(new AddressResource($this->region->load('translation')))->put('secondary_address',$this->secondary_address);
            }),
            'phone_number'=>$this->phone_number,
            'email'=>$this->when($this->email,function (){
                return $this->email;
            }),
            'visit_count'=>$this->visit_count,
            'description'=>$this->description,
            'status'=>$this->status,
            'isActive'=>$this->isActive,
            'services'=>$this->whenLoaded('categories',function (){
                return ServiceResource::collection($this->categories->load('translation'));
            }),
            'rating'=>$this->whenLoaded('ratings',function(){
                return $this->when($this->ratings,function(){
                    $ssc=count(collect(RatingResource::collection($this->ratings))->pluck('rating_score')->toArray());
                    $ss=array_sum(collect(RatingResource::collection($this->ratings))->pluck('rating_score')->toArray());
                    if ($ss==0)
                    {
                        return $ssc;
                    }
                    return round($ss/$ssc,1);
                });
            }),
        ];
    }
}
