<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MerchantRegisterOrderResource extends JsonResource
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
            'serial_number'=>$this->serial_number,
            'date'=>$this->date,
            'status'=>$this->status,
            'user_in'=>$this->whenLoaded('user', function () {
                return new UserResource($this->user->load(['region','personal_identification_papers']));
            }),
            'reply'=>$this->reply,
        ];
    }
}
