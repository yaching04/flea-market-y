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
                <img src="{{ asset(str_starts_with($item->image_path, 'storage/') ? $item->image_path : 'storage/' . $item->image_path) }}" alt="{{ $item->name }}" class="main-image" onerror="this.src='https://via.placeholder.com/600x600/eeeeee/999999?text=No+Image'">
            </div>

            <!-- 右側：商品情報 -->
            <div class="info-section">

                <h1 class="product-name">{{ $item->name }}</h1>

                @if ($item->brand)
                    <p class="brand">ブランド名：{{ $item->brand }}</p>
                @endif

                <p class="price"><span class="tax">¥ </span>{{ number_format($item->price) }} <span class="tax">(税込)</span></p>

                <!-- いいねとコメント -->
                <div class="reactions">
                <!-- いいね -->
                    <div class="reaction-item">
                        <button class="like-btn {{ $item->is_liked ? 'liked' : '' }}" data-item-id="{{ $item->id }}">
                            <i class="fa-heart {{ $item->is_liked ? 'fa-solid' : 'fa-regular' }}"></i>
                            <span class="like-count">{{ $item->likes_count ?? 0 }}</span>   <!-- ← ここに移動 -->
                        </button>
                    </div>

                <!-- コメント -->
                    <div class="reaction-item">
                        <span class="comment-icon">
                            <img src="{{ asset('images/icons/ふきだしのアイコン.png') }}" alt="コメント">
                        </span>
                        <span class="comment-count">{{ $item->comments->count() }}</span>
                    </div>
                </div>

                <!-- 購入ボタンエリア -->
                <div class="purchase-area {{ $item->sold_at ? 'sold-out-area' : '' }}">
                    @if (!$item->sold_at)
                        <a href="{{ route('purchases.create', $item) }}" class="buy-btn">購入手続きへ</a>
                    @else
                        <div class="sold-out">
                            <div class="sold-badge-large">SOLD OUT</div>
                            <p class="sold-message">この商品はすでに売却済みです</p>
                        </div>
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
                            <div class="category-tags">
                                @foreach($item->categories as $category)
                                    <span class="category-tag">{{ $category->name }}</span>
                                @endforeach
                                @if($item->categories->isEmpty())
                                    <span class="no-category">未設定</span>
                                @endif
                            </div>
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
                                <!-- プロフィール写真 + ユーザーネーム -->
                                <div class="comment-user">
                                    @if ($comment->user->profile_image)
                                        <img src="{{ asset($comment->user->profile_image) }}" alt="{{ $comment->user->name }}" class="comment-avatar">
                                    @else
                                        <div class="comment-avatar-placeholder">
                                            {{ substr($comment->user->name, 0, 1) }}
                                        </div>
                                    @endif
                                    <span class="username">{{ $comment->user->name }}</span>
                                </div>
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
                        <div class="comment-area">
                            <button type="submit" class="comment-btn">コメントを送信する</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>

    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const likeButtons = document.querySelectorAll('.like-btn');

        likeButtons.forEach(likeBtn => {
            likeBtn.addEventListener('click', function(e) {
                const itemId = this.dataset.itemId;

                // 未ログインの場合はログイン画面へ
                @if (!Auth::check())
                    window.location.href = "{{ route('login') }}";
                    return;
                @endif

                // ログイン済みの場合のみいいね処理を実行
                const heart = this.querySelector('.fa-heart');
                const countEl = this.querySelector('.like-count');

                fetch(`/items/${itemId}/like`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                })
                .then(data => {
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
                    alert('エラーが発生しました');
                });
            });
        });
    });
</script>
@endsection
