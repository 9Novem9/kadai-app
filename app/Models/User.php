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
        Follow::where('user', $this->id)
            ->where('follow_user', $id)
            ->first()
            ->delete();
    }
 
    public function blockUsers()
    {
        $blockUsers = Block::where('user', $this->id)->get();
        $result = [];
        foreach ($blockUsers as $blockUser) {
            array_push($result, $blockUser->blockUser());
        }
        return $result;
    }
   
    public function blockerUsers()
    {
        $blockerUsers = Block::where('blocks', $this->id)->get();
        $result = [];
        foreach ($blockerUsers as $blockUser) {
            array_push($result, $blockUser->blockerUser());
        }
        return $result;
    }
 
    public function isblocked($id)
    {
        foreach ($this->blockUsers() as $blockUser) {
            if ($blockUser->id == $id) {
                return true;
            }
        }

        return false;
    }
     
    public function block($id)
    {
        $block = new Block;
        $block->user = $this->id;
        $block->block = $id;
        $block->save();
    }

    public function unblock($id)
    {
        Block::where('user', $this->id)
            ->where('block', $id)
            ->first()
            ->delete();
    }
}