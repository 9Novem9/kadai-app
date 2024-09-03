<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Follow;
use App\Models\Block;
use App\Models\Post;

class User extends Model
{
    use HasFactory;

    /**
     * ユーザーの投稿を取得する
     */
    public function posts()
    {
        return Post::where('user', $this->id)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * ユーザーがフォローしているユーザーのリストを取得する
     */
    public function followUsers()
    {
        $followUsers = Follow::where('user', $this->id)->get();
        $result = [];
        foreach ($followUsers as $followUser) {
            array_push($result, $followUser->followUser());
        }
        return $result;
    }

    /**
     * ユーザーをフォローしているユーザーのリストを取得する
     */
    public function followerUsers()
    {
        $followerUsers = Follow::where('follow_user', $this->id)->get();
        $result = [];
        foreach ($followerUsers as $followUser) {
            array_push($result, $followUser->followerUser());
        }
        return $result;
    }

    /**
     * $idのユーザーがこのユーザーをフォローしているか判定する
     */
    public function isFollowed($id)
    {
        foreach ($this->followUsers() as $followUser) {
            if ($followUser->id == $id) {
                return true;
            }
        }

        return false;
    }

    /**
     * $idのユーザーをフォローする
     */
    public function follow($id)
    {
        $follow = new Follow;
        $follow->user = $this->id;
        $follow->follow_user = $id;
        $follow->save();
    }

    /**
     * $idのユーザーをフォロー解除する
     */
    public function unfollow($id)
{
    $follow = Follow::where('user', $this->id)
        ->where('follow_user', $id)
        ->first();

    if ($follow) {
        $follow->delete();
    } else {
        
    }
}
 /*
    *ユーザーがブロックしているユーザーのリストを取得する
    */
    public function blockUsers()
{
    $blockerUsers = Block::where('block_user', $this->id)->get();
    $result = [];

    foreach ($blockerUsers as $blockUser) {
        $user = $blockUser->blockerUser();
        if ($user) {
            $result[] = $user;
        }
    }

    return $result ?: []; // null の場合は空の配列を返す
}
   /*
    ユーザーをブロックしているユーザーのリストを取得する
    */
    public function blockerUsers()
    {
        $blockerUsers = Block::where('block_user', $this->id)->get();
        $result = [];
        foreach ($blockerUsers as $blockUser) {
            array_push($result, $blockUser->blockerUser());
        }
        return $result;
    }

    
 
   /**
     * $idのユーザーがこのユーザーをフォローしているか判定する
     */
    public function isBlocked($id)
    {
        $blockedUsers = $this->blockUsers();
    
        if ($blockedUsers === null) {
            \Log::warning('blockUsers() returned null for user ID ' . $this->id);
            return false;
        }
    
        foreach ($blockedUsers as $blockUser) {
            if ($blockUser && $blockUser->id == $id) { // $blockUser が null でないことを確認
                return true;
            }
        }
    
        return false;
    }
     
    public function block($id)
    {
        $block = new Block;
        $block->user = $this->id;
        $block->block_user = $id;
        $block->save();
    }


    public function unblock($id)
{
    $block = Block::where('user', $this->id)
        ->where('block_user', $id)
        ->first();

    if ($block) {
        $block->delete();
    } else {
        
    }
}
}