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

    
        return view('user.block', compact('user', 'blockUsers', 'blockerUsers'));
    }

    public function update(Request $request, $id)
    {
        // idからユーザーを取得
        $user = User::find($id);
    
        // ユーザーが存在するか判定
        if ($user == null) {
            return redirect('/')->with('error', '存在しないユーザーです');
        }
    
        // セッションにログイン情報があるか確認
        if (!Session::exists('user')) {
            return redirect('/');
        }
    
        // ログイン中のユーザーの情報を取得する
        $loginUser = Session::get('user');
    
        // フォームからのデータ取得
        $isBlock = $request->input('isBlock');
      
    
        if ($isBlock == '1') {
            $loginUser->block($id);
        } else {
            $loginUser->unblock($id);
        }
    
    if ($loginUser->isFollowed($id)) {
        $loginUser->unfollow($id);
    }
        // 画面表示
        return redirect('/user/' . $user->id);
    }
    
}