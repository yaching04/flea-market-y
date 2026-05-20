@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/show.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
@endsection

@section('content')
    <div class="product-detail">

        <div class="detail-container">

            <!-- 左側：商品画像 -->
            <div class="image-section">
                <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->name }}" class="main-image"
                    onerror="this.src='https://via.placeholder.com/600x600/eeeeee/999999?text=No+Image'">
            </div>

            <!-- 右側：商品情報 -->
            <div class="info-section">

                <h1 class="product-name">{{ $item->name }}</h1>

                @if ($item->brand)
                    <p class="brand">ブランド名：{{ $item->brand }}</p>
                @endif

                <p class="price">¥{{ number_format($item->price) }} <span class="tax">(税込)</span></p>

                <div class="reactions">
                    <button class="like-btn {{ $item->is_liked ? 'liked' : '' }}" data-item-id="{{ $item->id }}">
                        <i class="fa-heart {{ $item->is_liked ? 'fa-solid' : 'fa-regular' }}"></i>
                        <span class="like-count">{{ $item->likes_count ?? 0 }}</span>
                    </button>

                    <span class="comment-count">💬 {{ $item->comments->count() ?? 0 }}</span>
                </div>

                <!-- 購入ボタンエリア -->
                <div class="purchase-area">
                    @if (!$item->sold_at)
                        <a href="{{ route('purchases.create', $item) }}" class="buy-btn">購入手続きへ</a>
                    @else
                        <div class="sold-badge-large">SOLD OUT</div>
                        <p class="sold-message">この商品はすでに売却済みです</p>
                    @endif
                </div>

                <!-- 商品説明 -->
                <div class="description">
                    <h3>商品説明</h3>
                    <p>{{ $item->description }}</p>
                </div>

                <!-- 商品情報 -->
                <div class="product-info">
                    <h3>商品の情報</h3>
                    <div class="info-list">
                        <div class="info-row">
                            <span class="label">カテゴリー</span>
                            <span class="value">{{ $item->categories->pluck('name')->join('、') ?: '未設定' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="label">商品の状態</span>
                            <span class="value">{{ $item->condition->name ?? '未設定' }}</span>
                        </div>
                    </div>
                </div>

                <!-- コメントセクション -->
                <div class="comments-area">
                    <h3>コメント ({{ $item->comments->count() }})</h3>

                    @foreach ($item->comments as $comment)
                        <div class="comment">
                            <div class="comment-header">
                                <span class="username">{{ $comment->user->name }}</span>
                            </div>
                            <p class="comment-text">{{ $comment->content }}</p>
                        </div>
                    @endforeach

                    <!-- コメント入力 -->
                    <form action="{{ route('comments.store', $item) }}" method="POST" class="comment-form" novalidate>
                        @csrf
                        @error('content')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                        <textarea name="content" rows="4" placeholder="コメントを入力・・・" required>{{ old('content') }}</textarea>
                        <button type="submit" class="comment-btn">コメントを送信する</button>
                    </form>
                </div>

            </div>
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const likeBtn = document.querySelector('.like-btn');

            if (likeBtn) {
                likeBtn.addEventListener('click', function() {
                    const itemId = this.dataset.itemId;
                    const heart = this.querySelector('.fa-heart');
                    const countEl = this.querySelector('.like-count');

                    fetch(`/items/${itemId}/like`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content'),
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(data => {
                            console.log('Success:', data); // コンソールに結果を表示

                            if (data.liked) {
                                likeBtn.classList.add('liked');
                                heart.classList.remove('fa-regular');
                                heart.classList.add('fa-solid');
                            } else {
                                likeBtn.classList.remove('liked');
                                heart.classList.remove('fa-solid');
                                heart.classList.add('fa-regular');
                            }
                            countEl.textContent = data.count;
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('エラーが発生しました: ' + error.message);
                        });
                });
            }
        });
    </script>
@endsection
