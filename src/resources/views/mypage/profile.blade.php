@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('content')

<div class="profile-edit-container">

    <h1 class="profile-edit-title">プロフィール設定</h1>

    <form method="POST" action="{{ route('mypage.profile.update') }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <!-- プロフィール画像 -->
    <!-- プロフィール画像 -->
    <div class="form-group image-group">
        <div class="current-image">
            <img id="preview-image" src="{{ auth()->user()->profile_image ? asset(auth()->user()->profile_image) : 'https://via.placeholder.com/150x150/eeeeee/999999?text=No+Image' }}" alt="プレビュー">
        </div>

        <label for="profile_image" class="upload-btn">画像を選択する</label>
        <input type="file" name="profile_image" id="profile_image" accept="image/*" class="image-input">
    </div>

    <!-- ユーザー名 -->
    <div class="form-group">
        <label>ユーザー名</label>
        <input type="text" name="name" class="form-input"
            value="{{ old('name', auth()->user()->name) }}" required>
    </div>

    <!-- 郵便番号 -->
    <div class="form-group">
        <label>郵便番号</label>
        <input type="text" name="postal_code" class="form-input"
            value="{{ old('postal_code', auth()->user()->postal_code) }}"
            placeholder="123-4567" required>
    </div>

    <!-- 住所 -->
    <div class="form-group">
        <label>住所</label>
        <input type="text" name="address" class="form-input"
            value="{{ old('address', auth()->user()->address) }}" required>
    </div>

    <!-- 建物名 -->
    <div class="form-group">
        <label>建物名（任意）</label>
        <input type="text" name="building" class="form-input"
            value="{{ old('building', auth()->user()->building) }}">
    </div>

    <button type="submit" class="update-btn">更新する</button>
</form>

</div>

@endsection

@section('js')
<script>
document.getElementById('profile_image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(event) {
            document.getElementById('preview-image').src = event.target.result;
        };
        reader.readAsDataURL(file);
    }
});
</script>

@endsection
