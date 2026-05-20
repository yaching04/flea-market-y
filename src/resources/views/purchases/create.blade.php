@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchases.css') }}">
@endsection

@section('content')

<div class="purchase-container">

    <h1 class="purchase-title">購入確認</h1>

    <div class="purchase-main">

        <!-- 左側：商品情報 -->
        <div class="purchase-item">
            <div class="purchase-item-image">
                <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->name }}" onerror="this.src='https://via.placeholder.com/600x600/eeeeee/999999?text=No+Image'">
            </div>
            <div class="purchase-item-info">
                <h2>{{ $item->name }}</h2>
                @if($item->brand)
                    <p class="brand">ブランド: {{ $item->brand }}</p>
                @endif
                <p class="price">¥{{ number_format($item->price) }}</p>
            </div>
        </div>

        <!-- 右側：購入内容まとめ -->
        <div class="purchase-summary">
            <table class="summary-table">
                <tr>
                    <th>商品代金</th>
                    <td>¥{{ number_format($item->price) }}</td>
                </tr>
                <tr>
                    <th>支払い方法</th>
                    <td id="selected-payment">未選択</td>
                </tr>
            </table>
        </div>
    </div>

    <form method="POST" action="{{ route('purchases.store', $item) }}">
        @csrf

        <!-- 支払い方法 -->
        <div class="form-group">
            <label>支払い方法</label>
            <select name="payment_method" id="payment_method" class="form-select" required>
                <option value="" selected>選択してください</option>
                <option value="convenience">コンビニ払い</option>
                <option value="card">クレジットカード払い</option>
            </select>
        </div>

        <!-- 配送先情報 -->
        @php
            $delivery = session('purchase_delivery', [
                'postal_code' => auth()->user()->postal_code ?? '',
                'address'     => auth()->user()->address ?? '',
                'building'    => auth()->user()->building ?? '',
            ]);
        @endphp

        <input type="hidden" name="postal_code" value="{{ $delivery['postal_code'] }}">
        <input type="hidden" name="address" value="{{ $delivery['address'] }}">
        <input type="hidden" name="building" value="{{ $delivery['building'] }}">

        <div class="form-group">
            <label>配送先</label>
            <div class="address-info">
                <p>〒 {{ $delivery['postal_code'] }}</p>
                <p>{{ $delivery['address'] }} {{ $delivery['building'] }}</p>
                <a href="{{ route('purchases.address', $item) }}" class="change-address">変更する</a>
            </div>
        </div>

        <button type="submit" class="purchase-btn">購入する</button>
    </form>

</div>

@endsection

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const select = document.getElementById('payment_method');
    const display = document.getElementById('selected-payment');

    if (select && display) {
        // 初期状態を「未選択」に設定
        display.textContent = '未選択';

        select.addEventListener('change', function() {
            if (this.value === 'convenience') {
                display.textContent = 'コンビニ払い';
            } else if (this.value === 'card') {
                display.textContent = 'クレジットカード払い';
            } else {
                display.textContent = '未選択';
            }
        });
    }
});
</script>
@endsection
