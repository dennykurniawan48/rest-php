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
        try{
            $data = $user->product;
        }catch(Exception $e){
            return response()->json(['date' => $e->getMessage()]);
        }
        
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
            'category' => 'required|integer|exists:categories,id',
            'main_image' => 'required|image|mimes:jpeg,png,jpg|max:512',
            'description' => 'required|string|min:10|max:1000'
            // 'front_image' => 'image|mimes:jpeg,png,jpg|max:512',
            // 'back_image' => 'image|mimes:jpeg,png,jpg|max:512',
            // 'side_image' => 'image|mimes:jpeg,png,jpg|max:512',
        ];

        $this->validate($request, $rules);

        $image = $request->file('main_image');
        $image_uploaded_path = $image->store('', 'public');

        $product = Product::create([
            'product_name' => $request->product_name,
            'stock' => $request->stock,
            'available' => $request->available,
            'ref_seller' => $userId,
            'main_image' => 'storage/' . $image_uploaded_path,
            'ref_category' => $request->category,
            'price' => $request->price,
            'description' => $request->description
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
        $userId = auth('sanctum')->user()->id;
        $product = Product::findOrFail($id);
        
        if($product->ref_seller != $userId){
            return $this->errorResponse('Only seller can update product.', Response::HTTP_FORBIDDEN);
        }

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
