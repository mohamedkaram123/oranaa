<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Auth;

class AuthController extends Controller
{
    //
    public function login(Request $request)
    {
        $validate =Validator::make($request->all(),[
            'email' => 'required|string|email',
            'password' => [
                'required',
            ],
        ],[
            "email.required"=>  "the email is required",
            "password.required"=>  "the password is requrired",

        ]);
        if($validate->fails()){
            return fail("error",$validate->errors());
        }
        $credentials = $request->only('email', 'password');

        $token = Auth::attempt($credentials);
        if (!$token) {
            return fail("error",$validate->errors()->add("email","email is not exist"));

        }

        $user = Auth::user("admin");
        $user["token"] = $user->generateNewToken()['token'];

        return success("success",$user);

    }

    public function logout()
    {
        auth("admin")->logout();
        return success("success");

    }
}
