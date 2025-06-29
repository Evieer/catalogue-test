<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Group;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductApiController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query()
            ->with(['group', 'price'])
            ->orderBy('created_at', 'desc');

        // Фильтрация по группам
        if ($request->has('groupIds')) {
            if ($request->groupIds) {
                $groupIds = explode(',', $request->groupIds);
                $query->whereIn('id_group', $groupIds);
            }
        }

        // Сортировка
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'price_asc':
                    $query->orderByPrice('asc');
                    break;
                case 'price_desc':
                    $query->orderByPrice('desc');
                    break;
                case 'name_asc':
                    $query->orderBy('name', 'asc');
                    break;
                case 'name_desc':
                    $query->orderBy('name', 'desc');
                    break;
            }
        }

        $perPage = $request->get('per_page', 12);
        $products = $query->paginate($perPage);

        return ProductResource::collection($products);
    }
}
