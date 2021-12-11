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

        $rules = [
            'address' => 'required|string',
            'name' => 'required|string'
        ];

        $this->validate($request, $rules);

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

            if($object->error != ""){
                return $this->errorResponse("Sorry can't process order", Response::HTTP_BAD_REQUEST);
            }
            
            array_push($data, $object);
        }

        $transaction = ModelsTransaction::create([
            'ref_buyer' => $userId,
            'code_transaction' => 'INV-'.rand(10000, 100000),
            'address' => $request->address,
            'name' => $request->address
        ]);

        $dataProduct = [];

        foreach($data as $d){
            $object = new stdClass();
            $product = Product::findOrFail($d->id);
            $object->product_id = $product->id;
            $object->transaction_id = $transaction->id;
            $object->price = $d->price;
            $object->qty = $d->qty;
            $dataProduct[] = (array)$object;

            // reduce product quantity
            $product->stock = $product->stock - $d->qty;
            $product->save();
        }

       $transaction->products()->sync($dataProduct);

       $detailTransaction = ModelsTransaction::where('code_transaction', '=', $transaction->code_transaction)->with('products')->first();
        
        return response()->json(["data" => $detailTransaction], Response::HTTP_OK);
    }

    public function show($id){
        $transaction = ModelsTransaction::where('code_transaction', '=', $id)->with('products')->first();
        if(!$transaction){
            abort(404);
        }
        return response()->json($transaction);
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
        return response()->json(["data" => $data], Response::HTTP_OK);
    }
}
