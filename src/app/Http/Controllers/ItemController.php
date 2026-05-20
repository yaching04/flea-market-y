<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    /**
     * 商品一覧画面（トップページ）
     */
        public function index(Request $request)
    {
        $keyword = $request->query('keyword');

        $query = Item::with(['user', 'condition'])
                    ->latest();

        // ログインしている場合は、自分が出品した商品を除外
        if (auth()->check()) {
            $query->where('user_id', '!=', auth()->id());
        }

        // キーワード検索
        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                ->orWhere('description', 'like', "%{$keyword}%")
                ->orWhere('brand', 'like', "%{$keyword}%");
            });
        }

        $items = $query->get();

        return view('items.index', compact('items', 'keyword'));
    }

    /**
     * 商品詳細画面（売却済みでも見れる）
     */
    public function show(Item $item)
    {
        $item->load(['user', 'condition', 'categories', 'comments.user']);

        return view('items.show', compact('item'));
    }

    /**
     * 出品フォーム画面
     */
    public function create()
    {
        $conditions = \App\Models\Condition::all();
        $categories = \App\Models\Category::all();

        return view('items.create', compact('conditions', 'categories'));
    }

    /**
     * 出品処理
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'price'         => 'required|integer|min:1',
            'description'   => 'required|string|max:500',
            'condition_id'  => 'required|exists:conditions,id',
            'image'         => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'categories'    => 'nullable|array',
            'categories.*'  => 'exists:categories,id',
        ]);

        $imagePath = $request->file('image')->store('items', 'public');

        $item = Item::create([
            'user_id'      => auth()->id(),
            'name'         => $request->name,
            'brand'        => $request->brand ?? null,
            'description'  => $request->description,
            'price'        => $request->price,
            'condition_id' => $request->condition_id,
            'image_path'   => 'storage/' . $imagePath,
        ]);

        if ($request->has('categories') && is_array($request->categories)) {
            $item->categories()->sync($request->categories);
        }

        return redirect()->route('items.index')
                        ->with('success', '商品を出品しました！');
    }

    /**
     * いいねした商品一覧（マイリスト）
     */
    public function mylist(Request $request)
    {
        $keyword = $request->query('keyword');

        $query = auth()->user()->likedItems()
                        ->with(['condition'])
                        ->whereNull('sold_at')
                        ->latest();

        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                ->orWhere('description', 'like', "%{$keyword}%")
                ->orWhere('brand', 'like', "%{$keyword}%");
            });
        }

        $items = $query->get();

        return view('items.index', compact('items', 'keyword'));
    }

    // 未使用メソッド
    public function edit(Item $item) { }
    public function update(Request $request, Item $item) { }
    public function destroy(Item $item) { }
}
