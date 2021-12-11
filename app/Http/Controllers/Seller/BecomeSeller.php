<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\ApiController;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BecomeSeller extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userId = auth('sanctum')->user()->id;
        $user = User::findOrFail($userId);
        if($user->is_seller){
            abort(409, "You are already a seller");
        }
        return $this->showOne($user, Response::HTTP_OK);
    }

    public function store(Request $request){
        $rules = [
            'name' => 'required|string'
        ];

        $this->validate($request, $rules);

        $userId = auth('sanctum')->user()->id;

        $user = User::findOrFail($userId);

        $user->first_name = $request->name;
        $user->last_name = "";
        $user->is_seller = true;
        $user->save();

    }
}
