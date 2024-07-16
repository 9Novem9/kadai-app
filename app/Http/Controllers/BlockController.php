<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller,
    Session;
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
       
        $user = User::find($id);

    
        if ($user == null) {
            return dd('存在しないユーザーです');
        }
     
        if (!Session::exists('user')) {
            return redirect('/');
        }

 
        $loginUser = Session::get('user');

        if ($request->isblocked) {
        
            $loginUser->block($id);


            if($loginUser->isFollowed($id));
            {
                $loginUser->unfollow($id);
            }   
               
        } else {
           

            $loginUser->unblock($id);
        }

      
        return redirect('/user/' . $user->id);
    }
}

    