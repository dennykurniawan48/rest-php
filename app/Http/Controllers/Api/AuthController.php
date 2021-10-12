<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
   public function register(Request $request){
       $rules = [
           'first_name' => 'required',
           'last_name' => 'required',
           'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6'
       ];

       $this->validate($request, $rules);

       User::create([
        'first_name' => $request->first_name,
        'last_name' => $request->last_name,
        'email' => $request->email,
        'password' => bcrypt($request->password)
       ]);

       return response()->json(['success', 'success creating user'], Response::HTTP_CREATED);
   }

   public function login(Request $request){
       $rules = [
           'email' => 'required',
           'password' => 'required'
       ];

       $this->validate($request, $rules);

       if(!Auth::attempt($request->only('email', 'password'))){
           return response()->json(['error', 'Invalid credentials'], Response::HTTP_UNAUTHORIZED);
       }

       $user = Auth::user();

       $token = $user->createToken('token')->plainTextToken;

       return response()->json(['success' => 'Succesfully login', 'message' => $token], Response::HTTP_UNAUTHORIZED);
   }
}
