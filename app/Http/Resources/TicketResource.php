<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'title'         => $this->title,
            'desctiption'   => $this->description,
            'status'        => $this->status,
            'priority'      => $this->priority,
            'category'      => $this->category->name,
            'created_by'    => $this->user->name,
            'comments_count'=> $this->comments->count(),
            'created_at'    => $this->created_at->toISOString(),
        ];
    }
}