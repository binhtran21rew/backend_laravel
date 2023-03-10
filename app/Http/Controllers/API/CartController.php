<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function addtocart(Request $request){
        if(auth('sanctum')->check()){
            $user_id=auth('sanctum')->user()->id;
            $product_id = $request->product_id;
            $product_quantity = $request->product_quantity;

            $productCheck = Product::where('id',$product_id)->first();
            if($productCheck){
                if(Cart::where('product_id', $product_id)->where('user_id', $user_id)->exists()){
                    return response()->json([
                        'status' => 409,
                        'message' =>$productCheck->name. 'Already Added to Cart',
                    ]);
                }else{
                    $cartitem = new Cart;
                    $cartitem-> user_id = $user_id;
                    $cartitem-> product_id = $product_id;
                    $cartitem-> product_quantity = $product_quantity;
                    $cartitem->save();
                    return response()->json([
                        'status' => 201,
                        'message' => 'Added to Cart',
                    ]);
                }
            }else{
                return response()->json([
                    'status' => 404,
                    'message' => 'Product not found',
                ]);
            }
        }else{
            return response()->json([
                'status' => 401,
                'message' => 'Login to Add to Cart',
            ]);
        }
    }
    public function viewCart(){
        if(auth('sanctum')->check()){
            $user_id = auth('sanctum')->user()->id;
            $cart = Cart::where('user_id', $user_id)->get();
            return response()->json([
                'status' => 200,
                'cart' => $cart,
            ]);
        }else{
            return response()->json([
                'status' => 401,
                'message' => 'Login to into Cart',
            ]);
        }
    }
    public function updateQuantity($id, $scope){
        if(auth('sanctum')->check()){
            $user_id = auth('sanctum')->user()->id;
            $cart = Cart::where('id', $id)
                    ->where('user_id', $user_id)
                    ->first();
            
            if($scope == "inc"){
                $cart->product_quantity += 1; 
            }else if($scope == "dec"){
                if($cart->product_quantity > 1){
                    $cart->product_quantity -= 1; 
                }else{
                    return response()->json([
                        'status' => 409,
                        'message' => 'The quantity of product should be greater than 1',
                    ]);
                }
            }
            $cart->update();
            return response()->json([
                'status' => 200,
                'message' => 'Update your quantity successfully',
            ]);
        }else{
            return response()->json([
                'status' => 401,
                'message' => 'Please logon account to update your cart',
            ]);
        }
    }
}
