<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // Nome da tabela no banco de dados
    protected $table = 'products';

    // Colunas que podem ser preenchidas
    protected $fillable = [
        'name',
        'description',
        'price',
        'city',
    ];
}

