<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Purchase;

class MypageController extends Controller
{
    /**
     * マイページ（購入した商品 / 出品した商品）
     */
    public function index(Request $request)
    {
        $page = $request->query('page', 'buy');   // デフォルトは「購入した商品」

        if ($page === 'sell') {
            // 出品した商品一覧
            $items = Item::where('user_id', auth()->id())
                        ->with('condition')
                        ->latest()
                        ->paginate(12);

            return view('mypage.index', compact('items', 'page'));
        }

        // 購入した商品一覧
        $purchases = Purchase::where('user_id', auth()->id())
                            ->with(['item.condition', 'item.user'])
                            ->latest()
                            ->paginate(12);

        return view('mypage.index', compact('purchases', 'page'));
    }

    /**
     * プロフィール編集画面
     */
    public function editProfile()
    {
        $user = auth()->user();
        return view('mypage.profile', compact('user'));
    }

    public function updateProfile(Request $request)
{
    $request->validate([
        'name'          => 'required|string|max:255',
        'postal_code'   => 'required|string|size:8|regex:/^\d{3}-\d{4}$/',
        'address'       => 'required|string|max:255',
        'building'      => 'nullable|string|max:255',
        'profile_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
    ]);

    $user = auth()->user();

    // === ここで1回目の更新かどうかを判定 ===
    $isFirstUpdate = empty($user->postal_code);

    // 画像処理
    if ($request->hasFile('profile_image')) {
        $path = $request->file('profile_image')->store('profile_images', 'public');
        $user->profile_image = 'storage/' . $path;
    }

    $user->update([
        'name'         => $request->name,
        'postal_code'  => $request->postal_code,
        'address'      => $request->address,
        'building'     => $request->building,
    ]);

    // 判定
    if ($isFirstUpdate) {
        // 1回目の更新（初回登録後）
        return redirect()->route('items.index')
                        ->with('success', 'プロフィールを設定しました！');
    } else {
        // 2回目以降の更新
        return redirect()->route('mypage.index')
                        ->with('success', 'プロフィールを更新しました！');
    }
}
}
