<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ParticipantResource extends JsonResource
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
            'enrollment_id' => $this->enrollment_id,
            'first_driver_id' => $this->first_driver_id,
            'second_driver_id' => $this->second_driver_id,
            'vehicle_id' => $this->vehicle_id,
            'can_compete' => $this->can_compete
        ];
    }
}
