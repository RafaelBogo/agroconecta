<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
        'quantity',
    ];

    /**
     * Relacionamento com o modelo Product.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
