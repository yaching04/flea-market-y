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
        <div class="form-group">
            <label>商品画像</label>
            <input type="file" name="image" id="item-image" accept="image/*" required>

            <div class="image-preview" id="image-preview" style="margin-top: 15px; display: none;">
                <img id="preview" style="max-width: 300px; border-radius: 8px;">
            </div>
        </div>

        <!-- カテゴリー -->
        <div class="form-group">
            <label>カテゴリー</label>
            <div class="category-tags">
                @foreach($categories as $category)
                    <label class="category-tag">
                        <input type="checkbox" name="categories[]" value="{{ $category->id }}" {{ in_array($category->id, old('categories', [])) ? 'checked' : '' }}>
                        <span>{{ $category->name }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <!-- 商品の状態 -->
        <div class="form-group">
            <label>商品の状態</label>
            <select name="condition_id" class="form-select" required>
                <option value="">選択してください</option>
                @foreach($conditions as $condition)
                    <option value="{{ $condition->id }}">{{ $condition->name }}</option>
                @endforeach
            </select>
        </div>

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
                <span class="yen">¥</span>
                <input type="number" name="price" class="form-input" value="{{ old('price') }}" required>
            </div>
        </div>

        <button type="submit" class="sell-submit-btn">出品する</button>
    </form>

</div>

@endsection

@section('js')

<script>
document.getElementById('item-image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(event) {
            const preview = document.getElementById('preview');
            preview.src = event.target.result;
            document.getElementById('image-preview').style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
});
</script>

@endsection
