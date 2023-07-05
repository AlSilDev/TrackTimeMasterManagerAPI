<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TimeRunResource extends JsonResource
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
            'run_id' => $this->run_id,
            'run_order' => $this->participant->enrollment->run_order,
            'participant_id' => $this->participant_id,
            'arrival_date' => $this->arrival_date,
            'departure_date' => $this->departure_date,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'time_mils' => $this->time_mils,
            'time_secs' => $this->time_secs,
            'started' => $this->started,
            'arrived' => $this->arrived,
            'penalty' => $this->penalty,
            'penalty_updated_by' => $this->penalty_updated_by,
            'penalty_notes' => $this->penalty_notes,
            'time_points' => $this->time_points,
            'run_points' => $this->run_points
        ];
    }
}
