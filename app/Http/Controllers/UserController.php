<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use Illuminate\Http\Request;

class UserController extends Controller
{
    //
    public function register(Request $request){
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
        $user=new User();
        $user->name=$request->name;
        $user->email=$request->email;
        $user->password=bcrypt($request->password);

        $user->save();
        // create the token
        $token=$user->createToken('registertoken')->plainTextToken;
        // setting response
        $response=[
            'user'=>$user,
            'token'=>$token,
        ];

        return response($response,201);

    }
    public function logout(Request $request){
        Auth::user()->tokens()->delete();
        return ['message'=>'logged out'];

    }
    public function login(Request $request){
        $request->validate([
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8'],
        ]);
        $user = User::where('email', $request->email)->first();
 
        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response([
                'email' => ['The provided credentials are incorrect.'],401
            ]);
        }
     
        $token= $user->createToken('logintoken')->plainTextToken;

        return response([
            'user'=>$user,
            'token'=>$token,
            'message'=>'You have successfully logged in'
        ]);

        

    }
}
