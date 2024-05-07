<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('/css/reset.css') }}" />
    <link rel="stylesheet" href="{{ asset('/css/app.css') }}" />

    <title>kadai-app | ユーザー</title>
</head>
<!-- ユーザー画面 -->

<body class="">
    <x-header></x-header>
    <div class="page user-page">
        <div class="page-container">
            <div class="user-info">
                <div class="user">
                    <div class="user-row">
                        <img class="user-icon" src="{{ asset('/img/user_icon.png') }}" alt="" /> @if ($isOwnPage)
                        <a href="/user/edit/{{ $user->id }}">
                            <button class="button-white edit">編集</button>
                        </a>
                        @else
                        <div class="follow-info">
                            <form name="follow" action="/follow/{{ $user->id }}" method="post">
                                @csrf @method('PUT') @if ($isFollowed)
                                <input type="hidden" name="isFollow" value="0" />
                                <button class="button-white" onClick="unfollow()">
                                        フォロー済み
                                    </button> @else
                                <input type="hidden" name="isFollow" value="1" />
                                <button class="button-black">
                                        フォロー
                                    </button> @endif
                            </form>
                        </div>
                     
                        <div class="block-info">
                            <form name="block" action="/block/{{ $user->id }}" method="post">
                                @csrf @method('PUT') @if ($isblocked)
                                <input type="hidden" name="isblocked" value="0" />
                                <button class="button-white" onClick="unblock()">
                                        ブラック済み
                                    </button> @else
                                <input type="hidden" name="isblocked" value="1" />
                                <button class="button-black">
                                        ブロック
                                    </button> @endif
                            </form>
                        </div>
                        @endif
                    </div>
                    <div class="user-name">{{ $user->name }}</div>
                </div>
                <div class="biography">
                    {{ $user->biography }}
                </div>
                <div class="follow-info">
                    <a href="/user/{{ $user->id }}/follow">
                        <div class="follow">
                            {{ $followCount }} フォロー中
                        </div>
                    </a>
                    <a href="/user/{{ $user->id }}/follower">
                        <div class="follower">
                            {{ $followerCount }} フォロワー
                        </div>
                    </a>
                </div>
            </div>
            <div class="post-list">
                <div class="title">投稿一覧</div>
                @foreach ($posts as $post)
                <a href="/post/detail/{{ $post->id }}">
                    <div class="post">
                        <img class="user-icon" src="{{ asset('/img/user_icon.png') }}" alt="" />
                        <div class="container">
                            <div class="user-name">
                                {{ $user->name }}
                            </div>
                            <div class="content">{{ $post->content }}</div>
                            <div class="time-stamp">
                                {{ $post->created_at }}
                            </div>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </div>
    <x-footer></x-footer>
</body>
<script src="{{ asset('/js/app.js') }}"></script>
<script>
    function unfollow() {
        if (confirm("フォローを解除しますか?")) {
            document.follow.submit();
        }
    }
</script>
<style scoped>
    .user-page .page-container {
        padding: 0 10px;
    }
    
    .user-page .user-info .user-icon {
        width: 60px;
        height: 60px;
    }
    
    .user-page .user-info {
        margin-bottom: 10px;
    }
    
    .user-page .user-row {
        display: flex;
        justify-content: space-between;
        line-height: 60px;
    }
    
    .user-page .user-info .user-name {
        font-size: 20px;
        font-weight: bold;
    }
    
    .user-page .biography {
        font-size: 14px;
        padding: 8px 0;
    }
    
    .user-page .follow-info {
        display: flex;
        font-size: 14px;
    }
    
    .user-page .follow-info .follow {
        margin-right: 5px;
    }
    
    .user-page .title {
        font-size: 18px;
        font-weight: bold;
        color: gray;
        margin-bottom: 6px;
    }
    
    .user-page .post {
        display: flex;
        padding: 0 10px;
    }
    
    .user-page .post .container {
        width: 90%;
    }
    
    .user-page .post-list .user-icon {
        width: 40px;
        height: 40px;
    }
    
    .user-page .user-name {
        line-height: 40px;
    }
    
    .user-page .content {
        font-size: 14px;
        word-wrap: break-word;
    }
    
    .user-page .time-stamp {
        font-size: 8px;
        text-align: end;
    }
</style>

</html>