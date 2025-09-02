<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
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
            'address'=>$this->address,
            'start_time'=>$this->start_time,
            'end_time'=>$this->end_time,
            'date'=>Carbon::parse($this->created_at)->format('Y - m - d'),
            'is_payment'=>$this->is_payment
        ];
    }
}
