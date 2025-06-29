<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Product;

class CatalogueController extends Controller
{
    public function index()
    {
        $groups = Group::where('id_parent', 0)
            ->withCount('products')
            ->get();

        $products = Product::with(['group', 'price'])
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('catalogue.index', [
            'groups' => $groups,
            'products' => $products,
        ]);
    }

    public function group($id)
    {
        $currentGroup = Group::with(['parent.parent.parent', 'children'])->findOrFail($id);

        $breadcrumbs = collect();
        $group = $currentGroup;
        while ($group) {
            $breadcrumbs->prepend([
                'id' => $group->id,
                'name' => $group->name
            ]);
            $group = $group->parent;
        }

        $groupIds = $this->getNestedGroupIds($currentGroup);

        return view('catalogue.group', [
            'currentGroup' => $currentGroup,
            'breadcrumbs' => $breadcrumbs,
            'subgroups' => $currentGroup->children,
            'products' => Product::whereIn('id_group', $groupIds)
                ->with('price')
                ->paginate(12),
            'groupIds' => $groupIds,
            'directProductsCount' => $currentGroup->direct_products_count
        ]);
    }

    public function product(Product $product)
    {
        $product->load([
            'group' => function ($query) {
                $query->with('parent.parent.parent');
            },
            'price'
        ]);

        return view('catalogue.product', compact('product'));
    }

    private function getNestedGroupIds(Group $group)
    {
        $ids = [$group->id];
        foreach ($group->children as $child) {
            $ids = array_merge($ids, $this->getNestedGroupIds($child));
        }
        return $ids;
    }
}
