<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    use HasFactory;
    protected $table = 'prices';
    protected $fillable = ['id_product', 'price'];
    
    // Цена относится к продукту
    public function product()
    {
        return $this->belongsTo(Product::class, 'id_product');
    }
}
