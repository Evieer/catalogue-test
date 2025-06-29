<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

class Group extends Model
{
    use HasFactory;
    protected $table = 'groups';
    protected $fillable = ['id_parent', 'name'];

    // Дочерние группы
    public function children()
    {
        return $this->hasMany(Group::class, 'id_parent')->withCount('products');
    }

    // Родительская группа
    public function parent()
    {
        return $this->belongsTo(Group::class, 'id_parent');
    }

    // Продукты, относящиеся к категории
    public function products()
    {
        return $this->hasMany(Product::class, 'id_group');
    }

    // Кол-во товаров в текущей и всех вложенных группах
    public function getProductsCountAttribute()
    {
        $groupIds = $this->getNestedGroupIds($this);
        return Product::whereIn('id_group', $groupIds)->count();
    }

    // Хлебные крошки (для страницы товара)
    public function getBreadcrumbsAttribute()
    {
        $breadcrumbs = collect();
        $group = $this;
        while ($group) {
            $breadcrumbs->prepend($group);
            $group = $group->parent;
        }
        return $breadcrumbs;
    }

    // Рекурсивный сбор ID текущей группы и всех вложенных
    protected function getNestedGroupIds(Group $group)
    {
        $ids = [$group->id];
        foreach ($group->children as $child) {
            $ids = array_merge($ids, $this->getNestedGroupIds($child));
        }
        return $ids;
    }

    // Кол-во товаров только текущей группы
    public function getDirectProductsCountAttribute()
    {
        return $this->products()->count();
    }
}
