<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';
    protected $fillable = ['id_group', 'name'];

    // Продукт принадлежит группе
    public function group()
    {
        return $this->belongsTo(Group::class, 'id_group');
    }

    // Отношение один к одному с ценой
    public function price()
    {
        return $this->hasOne(Price::class, 'id_product');
    }

    public function getCurrentPriceAttribute()
    {
        return $this->price ? $this->price->price : 0;
    }

    // Локальный скоуп для сортировки по цене
    public function scopeOrderByPrice($query, $direction = 'asc')
    {
        return $query->join('prices', 'products.id', '=', 'prices.id_product')
            ->orderBy('prices.price', $direction)
            ->select('products.*');
    }

    // Хлебные крошки
    public function getBreadcrumbsAttribute()
    {
        return $this->group->breadcrumbs;
    }
}
