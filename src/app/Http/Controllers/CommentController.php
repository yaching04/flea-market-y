<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Item;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * コメント投稿
     */
    public function store(Request $request, Item $item)
    {
        $request->validate([
            'content' => 'required|string|max:255',
        ], [
            // 設計書通りのメッセージ
            'content.required' => 'コメントを入力してください。',
            'content.max'      => 'コメントは255文字以内で入力してください。',
        ]);

        Comment::create([
            'user_id' => auth()->id(),
            'item_id' => $item->id,
            'content' => $request->content,
        ]);

        return redirect()->route('items.show', $item)
                        ->with('success', 'コメントを投稿しました！');
    }
}
