@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/address.css') }}">
@endsection

@section('content')

<div class="address-container">

    <h1 class="address-title">住所の変更</h1>

    <form method="POST" action="{{ route('purchases.address.update', $item) }}">
    @csrf
    {{-- @method('PUT') は削除 --}}

    <div class="form-group">
        <label>郵便番号</label>
        <input type="text" name="postal_code" class="form-input"
            value="{{ old('postal_code', auth()->user()->postal_code ?? '') }}"
            placeholder="123-4567" required>
    </div>

    <div class="form-group">
        <label>住所</label>
        <input type="text" name="address" class="form-input"
            value="{{ old('address', auth()->user()->address ?? '') }}"
            required>
    </div>

    <div class="form-group">
        <label>建物名（任意）</label>
        <input type="text" name="building" class="form-input"
            value="{{ old('building', auth()->user()->building ?? '') }}">
    </div>

    <button type="submit" class="update-btn">この住所で確定する</button>
</form>

    <div class="back-link">
        <a href="{{ route('purchases.create', $item) }}">購入画面に戻る</a>
    </div>

</div>

@endsection
