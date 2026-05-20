@extends('layouts.app')

@section('content')
<div class="success-container" style="text-align: center; padding: 60px 20px; max-width: 600px; margin: 0 auto;">
    <h1 style="color: #34c759; font-size: 2.5rem;">✅ 購入完了</h1>
    <p style="font-size: 1.2rem; margin: 20px 0;">商品「{{ $item->name }}」の購入が完了しました。</p>

    <a href="{{ route('mypage.index', ['page' => 'buy']) }}" style="background:#ff6b6b; color:white; padding:15px 30px; text-decoration:none; border-radius:8px; font-size:1.1rem;">
        購入した商品を確認する
    </a>
</div>
@endsection
