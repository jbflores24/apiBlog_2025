<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'email'=> $this->email,
            'rol_id' => $this->rol_id,
            'rol' => $this->rol,
            'articulos' => $this->articles,
            'comentarios' => $this->comments,
            'created_at' => $this->created_at,
            'update_at' => $this->updated_at
        ];
    }
}
