<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ArticleDetailResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'publish_place' => $this->publish_place,
            'status' => $this->status,
            'article_pdf_url' => $this->article_pdf ? Storage::url($this->article_pdf) : null,
            'conclusion_pdf_url' => $this->conclusion_pdf ? Storage::url($this->conclusion_pdf) : null,
            'uploaded_by' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'full_name' => $this->user->full_name,
            ],
            'scores' => ArticleScoreResource::collection($this->whenLoaded('articleScores')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
