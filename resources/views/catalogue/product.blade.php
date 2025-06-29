@extends('layouts.app')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">Главная</a></li>
    @foreach($product->breadcrumbs as $breadcrumb)
        <li class="breadcrumb-item">
            <a href="{{ route('group', $breadcrumb) }}">{{ $breadcrumb->name }}</a>
        </li>
    @endforeach
    <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <h1 class="card-title">{{ $product->name }}</h1>
            <p class="display-6">Цена: {{ $product->current_price }} руб.</p>

            <div class="mt-4">
                <a href="{{ url()->previous() }}" class="btn btn-secondary">
                    &larr; Назад к каталогу
                </a>
            </div>
        </div>
    </div>
@endsection