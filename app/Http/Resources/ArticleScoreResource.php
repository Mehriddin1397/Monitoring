<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ArticleScoreResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'definitions' => (int)$this->definitions,
            'classifications' => (int)$this->classifications,
            'suggestions' => (int)$this->suggestions,
            'total_score' => (float)$this->total_score,
            'evaluated_by' => $this->evaluator ? [
                'id' => $this->evaluator->id,
                'name' => $this->evaluator->name,
                'full_name' => $this->evaluator->full_name,
            ] : null,
            'created_at' => $this->created_at,
        ];
    }
}
