<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginUserRequest;
use App\Http\Requests\Auth\StoreUserRequest;
use App\Models\User;
use App\Traits\HttpResponses;
use GuzzleHttp\Promise\Create;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use HttpResponses;

    public function login(LoginUserRequest $request)
    {
       $request->validated($request->all ());
       if(!Auth::attempt($request->only(['email', 'password']))){
        return($this->error('','Credentials do not match any of our records',401));
       }
       $user= User::where('email', $request->email)->first(); 
       return $this->success([
        'user'=>$user,
        'token'=>$user->createToken('Api token of '.$user->name)->plainTextToken
       ]);
          
    }
    public function register(StoreUserRequest $request)
    {
       $request->validated($request->all());
       $user = User::create([
        'name'=> $request->name,
        'email'=>$request->email,
        
        'password'=>Hash::make($request->password),
        
       ]);
       return response()->json([
        'user'=>$user,
        'token'=> $user->createToken('API Token of'.$user->name)->plainTextToken
        
       ]);
    
       
    }
    public function logout()
    {
        return response()->json('this is logout function');
    }
}