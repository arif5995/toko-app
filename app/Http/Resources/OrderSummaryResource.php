<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderSummaryResource extends JsonResource
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
            'qty' => $this->qty,
            'total_price' => 'Rp ' . number_format($this->total_price, 0, ',', '.'),
            'status' => $this->status,
            'created_at' => $this->created_at->toDateTimeString(),
            'customer' => [
                'name' => $this->user->name ?? 'Guest',
                'email' => $this->user->email ?? null,
            ],
            'product' => [
                'id' => $this->product->id ?? null,
                'name' => $this->product->name ?? null,
                'price' => $this->product ? 'Rp ' . number_format($this->product->price, 0, ',', '.') : null,
                'category' => $this->product->category->name ?? null,
            ]
        ];
    }
}
