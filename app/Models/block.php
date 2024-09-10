<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Block extends Model
{
    use HasFactory;
    
    public function blockUser()
    {
        return User::find($this->block_user);
    }

   
    public function blockerUser()
    {
        return User::find($this->user);
    }
}
