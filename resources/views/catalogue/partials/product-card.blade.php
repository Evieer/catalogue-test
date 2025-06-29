<div class="col-md-4 mb-4">
    <div class="card h-100">
        <div class="card-body">
            <h6 class="card-title">{{ $product->name }}</h6>
            <p class="card-text text-success fw-bold mb-1">
                {{ $product->price->price }} ₽
            </p>
            <p class="small text-muted mb-2">
                Категория: {{ $product->group->name }}
            </p>
            <a href="{{ route('product', $product->id) }}" class="btn btn-sm btn-outline-primary">
                Подробнее
            </a>
        </div>
    </div>
</div>