<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Block extends Model
{
    use HasFactory;
    
    public function blockuser()
    {
        return User::find($this->blocks);
    }

   
    public function blockeruser()
    {
        return User::find($this->user);
    }
}
