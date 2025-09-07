<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'name',
        'price',
        'validity',
        'unit',
        'contact',
        'description',
        'address',
        'city',
        'photo',
        'user_id',
        'stock',
    ];

    /**
     * Define a relação entre Produto e Usuário (vendedor).
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

}
