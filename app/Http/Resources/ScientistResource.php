<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ScientistResource extends JsonResource
{
    public function toArray($request): array
    {
        // $this is a User model (scientist)
        return [
            'id' => $this->id,
            'name' => $this->name,
            'full_name' => $this->full_name,
            'position' => $this->position,
            'degree' => $this->degree,
            'is_scientific' => (bool)$this->is_scientific,
            'total_score' => (float)$this->total_score,
            'articles_count' => $this->articles_count ?? $this->articles()->count(),
        ];
    }
}
