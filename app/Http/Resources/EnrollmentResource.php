<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EnrollmentResource extends JsonResource
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
            'first_driver_id' => $this->first_driver_id,
            'second_driver_id' => $this->second_driver_id,
            'vehicle_id' => $this->vehicle_id,
            'enrolled_by_id' => $this->enrolled_by_id,
            'check_in' => $this->check_in,
        ];
    }
}
