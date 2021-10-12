<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\ApiController;
use App\Models\Product;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SellerProduct extends ApiController
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
       
        return $this->showOne($user, Response::HTTP_OK);
    }

    public function listproduct(){
        $userId = auth('sanctum')->user()->id;
        $user = User::findOrFail($userId);
        try{
            $data = $user->product;
        }catch(Exception $e){
            return response()->json(['date' => $e->getMessage()]);
        }
        
       // return response()->json(['date' => $data]);
        return $this->showAll($data, Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $userId = auth('sanctum')->user()->id;

        $rules = [
            'product_name' => 'required|string|min:3',
            'stock' => 'required|integer|min:1',
            'available' => 'required|boolean',
            'price' => 'required|regex:/^[0-9]+(\.[0-9][0-9]?)?$/',
            'category' => 'required|integer|exists:categories,id'
        ];

        $this->validate($request, $rules);

        $product = Product::create([
            'product_name' => $request->product_name,
            'stock' => $request->stock,
            'available' => $request->available,
            'ref_seller' => $userId,
            'ref_category' => $request->category,
            'price' => $request->price,
        ]);

        return $this->showOne($product, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::findOrFail($id);
        return $this->showOne($product, Response::HTTP_OK);
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
        $userId = auth('sanctum')->user()->id;

        $product = Product::findOrFail($id);

        if($product->ref_seller != $userId){
            return $this->errorResponse('Only seller can update product.', Response::HTTP_FORBIDDEN);
        }

        $rules = [
            'product_name' => 'required|string|min:3',
            'stock' => 'required|integer|min:1',
            'available' => 'required|boolean',
            'price' => 'required|regex:/^[0-9]+(\.[0-9][0-9]?)?$/',
            'category' => 'required|integer|exists:categories,id'
        ];

        $this->validate($request, $rules);

        $product->product_name = $request->product_name;
        $product->stock = $request->stock;
        $product->available = $request->available;
        $product->ref_seller = $userId;
        $product->ref_category = $request->category;
        $product->price = $request->price;

        $product->save();

        return $this->showOne($product, Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $userId = auth('sanctum')->user()->id;

        $product = Product::findOrFail($id);

        if($product->ref_seller != $userId){
            return $this->errorResponse('Only seller can update product.', Response::HTTP_FORBIDDEN);
        }

        $product->delete();

        return $this->showOne($product, Response::HTTP_OK);
    }
}
