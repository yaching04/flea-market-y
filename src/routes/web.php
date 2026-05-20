<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\MypageController;
use App\Http\Controllers\CommentController;
use Illuminate\Support\Facades\Auth;

Route::get('/', [ItemController::class, 'index'])->name('items.index');
Route::get('/items/{item}', [ItemController::class, 'show'])->name('items.show');

// ====================
// 認証関連
// ====================
// Fortify + 手動登録を共存させる（登録処理は手動コントローラーを優先）
Route::get('/register', fn() => view('auth.register'))->name('register');
Route::post('/register', [App\Http\Controllers\Auth\RegisterController::class, 'store'])->name('register');

Route::get('/login', fn() => view('auth.login'))->name('login');
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login'])->name('login.post');

Route::post('/logout', function () {
    Auth::logout();
    return redirect()->route('items.index');
})->name('logout');

// コメント投稿
Route::post('/items/{item}/comments', [CommentController::class, 'store'])
    ->name('comments.store')
    ->middleware('auth');

// ====================
// ログイン必須ルート
// ====================
Route::middleware('auth')->group(function () {

    Route::get('/sell', [ItemController::class, 'create'])->name('items.create');
    Route::post('/sell', [ItemController::class, 'store'])->name('items.store');

    Route::get('/purchase/{item}', [PurchaseController::class, 'create'])->name('purchases.create');
    Route::post('/purchase/{item}', [PurchaseController::class, 'store'])->name('purchases.store');

    Route::get('/purchase/{item}/address', [PurchaseController::class, 'editAddress'])
        ->name('purchases.address');

    Route::post('/purchase/{item}/address', [PurchaseController::class, 'updateAddress'])
        ->name('purchases.address.update');

    Route::get('/mypage', [MypageController::class, 'index'])->name('mypage.index');
    Route::get('/mypage/profile', [MypageController::class, 'editProfile'])->name('mypage.profile.edit');
    Route::put('/mypage/profile', [MypageController::class, 'updateProfile'])
        ->name('mypage.profile.update');

    // Stripe決済成功後
    Route::get('/checkout/success/{item}', function (App\Models\Item $item) {
        $user = auth()->user();

        if ($item->sold_at === null) {
            \DB::transaction(function () use ($user, $item) {
                \App\Models\Purchase::create([
                    'user_id'        => $user->id,
                    'item_id'        => $item->id,
                    'payment_method' => 'card',
                    'postal_code'    => $user->postal_code ?? '未設定',
                    'address'        => $user->address ?? '未設定',
                    'building'       => $user->building ?? null,
                ]);

                $item->update(['sold_at' => now()]);
            });
        }

        return view('checkout.success', compact('item'));
    })->name('checkout.success');

    // いいね機能
    Route::post('/items/{item}/like', [App\Http\Controllers\LikeController::class, 'toggle'])
        ->name('items.like');

    // マイリスト
    Route::get('/mylist', [ItemController::class, 'mylist'])
        ->name('items.mylist');
});
