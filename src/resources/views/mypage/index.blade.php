@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
@endsection

@section('content')

    <div class="mypage-container">

        <!-- プロフィールヘッダー -->
        <div class="profile-header">
            <div class="profile-image">
                @if (auth()->user()->profile_image)
                    <img src="{{ asset(auth()->user()->profile_image) }}" alt="プロフィール画像">
                @else
                    <div class="default-avatar"></div>
                @endif
            </div>
            <div class="profile-info">
                <h2>{{ auth()->user()->name }}</h2>
                <a href="{{ route('mypage.profile.edit') }}" class="edit-profile-btn">プロフィールを編集</a>
            </div>
        </div>

        <!-- タブ -->
        <div class="tab-area">
            <a href="{{ route('mypage.index') }}" class="tab {{ request('page') !== 'sell' ? 'active' : '' }}">
                購入した商品
            </a>
            <a href="{{ route('mypage.index', ['page' => 'sell']) }}"
                class="tab {{ request('page') === 'sell' ? 'active' : '' }}">
                出品した商品
            </a>
        </div>

        <!-- 商品グリッド -->
        <div class="products-grid">
            @if (request('page') === 'sell')
                <!-- 出品した商品 -->
                @foreach ($items ?? [] as $item)
                    <div class="product-card">
                        <a href="{{ route('items.show', $item) }}" class="product-link">
                            <div class="product-image">
                                <img src="{{ asset($item->image_path) }}" alt="{{ $item->name }}"
                                    onerror="this.src='https://via.placeholder.com/600x600/eeeeee/999999?text=No+Image'">
                            </div>
                            <div class="product-info">
                                <p class="product-name">{{ $item->name }}</p>
                                <p class="product-price">¥{{ number_format($item->price) }}</p>
                            </div>
                        </a>
                    </div>
                @endforeach
            @else
                <!-- 購入した商品 -->
                @foreach ($purchases ?? [] as $purchase)
                    @php $item = $purchase->item; @endphp
                    <div class="product-card">
                        <a href="{{ route('items.show', $item) }}" class="product-link">
                            <div class="product-image">
                                <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->name }}"
                                    onerror="this.src='https://via.placeholder.com/600x600/eeeeee/999999?text=No+Image'">
                            </div>
                            <div class="product-info">
                                <p class="product-name">{{ $item->name }}</p>
                                <p class="product-price">¥{{ number_format($item->price) }}</p>
                            </div>
                        </a>
                    </div>
                @endforeach
            @endif
        </div>

    </div>

@endsection
