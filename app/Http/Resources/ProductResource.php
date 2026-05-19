<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'category_name' => $this->category->name ?? null,
            'price' => 'Rp ' . number_format($this->price, 0, ',', '.'),
            'stock' => $this->stock,
            'is_active' => $this->is_active,
        ];
    }
}
