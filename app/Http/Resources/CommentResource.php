<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
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
            'comentario' => $this->comentario,
            'estado' => $this->estado,
            'user_id'=> $this->user_id,
            'usuario' => $this->user,
            'article_id' => $this->article_id,
            'article' => $this->article,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
