<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VehicleResource extends JsonResource
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
            'model' => $this->model,
            'class' => $this->class,
            'category' => $this->class->category,
            'license_plate' => $this->license_plate,
            'year' => $this->year,
            'engine_capacity' => $this->engine_capacity,
        ];
    }
}
