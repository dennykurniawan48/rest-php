<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Models\Product;
use App\Models\Transaction as ModelsTransaction;
use Illuminate\Http\Request;
use stdClass;
use Symfony\Component\HttpFoundation\Response;

class Transaction extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userId = auth('sanctum')->user()->id;
        $transaction = ModelsTransaction::where('ref_buyer', '=', $userId)->get();
        return $this->showAll($transaction, Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = [];
        $userId = auth('sanctum')->user()->id;

        foreach($request->item as $item){
            $object = new stdClass();
            $product = Product::findOrFail($item["id"]);
            $object->product_name = $product->product_name;
            $object->id = $product->id;
            $object->price = $product->price;
            $object->image = $product->main_image;
            $object->qty = $item["qty"];
            $object->error = "";

            if($product->stock - $item["qty"] < 0){
                $object->error = "Qty of item out of stock.";
            }if(!$product->available){
                $object->error = "Sorry, this product is unavailable.";
            }if($product->ref_seller == $userId){
                $object->error = "Sorry, seller can't buy his own product.";
            }
            
            array_push($data, $object);
        }
        return response()->json($data, Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function checkout(Request $request)
    {
        $data = [];
        $userId = auth('sanctum')->user()->id;

        foreach($request->item as $item){
            $object = new stdClass();
            $product = Product::findOrFail($item["id"]);
            $object->product_name = $product->product_name;
            $object->id = $product->id;
            $object->price = $product->price;
            $object->image = $product->main_image;
            $object->qty = $item["qty"];
            $object->error = "";

            if($product->stock - $item["qty"] < 0){
                $object->error = "Qty of item out of stock.";
            }if(!$product->available){
                $object->error = "Sorry, this product is unavailable.";
            }if($product->ref_seller == $userId){
                $object->error = "Sorry, seller can't buy his own product.";
            }
            
            array_push($data, $object);
        }
        return response()->json($data, Response::HTTP_OK);
    }
}
