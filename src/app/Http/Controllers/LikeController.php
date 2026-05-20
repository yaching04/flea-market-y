<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Like;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function toggle(Request $request, Item $item)
    {
        try {
            $user = auth()->user();

            if (!$user) {
                return response()->json(['error' => '未ログインです'], 401);
            }

            $like = Like::where('user_id', $user->id)
                        ->where('item_id', $item->id)
                        ->first();

            if ($like) {
                $like->delete();
                $liked = false;
            } else {
                Like::create([
                    'user_id' => $user->id,
                    'item_id' => $item->id,
                ]);
                $liked = true;
            }

            return response()->json([
                'liked' => $liked,
                'count' => $item->likes()->count()
            ]);

        } catch (\Exception $e) {
            // 詳細なエラーを返す
            return response()->json([
                'error' => $e->getMessage(),
                'file'  => basename($e->getFile()),
                'line'  => $e->getLine()
            ], 500);
        }
    }
}
