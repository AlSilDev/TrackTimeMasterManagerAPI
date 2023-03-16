<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DriveResource extends JsonResource
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
            'email' => $this->email,
            'license_num' => $this->license_num,
            'license_expiry' => $this->license_expiry,
            'phone_num' => $this->phone_num,
            'affiliate_num' => $this->affiliate_num,
        ];
    }
}
