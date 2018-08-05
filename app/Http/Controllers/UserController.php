<?php

namespace App\Http\Controllers;

use \App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    
    public function listUser()
    {   
        $users = User::listUser();

        if ( request()->path() == 'upgrade' ) {
            return view('user.upgrade' , compact('users') );
        }else{
            return view('user.downgrade' , compact('users') );
        }

    }

    public function upgradeUser(User $user)
    {

        $data = request()->validate([

            'list'  => 'required|array',
            'list.*'=> 'string|min:4|max:7|in:show,create,edit,delete,approve',
            'permission_level' => 'string|in:admin,moderator,user',

            ] , ['list.*' => 'اختار من المتاح يا روح امك ']);

        

        foreach ($data['list'] as $value) {
            
            
            if ( request()->route()->getName() == 'upgrade.users' ) {

                $permissions[ $data['permission_level'] . '.' . $value ] = true;

            }elseif ( request()->route()->getName() == 'downgrade.users' ) {

                $permissions[ $data['permission_level'] . '.'  . $value ] = false;
            }

        }

        if ( $user->upgradeOrDowngradeUser(7 , $permissions) ) {
            return back()->with('success' , 'User Has Been Updated');
        }else{
            return back()->with('error' , 'Unexpected Error');
        }
    }


}
