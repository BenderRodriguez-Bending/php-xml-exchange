<?php


namespace App\API\ServiceData;


use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserData
{

    public function userData(): array
    {
        if (Auth::check()){
            $user = Auth::user();
            $user_id = $user->id;
            $session_id = $user->session_back_id;
            $is_admin = $user->is_admin;
        }else{
            $user_id = null;
            $session_id = null;
            $is_admin = null;
        }

        return [
            'user_id' => $user_id,
            'session_id' => $session_id,
            'is_admin' => $is_admin
        ];
    }
}
