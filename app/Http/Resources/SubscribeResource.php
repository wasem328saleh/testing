<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscribeResource extends JsonResource
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
            'package_title'=>$this->whenLoaded('advertising_package', function () {
                return $this->advertising_package->title;
            }),
            'trans_package_title'=>$this->when(true,function(){
                $key=$this->advertising_package->translation->key;
                $file=$this->advertising_package->translation->file;
                return trans($file.'.'.$key);
            }),
            'subscription_date'=>Carbon::parse($this->subscription_start_date)->format('Y-m-d'),
            'subscription_expired_date'=>Carbon::parse($this->subscription_end_date)->format('Y-m-d'),
            'number_days_remaining'=>Carbon::parse($this->subscription_start_date)->diffInDays(Carbon::parse($this->subscription_end_date)),
            'used_advertisements_count'=>$this->used_advertisements_count,
            'all_advertisements_count'=>$this->advertisements_count,
            'count_advertisements_remaining'=>$this->advertisements_count-$this->used_advertisements_count,
            'status'=>$this->active,
            'user'=>$this->whenLoaded('user', function () {
                return new UserResource($this->user->load(['region']));
            }),
        ];
    }
}
