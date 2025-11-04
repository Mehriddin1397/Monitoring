<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ArticleListResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'publish_place' => $this->publish_place,
            'status' => $this->status,
            'article_pdf_url' => $this->article_pdf ? Storage::url($this->article_pdf) : null,
            'total_score' => isset($this->total_score) ? (float)$this->total_score : $this->articleScores()->orderByDesc('created_at')->value('total_score'),
            'scores' => ArticleScoreResource::collection($this->whenLoaded('articleScores')),
            'uploaded_by' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'full_name' => $this->user->full_name,
            ],
        ];
    }
}
