<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller,
    Session;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * ホーム画面遷移
     */
    public function index(Request $request)
{
    // セッションにログイン情報があるか確認
    if (!Session::has('user')) {
        // ログインしていなければログインページへ
        return redirect('/login');
    }

    // ログイン中のユーザーの情報を取得する
    $loginUser = Session::get('user');

    // フォローしているユーザーを取得
    $followedUsers = $loginUser->followUsers() ?: [];
    $blockedUsers = $loginUser->blockUsers() ?: [];

    // ログインしているユーザー自身も表示に含める
    $users = array_merge($followedUsers, $blockedUsers, [$loginUser]);

    // 各ユーザーの投稿を取得
    $posts = [];
    foreach ($users as $user) {
        if ($user) {
            foreach ($user->posts() as $post) {
                $posts[] = ['user' => $user, 'post' => $post];
            }
        }
    }

    // 投稿を時系列順に並べ替え
    $posts = $this->sort($posts);

    // 画面表示
    return view('home', compact('posts'));
}

/**
 * ホームに表示する投稿を時系列順に並べ替え
 */
private function sort($array)
{
    $standard_key_array = array_column($array, 'post', 'created_at');

    array_multisort($standard_key_array, SORT_DESC, $array);

    return $array;
}
}
