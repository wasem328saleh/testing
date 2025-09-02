<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ComplaintResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if ($this->reply){
            return [
                'id'=>$this->id,
                'serial_number'=>$this->serial_number,
                'user'=>$this->whenLoaded('user',function (){
                    return new UserResource($this->user->load('region'));
                }),
                'title'=>$this->title,
                'text'=>$this->complaint_text,
                'is_reply'=>true,
                'medias'=>$this->when($this->complaint_media,function(){
                    return UrlResource::collection($this->complaint_media);
                }),
                'reply'=>$this->when($this->reply,function(){
                    return new ComplaintResponseResource($this->reply);
                }),
            ];
        }
        return [
            'id'=>$this->id,
            'serial_number'=>$this->serial_number,
            'user'=>$this->whenLoaded('user',function (){
                return new UserResource($this->user->load('region'));
            }),
            'title'=>$this->title,
            'text'=>$this->complaint_text,
            'is_reply'=>false,
            'medias'=>$this->when($this->complaint_media,function(){
                return UrlResource::collection($this->complaint_media);
            }),
            'reply'=>'No reply',
        ];
    }
}
