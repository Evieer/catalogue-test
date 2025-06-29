@extends('layouts.app')

@section('breadcrumbs')
    @if(isset($breadcrumbs) && $breadcrumbs->isNotEmpty())
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Главная</a></li>
                @foreach($breadcrumbs as $crumb)
                    @if(!$loop->last)
                        <li class="breadcrumb-item">
                            <a href="{{ route('group', $crumb['id']) }}">{{ $crumb['name'] }}</a>
                        </li>
                    @else
                        <li class="breadcrumb-item active" aria-current="page">{{ $crumb['name'] }}</li>
                    @endif
                @endforeach
            </ol>
        </nav>
    @endif
@endsection

@section('content')
    <div class="row">
        <!-- Боковая панель -->
        <div class="col-md-3">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    Подкатегории
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @foreach($subgroups as $subgroup)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <a href="{{ route('group', $subgroup->id) }}" class="text-decoration-none">
                                    {{ $subgroup->name }}
                                </a>
                                <span class="badge bg-secondary rounded-pill">
                                    {{ $subgroup->products_count }}
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
                <div class="card-header bg-white d-flex justify-content-between align-items-center mb-4">
                    <h4 class="mb-0">
                        {{ $currentGroup->name }}
                        <small class="text-muted">({{ $currentGroup->directProductsCount}} товаров)</small>
                        <small class="text-muted">({{ $products->total() }} всего)</small>
                    </h4>
                    @include('catalogue.partials.sort-controls')
                </div>

                <div class="card-body">
                    @if($products->isEmpty())
                        <div class="alert alert-info">Товары не найдены</div>
                    @else
                        <div class="row" id="products-container">
                            @foreach($products as $product)
                                @include('catalogue.partials.product-card')
                            @endforeach
                        </div>

                        <div class="mt-4" id="pagination-links">
                            {{ $products->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection