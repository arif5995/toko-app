<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    //
    protected $fillable = ['user_id', 'product_id', 'qty', 'total_price', 'status'];

    /**
     * Relasi ke model Product
     * Mengindikasikan bahwa setiap order dibeli untuk 1 produk tertentu
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /**
     * Relasi ke model User
     * Mengindikasikan bahwa setiap order dimiliki oleh 1 user/customer tertentu
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
