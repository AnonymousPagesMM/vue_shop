<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $carts = Cart::select('carts.*', 'products.name', 'products.image'
            , 'products.id as product_id', 'products.information', 'products.qty as remain_qty')
            ->join('products', 'carts.product_id', 'products.id')
            ->where('carts.user_id', $user->id)->get();
        return response()->json($carts, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'productId' => 'required',
            'qty' => 'required|numeric|min:1',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()], 200);
        }
        $user = $request->user();
        logger($user);
        $same_cart = Cart::where('user_id', $user->id)->where('product_id', $request->productId)
            ->where('color', $request->color)->first();
        $product = Product::find($request->productId);

        $carts = Cart::where('user_id', $user->id)->get();
        foreach ($carts as $cart) {
            $first_product =  Product::find($cart->product_id);
            if ($first_product->user_id != $product->user_id) {
                return response()->json(['change'=>'Are you sure to change'], 200);
            }
        }

        $cart_product = Cart::select('*', DB::raw('SUM(qty) as qty_sum'))->where('user_id', $user->id)->where('product_id', $request->productId)->first();
        if ($cart_product) {
            if ($cart_product->qty_sum + 1 > $product->qty) {
                return response()->json(['error' => [$product->name . "'s remain amount is not enough!Total " . $cart_product->qty_sum . " qty is already exit in your cart!"]]);
            }
        };
        if ($same_cart) {
            $same_cart->qty += 1;
            $same_cart->update();
        } else {
            Cart::create([
                'user_id' => $user->id,
                'product_id' => $request->productId,
                'qty' => $request->qty,
                'color' => $request->color,
                'size' => $request->size,
            ]);
        };

        return response()->json(['success' => 'Add to Cart'], 200);

    }

    public function show($id)
    {
        //
    }

    public function update(Request $request)
    {
        $cart = Cart::find($request->id);
        $cart->update(['qty' => $request->qty]);
        return response()->json(['success' => 'true'], 200);
    }

    public function destroy($id, Request $request)
    {

        Cart::where('id', $id)->where('user_id', $request->user()->id)->delete();
        return response()->json(['success' => 'true'], 200);
    }

    public function destroy_all(Request $request)
    {
        Cart::where('user_id',$request->user()->id)->delete();
        return response()->json(['success'=>'Deleted All Carts'], 200);
    }

    public function total_qty(Request $request)
    {
        $user = $request->user();
        $data = Cart::where('user_id', $user->id)->count();
        return response()->json($data, 200);
    }
}
