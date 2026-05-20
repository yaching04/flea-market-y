@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')

<div class="toppage-content">

    <!-- タブ -->
<div class="tab-area">
    <a href="{{ route('items.index') }}" class="tab {{ !request()->routeIs('items.mylist') ? 'active' : '' }}">おすすめ</a>
    <a href="{{ route('items.mylist') }}" class="tab {{ request()->routeIs('items.mylist') ? 'active' : '' }}">マイリスト</a>
</div>

    <!-- 商品一覧グリッド -->
<div class="products-grid">
    @foreach($items as $item)
        <div class="product-card">
            <a href="{{ route('items.show', $item) }}" class="product-link">
                <div class="product-image">
                    <img src="{{ asset('storage/' . $item->image_path) }}"
                        alt="{{ $item->name }}"
                        onerror="this.src='https://via.placeholder.com/600x600/eeeeee/999999?text=No+Image'">

                    @if($item->sold_at)
                        <div class="sold-overlay">
                            <span class="sold-text">SOLD OUT</span>
                        </div>
                    @endif
                </div>
                <div class="product-info">
                    <p class="product-name">{{ $item->name }}</p>
                    <p class="product-price">¥{{ number_format($item->price) }}</p>
                </div>
            </a>
        </div>
    @endforeach
</div>

</div>

@endsection
