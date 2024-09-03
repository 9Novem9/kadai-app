<?php

namespace App\Http\Controllers;

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Session;
use App\Models\Block;
use App\Models\User;

class BlockController extends Controller
{
    public function index($id)
    {
        $user = User::find($id);

        if ($user == null) {
            return dd('存在しないユーザーです');
        }

        $loginUser = Session::get('user');

      
        $blockUsers = $loginUser->blockedUsers; 
        $blockerUsers = $loginUser->blockers; 

        return view('user.block', compact('user', 'blockUsers', 'blockerUsers'));
    }

    public function update(Request $request, $id)
{
    $user = User::find($id);

    if ($user == null) {
        return dd('存在しないユーザーです');
    }

    // セッションからログインユーザーを取得
    $loginUser = Session::get('user');

    if (!$loginUser) {
        return redirect('/')->with('error', 'ログインセッションが存在しません');
    }

    // ブロック処理
    if ($request->input('isblocked')) {
        // 既にブロックされていないか確認
        $existingBlock = Block::where('id', $loginUser->id)
                              ->where('block_user', $id)
                              ->exists();

        if (!$existingBlock) {
            // ブロックが存在しない場合のみ新規作成
            $loginUser->block($id);
        }

        // フォローしている場合はアンフォロー
        if ($loginUser->isFollowed($id)) {
            $loginUser->unfollow($id);
        }
    } else {
        // ブロック解除処理
        $block = Block::where('id', $loginUser->id)
                      ->where('block_user', $id)
                      ->first();

        if ($block) {
            $loginUser->unblock($id);
        }
    }
        return redirect('/user/' . $user->id);
    }
}

    