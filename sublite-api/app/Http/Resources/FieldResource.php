<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FieldResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'type' => $this->type,
            'required' => (bool) $this->required,
            'value' => $this->type === 'boolean' ? (bool) $this->pivot?->value ?? ((bool)$this->value ?? null) : $this->pivot?->value ?? ($this->value ?? null)
        ];
    }
}