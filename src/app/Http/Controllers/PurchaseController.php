<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PurchaseController extends Controller
{
    /**
     * 購入フォーム画面
     */
    public function create(Item $item)
    {
        if ($item->sold_at !== null) {
            abort(404, 'この商品はすでに売却済みです');
        }

        // テスト中はコメントアウト推奨
        // if ($item->user_id === auth()->id()) {
        //     abort(403, '自分の商品は購入できません');
        // }

        return view('purchases.create', compact('item'));
    }

        /**
     * 購入処理（支払い方法で分岐）
     */
    public function store(Request $request, Item $item)
    {
        if ($item->sold_at !== null) {
            abort(404, 'この商品はすでに売却済みです');
        }

        $request->validate([
            'payment_method' => 'required|in:convenience,card',
            'postal_code'    => 'required|string|size:8|regex:/^\d{3}-\d{4}$/',
            'address'        => 'required|string|max:255',
            'building'       => 'nullable|string|max:255',
        ]);

        $paymentMethod = $request->payment_method;

        // 画像パスをStripeがアクセスできる公開URLに変換
        $imageUrl = $this->getStripeImageUrl($item);
        \Log::info('Stripe Image URL:', ['url' => $imageUrl, 'image_path' => $item->image_path]);

        $images = $imageUrl ? [$imageUrl] : [];

        if ($paymentMethod === 'card') {
            // ==================== カード払い → Stripe Checkout ====================
            $secretKey = config('services.stripe.secret');

            if (empty($secretKey)) {
                abort(500, 'StripeのAPIキーが設定されていません。');
            }

            \Stripe\Stripe::setApiKey($secretKey);

            $checkoutSession = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency'     => 'jpy',
                        'product_data' => [
                            'name'        => $item->name,
                            'description' => \Illuminate\Support\Str::limit($item->description ?? '', 200),
                            'images'      => $images,
                        ],
                        'unit_amount'  => $item->price,
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('checkout.success', $item) . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url'  => route('purchases.create', $item),
                'customer_email' => auth()->user()->email,
                'metadata' => [
                    'item_id'        => $item->id,
                    'user_id'        => auth()->id(),
                    'payment_method' => 'card',
                ],
            ]);

            return redirect($checkoutSession->url);

        } else {
            // ==================== コンビニ払い → Stripe Checkout（Konbini対応） ====================
            $secretKey = config('services.stripe.secret');

            if (empty($secretKey)) {
                abort(500, 'StripeのAPIキーが設定されていません。');
            }

            \Stripe\Stripe::setApiKey($secretKey);

            $checkoutSession = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['konbini'],   // コンビニ専用
                'line_items' => [[
                    'price_data' => [
                        'currency'     => 'jpy',
                        'product_data' => [
                            'name'        => $item->name,
                            'description' => \Illuminate\Support\Str::limit($item->description ?? '', 200),
                            'images'      => $images,
                        ],
                        'unit_amount'  => $item->price,
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('checkout.success', $item) . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url'  => route('purchases.create', $item),
                'customer_email' => auth()->user()->email,
                'metadata' => [
                    'item_id'        => $item->id,
                    'user_id'        => auth()->id(),
                    'payment_method' => 'convenience',
                ],
            ]);

            return redirect($checkoutSession->url);
        }
    }

    /**
     * Stripe Checkout用の画像URLを生成
     */
    private function getStripeImageUrl(Item $item): ?string
    {
        $path = ltrim($item->image_path, '/');

        if (Str::startsWith($path, 'storage/')) {
            $path = substr($path, strlen('storage/'));
        }

        if (Str::startsWith($path, 'images/')) {
            $filename = substr($path, strlen('images/'));
            $baseUrl = config('filesystems.disks.s3.url') ?: env('AWS_URL') ?: 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com';
            return rtrim($baseUrl, '/') . '/image/' . $filename;
        }

        if (Str::startsWith($path, 'image/')) {
            $baseUrl = config('filesystems.disks.s3.url') ?: env('AWS_URL') ?: 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com';
            return rtrim($baseUrl, '/') . '/' . $path;
        }

        if (filter_var($path, FILTER_VALIDATE_URL)) {
            return $path;
        }

        return url('storage/' . $path);
    }


    /**
     * 購入時の配送先住所変更画面を表示
     */
    public function editAddress(Item $item)
    {
        if ($item->sold_at !== null) {
            abort(404, 'この商品はすでに売却済みです');
        }

        return view('purchases.address', compact('item'));
    }

    /**
     * 購入時の住所変更処理
     */
    public function updateAddress(Request $request, Item $item)
    {
        $request->validate([
            'postal_code' => 'required|string|size:8|regex:/^\d{3}-\d{4}$/',
            'address'     => 'required|string|max:255',
            'building'    => 'nullable|string|max:255',
        ]);

        // 変更した配送先情報をセッションに一時保存
        session([
            'purchase_delivery' => [
                'postal_code' => $request->postal_code,
                'address'     => $request->address,
                'building'    => $request->building,
            ]
        ]);

        return redirect()->route('purchases.create', $item)
                        ->with('success', '配送先を更新しました');
    }

    // 以下は使用しない空のメソッド
    public function index() { }
    public function show(Purchase $purchase) { }
    public function edit(Purchase $purchase) { }
    public function update(Request $request, Purchase $purchase) { }
    public function destroy(Purchase $purchase) { }
}
