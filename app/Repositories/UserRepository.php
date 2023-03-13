<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Contract\UserContract;
use Illuminate\Support\Facades\Hash;

class UserRepository implements UserContract
{
    public function create(array $data)
    {
        $user = new User();
        $user->first_name = $data["first_name"];
        $user->last_name = $data["last_name"];
        $user->email = $data["email"];
        $user->user_type = $data["user_type"];
        $user->password = Hash::make($data["password"]) ;

        $user->save();

        return $user;

    }


    public function update($id,array $data)
    {
        $user = User::find($id);
        $user->first_name = $data["first_name"];
        $user->last_name = $data["last_name"];
        $user->email = $data["email"];
        $user->user_type = $data["user_type"];
        if(in_array("password",array_keys($data)) && $data["password"] != ""){
            $user->password = Hash::make($data["password"]) ;
        }
        $user->save();

        return $user;

    }

    public function delete($id)
    {
        $user = User::find($id);
        $user->delete();

        return $user;

    }

}
