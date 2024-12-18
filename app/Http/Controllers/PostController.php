<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Http\Controllers\Controller,
    Validator,
    Session;


class PostController extends Controller
{
    /**
     * 投稿画面遷移
     */
    function create()
    {
        // セッションにログイン情報があるか確認
        if (!Session::exists('user')) {
            // ログインしていなければログインページへ
            return redirect('/login');
        }

        // 画面表示
        return view('post.index');
    }

    /**
     * 投稿処理
     */

    function store(Request $request)
    {

        // セッションにログイン情報があるか確認
        if (!Session::exists('user')) {
            // ログインしていなければログインページへ
            return redirect('/login');
        }

        // ログイン中のユーザーの情報を取得する
        $loginUser = Session::get('user');

        $rules = [
            'postContent' => 'required|max:140',
        ];

        $errorMessage = ['required' => '必須項目です', 'max' => '140文字以下にしてください。'];
        Validator::make($request->all(), $rules, $errorMessage)->validate();


        // データ登録
        $post = new Post;
        $post->user = $loginUser->id;
        $post->content = $request->postContent;
        $post->save();

        return redirect('/');
    }

    /**
     * 投稿詳細画面遷移
     */
 public function show($id)
{
    // 指定したIDの投稿情報を取得
    $post = Post::find($id);

    // 投稿が存在するか判定
    if (!$post) {
        return dd('存在しない投稿です');
    }

    // 親投稿（リプライ元）を取得
    $parentPost = null;
    if ($post->reply_to) {
        // reply_to が存在する場合、親投稿を取得
        $parentPost = Post::find($post->reply_to);  // 親投稿を取得
    }

    // 投稿者を取得
    $user = $post->user();

    // 自分の投稿か判定
    $isOwnPost = false;
    if (Session::exists('user')) {
        $loginUser = Session::get('user');
        $isOwnPost = $loginUser->id == $user->id;
    }

    // リプライの取得
    $replies = $post->replies()->orderBy('created_at', 'asc')->get();

    // 親投稿が存在する場合、その情報をビューに渡す
    return view('post.detail', compact('post', 'user', 'isOwnPost', 'replies', 'parentPost'));
}
    /**
     * 投稿編集画面
     */

    public function edit($id)
    {
        $post = Post::find($id);
        // 投稿が存在するか判定
        if ($post == null) {
            return dd('存在しない投稿です');
        }
        // セッションにログイン情報があるか確認
        if (!Session::exists('user')) {
            return redirect('/');
        }

        // ログイン中のユーザーの情報を取得する
        $loginUser = Session::get('user');
        // 投稿者を取得する
        $user = $post->user();
        // 自分自身の投稿ページか判定
        if ($loginUser->id != $user->id) {
            return redirect('/');
        }

        // 画面表示
        return view('post.edit', compact('post'));
    }

    /**
     * 投稿編集処理
     */
    public function update(Request $request, $id)
    {
        // idから投稿を取得
        $post = Post::find($id);

        // 投稿が存在するか判定
        if ($post == null) {
            return dd('存在しない投稿です');
        }
        // セッションにログイン情報があるか確認
        if (!Session::exists('user')) {
            return redirect('/');
        }

        // データ登録
        $post->content = $request->postContent;
        $post->save();

        // 画面表示
        return redirect('/post/detail/' . $post->id);
    }

    /**
     * 投稿削除処理
     */
    public function delete(Request $request, $id)
    {
        // idから投稿を取得
        $post = Post::find($id);

        // 投稿が存在するか判定
        if ($post == null) {
            return dd('存在しない投稿です');
        }
        // セッションにログイン情報があるか確認
        if (!Session::exists('user')) {
            return redirect('/');
        }

        // ログイン中のユーザーの情報を取得する
        $loginUser = Session::get('user');
        // 投稿者を取得する
        $user = $post->user();
        // 自分自身の投稿ページか判定
        if ($loginUser->id != $user->id) {
            return redirect('/');
        }

        // データ登録
        $post->is_deleted = true;
        $post->delete();

        return redirect('/');
    }

public function reply(Request $request, $postId)
{
    // バリデーション
    $request->validate([
        'content' => 'required|string|max:140'
    ]);

    // ログインしているユーザーを取得
    $loginUser = Session::get('user');

    if (!$loginUser) {
        return response()->json(['success' => false, 'message' => 'ログインが必要です。'], 401);
    }

    // 投稿を取得
    $post = Post::find($postId);
    if (!$post) {
        return response()->json(['success' => false, 'message' => '投稿が見つかりません。'], 404);
    }

    // リプライの作成
  $reply = Post::create([
    'user' => $loginUser->id,
    'content' => $request->content,
    'reply_to' => $post->id,  // 親投稿ID
    'is_deleted' => false
]);

    return response()->json(['success' => true, 'message' => 'リプライが送信されました']);
}

}
