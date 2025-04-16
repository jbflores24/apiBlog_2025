<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return ([
            'id' => $this->id,
            'titulo' => $this->titulo,
            'imagen' => $this->imagen,
            'texto' => $this->texto,
            'user_id' => $this->user_id,
            'user' => $this->user,
            //'comments' => $this->comments,
            'comments' => new CommentCollection($this->comments),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);
    }
}
