@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/create.css') }}">
@endsection

@section('content')

<div class="sell-container">

    <h1 class="sell-title">商品の出品</h1>

    <form method="POST" action="{{ route('items.store') }}" enctype="multipart/form-data">
        @csrf

        <!-- 商品画像 -->
        <div class="image-upload-group">
            <label for="item-image">商品画像</label>

            <!-- ① アップロードボタンエリア -->
            <div class="image-area" id="upload-area">
                <label for="item-image" class="upload-btn">画像を選択する</label>
                <input type="file" name="image" id="item-image" accept="image/*" required class="image-input">
            </div>

            <!-- ② プレビューエリア -->
            <div class="image-preview" id="image-preview" style="display: none;">
                <img id="preview" style="max-width: 100%; border-radius: 8px;">
            </div>
        </div>

        <!-- 商品の詳細 -->
        <div class="form-group">
            <h2 class="form-title">商品の詳細</h2>

            <!-- カテゴリー -->
            <div class="category-area">
                <label>カテゴリー</label>
                <div class="category-tags">
                    @foreach($categories as $category)
                    <label class="category-tag">
                        <input type="checkbox" name="categories[]" value="{{ $category->id }}"
                            {{ in_array($category->id, old('categories', [])) ? 'checked' : '' }}>
                        <span>{{ $category->name }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            <!-- 商品の状態 -->
            <label>商品の状態</label>
            <select name="condition_id" class="form-select" required>
                <option value="" disabled selected hidden>選択してください</option>
                @foreach($conditions as $condition)
                    <option value="{{ $condition->id }}" class="condition-option">{{ $condition->name }}</option>
                @endforeach
            </select>
        </div>


        <h2 class="form-title">商品名と説明</h2>
        <!-- 商品名 -->
        <div class="form-group">
            <label>商品名</label>
            <input type="text" name="name" class="form-input" value="{{ old('name') }}" required>
        </div>

        <!-- ブランド名 -->
        <div class="form-group">
            <label>ブランド名</label>
            <input type="text" name="brand" class="form-input" value="{{ old('brand') }}">
        </div>

        <!-- 商品の説明 -->
        <div class="form-group">
            <label>商品の説明</label>
            <textarea name="description" class="form-textarea" rows="5" required>{{ old('description') }}</textarea>
        </div>

        <!-- 販売価格 -->
        <div class="form-group">
            <label>販売価格</label>
            <div class="price-input">
                <div class="yen-wrapper">
                    <span class="yen">¥</span>
                    <input type="number" name="price" class="form-input price-field" value="{{ old('price') }}" required>
                </div>
            </div>
        </div>

        <button type="submit" class="sell-submit-btn">出品する</button>
    </form>

</div>

@endsection

@section('js')
<script>
// ==================== 商品画像 プレビュー ====================
const fileInput = document.getElementById('item-image');
const uploadArea = document.getElementById('upload-area');
const previewArea = document.getElementById('image-preview');
const previewImg = document.getElementById('preview');
const itemImageLabel = document.querySelector('label[for="item-image"]');

fileInput.addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(event) {
            previewImg.src = event.target.result;
            previewArea.style.display = 'block';
            uploadArea.style.display = 'none';
            if (itemImageLabel) itemImageLabel.style.display = 'none';
        };
        reader.readAsDataURL(file);
    }
});

previewImg.addEventListener('click', function() {
    fileInput.click();
});
</script>
@endsection
