<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ConfigAttributesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->when($this->id,function (){
                return $this->id;
            }),
            'attribute_name'=>$this->attribute_name,
            'rules'=>$this->when($this->rules,function (){
                return json_decode($this->rules);
            }),
            'is_required'=>$this->when($this->is_required,function (){
                return $this->is_required;
            }),
            'is_used_for_filtering'=>$this->when($this->is_used_for_filtering,function (){
                return $this->is_used_for_filtering;
            }),
            'classification_id'=>$this->when($this->classification_id,function (){
                return $this->classification_id;
            }),
            'category_id'=>$this->when($this->category_id,function (){
                return $this->category_id;
            })
        ];
    }
}
