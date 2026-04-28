<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AssignmentResource extends JsonResource
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
            'admin' => $this->whenLoaded('admin'),
            'technician' => $this->whenLoaded('technician'),
            'customer' => $this->whenLoaded('customer'),
            'status' => $this->whenLoaded('status'),
            'description_by_admin' => $this->description_by_admin,
            'lat_check_in' => $this->lat_check_in,
            'lng_check_in' => $this->lng_check_in,
            'check_in_photo_url' => $this->check_in_photo_path ? url('storage/' . $this->check_in_photo_path) : null,
            'lat_check_out' => $this->lat_check_out,
            'lng_check_out' => $this->lng_check_out,
            'check_out_photo_url' => $this->check_out_photo_path ? url('storage/' . $this->check_out_photo_path) : null,
            'description_by_technician' => $this->description_by_technician,
            'rating' => $this->rating,
            'review_by_admin' => $this->review_by_admin,
            'completed_at' => $this->completed_at,
            'closed_at' => $this->closed_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
