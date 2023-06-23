<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TechnicalVerificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'event_id' => $this->event_id,
            'enrollment_order' => $this->enrollment_order,
            'enrollment_id' => $this->enrollment_id,
            'verified' => $this->verified,
            'notes' => $this->notes,
            'verified_by' => $this->verified_by
        ];
    }
}
