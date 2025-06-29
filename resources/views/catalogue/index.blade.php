@extends('layouts.app')

@section('content')
<div class="row">
    <!-- Боковая панель с категориями -->
    <div class="col-md-3">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Категории</h5>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    @foreach($groups as $category)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="{{ route('group', $category->id) }}" class="text-decoration-none">
                            {{ $category->name }}
                        </a>
                        <span class="badge bg-secondary rounded-pill">
                            {{ $category->products_count }}
                        </span>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    <!-- Основной контент -->
    <div class="col-md-9">
        <div class="card">
            <div class="card-header bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Все товары</h5>

                    @include('catalogue.partials.sort-controls')
                </div>
            </div>
            
            <div class="card-body">
                <div class="row" id="products-container">
                    @foreach($products as $product)
                        @include('catalogue.partials.product-card')
                    @endforeach
                </div>

                <div class="mt-4" id="pagination-links">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection