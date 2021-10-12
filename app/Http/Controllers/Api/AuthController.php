<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;

class AuthController extends ApiController
{
   public function register(Request $request){
       $rules = [
        'first_name' => 'required',
        'last_name' => 'required',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:6'
       ];

       $this->validate($request, $rules);

       $user = User::create([
        'first_name' => $request->first_name,
        'last_name' => $request->last_name,
        'email' => $request->email,
        'verified_token' => Str::random(30),
        'password' => bcrypt($request->password)
       ]);

       return $this->showOne($user, Response::HTTP_OK);
   }

   public function login(Request $request){
       $rules = [
           'email' => 'required',
           'password' => 'required'
       ];

       $this->validate($request, $rules);

       if(!Auth::attempt($request->only('email', 'password'))){
           return $this->errorResponse('Invalid credentials', Response::HTTP_UNAUTHORIZED);
       }

       $user = Auth::user();

       $token = $user->createToken('token')->plainTextToken;

       $dataUser = User::findOrFail($user->id);

       $dataUser->token = $token;

       return $this->showOne($dataUser, Response::HTTP_OK);
   }

   public function verifyuser(Request $request){

   }
}
