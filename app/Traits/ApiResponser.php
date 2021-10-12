<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

trait ApiResponser{
    private function showResponse($data, $code){
        return response()->json($data, $code);
    }

    protected function errorResponse($messsage, $code){
        return response()->json(['error' => $messsage, 'code' => $code], $code);
    }

    protected function showAll(Collection $collection, $code){
        return $this->showResponse(['data' => $collection], $code);
    }

    protected function showOne(Model $model, $code){
        return $this->showResponse(['data' => $model], $code);
    }
}