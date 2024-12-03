<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('/css/reset.css') }}" />

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>kadai-app | 投稿詳細</title>
</head>
<!-- show.blade.php -->

<h2>投稿詳細</h2>

<!-- 親投稿が存在する場合のみ表示 -->
@if ($parentPost)
<div class="parent-post">
    <h3>親投稿</h3>
    <a href="/user/{{ $parentPost->user->id }}">
        <div class="user-info">
            <img class="user-icon" src="{{ asset('/img/user_icon.png') }}" alt="" />
            <div class="user-name">{{ $parentPost->user->name }}</div>
        </div>
        <div class="content">{{ $parentPost->content }}</div>
        <div class="time-stamp">{{ $parentPost->created_at }}</div>
    </a>
</div>
@endif

<!-- 自分の投稿表示 -->
<div class="post">
    <a href="/user/{{ $user->id }}">
        <div class="user-info">
            <img class="user-icon" src="{{ asset('/img/user_icon.png') }}" alt="" />
            <div class="user-name">{{ $user->name }}</div>
        </div>
        <div class="content">{{ $post->content }}</div>
        <div class="time-stamp">{{ $post->created_at }}</div>
    </a>

    @if ($isOwnPost)
    <div class="menu">
        <div class="menu-item font-blue">
            <a href="/post/edit/{{ $post->id }}">編集</a>
        </div>
        <form name="delete" action="/post/delete/{{ $post->id }}" method="post">
            @csrf
            <div class="menu-item font-red" onclick="deletePost()">
                削除
            </div>
        </form>
    </div>
    @endif
</div>

<!-- リプライ入力フォーム -->
<div class="reply-form">
    <textarea id="reply-content" placeholder="リプライを入力してください"></textarea>
    <button id="submit-reply" data-post-id="{{ $post->id }}">リプライ送信</button>
</div>

<!-- リプライの表示 -->
@foreach ($replies as $reply)
<div class="post">
    <a href="/user/{{ $reply->user->id }}">
        <img class="user-icon" src="{{ asset('/img/user_icon.png') }}" alt="" />
    </a>
    <div class="container">
        <a href="/user/{{ $reply->user->id }}">
            <div class="user-name">{{ $reply->user->name }}</div>
        </a>
        <a href="/post/detail/{{ $reply->id }}">
            <div class="content">{{ $reply->content }}</div>
        </a>
        <div class="time-stamp">{{ $reply->created_at }}</div>
    </div>
</div>
@endforeach
</div>
<x-footer></x-footer>
<script src="{{ asset('/js/app.js') }}"></script>
<script>
    function deletePost() {
        if (confirm("削除しますか?")) {
            document.delete.submit();
        }
    }
</script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.getElementById("submit-reply").addEventListener("click", function () {
            const postId = this.getAttribute("data-post-id");
            const content = document.getElementById("reply-content").value.trim();

            // 空欄チェック
            if (!content) {
                alert("リプライ内容を入力してください。");
                return;
            }

            // リプライをサーバーに送信
            fetch(`/post/${postId}/reply`, {
                method: "POST", // POSTメソッドを指定
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-Token": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
                },
                body: JSON.stringify({ content: content }) // ボディにリプライ内容を含める
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error("サーバーエラー");
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        location.reload(); // ページを再読み込みしてリプライを表示
                    } else {
                        alert("リプライの送信に失敗しました: " + data.message);
                    }
                })
                .catch(error => {
                    console.error("エラー:", error);
                    alert("リプライの送信中にエラーが発生しました。");
                });
        });
    });
</script>
<style scoped>
    .post-detail-page .user-icon {
        width: 50px;
        height: 50px;
    }

    .post-detail-page .user-info {
        display: flex;
    }

    .post-detail-page .user-name {
        line-height: 50px;
        font-size: 18px;
    }

    .post-detail-page .time-stamp {
        text-align: end;
        font-size: 14px;
    }

    .post-detail-page .post {
        padding: 0 10px;
    }

    .post-detail-page .menu {
        display: flex;
        justify-content: end;
    }

    .post-detail-page .menu-item {
        font-size: 16px;
        margin: 0 2px;
    }

    .post-detail-page .menu-item {
        font-size: 16px;
        margin: 0 2px;
    }

    .post-detail-page .menu-item {
        font-size: 16px;
        margin: 0 2px;
    }

    .post-detail-page .content {
        word-wrap: break-word;
    }
</style>

</html>