<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
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
            'name' => $this->name,
            'date_start_enrollments' => $this->date_start_enrollments,
            'date_end_enrollments' => $this->date_end_enrollments,
            'date_start_event' => $this->date_start_event,
            'date_end_event' => $this->date_end_event,
            'year' => $this->year,
            'image_url' => $this->image_url,
            'course_url' => $this->course_url,
            'category_id' => $this->category_id,
            'base_penalty' => $this->base_penalty,
            'point_calc_reason' => $this->point_calc_reason
        ];
    }
}
