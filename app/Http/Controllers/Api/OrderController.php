<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{

    public function index(Request $request)
    {
        $orders = Order::where('user_id',$request->user()->id)->orderBy('created_at','desc')->paginate(6);
        return response()->json($orders, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'phone_1' =>'required',
            'phone_2' => 'required',
            'address' => 'required',
            'city'=>'required',
            'township' => 'required',
            'payment' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()->all()], 200);
        }
        $order_code ='U'.$request->user()->id.'-'.time();
        $order = Order::create([
            'order_id'=>$order_code,
            'phone_1' => $request->phone_1,
            'phone_2' =>  $request->phone_2,
            'address' =>  $request->address,
            'city'=>  $request->city.' , '.$request->township,
            'payment' => $request->payment,
            'delivery_charges'=>$request->deliveryFee,
            'user_id'=>$request->user()->id
        ]);
        $carts = Cart::where('user_id',$request->user()->id)->get();
        foreach ($carts as $cart){
            $product = Product::find($cart->product_id);
            if ($cart->qty > $product->qty) {
                return response()->json(['error'=>[$product->name.' is not enough!Remain amount is '.$product->qty.'.']], 200);
            }
        }
        foreach ($carts as $cart){
            $product = Product::find($cart->product_id);
            $infos = json_decode($product->information);
            $price = '';
            foreach ($infos as $info) {
                if ($info->key == 'price') {
                    $price = $info->value;
                }
            }
            OrderItem::create([
                'order_id'=>$order->order_id,
                'product_id'=>$cart->product_id,
                'member_id'=>$product->user_id,
                'user_id'=>$request->user()->id,
                'qty'=>$cart->qty,
                'color'=>$cart->color,
                'size'=>$cart->size,
                'price'=>$price,
            ]);
        };
        $order->member_id = $product->user_id;
        $order->update();
        Cart::where('user_id',$request->user()->id)->delete();
        return response()->json(['success'=>'Order Crate Successful'], 200);
    }

    public function show($id,Request $request)
    {
        $order_items = OrderItem::select('order_items.*','products.name','products.image')
        ->join('products','order_items.product_id','products.id')
        ->where('order_items.user_id',$request->user()->id)
        ->where('order_items.order_id',$id)->get();
        $order = Order::where('order_id',$id)->first();
        return response()->json(['order_items'=>$order_items,'order'=>$order], 200);
    }

    public function update(Request $request, $id)
    {
        $order = Order::where('order_id',$id)->where('user_id',$request->user()->id)->first();
        $remark = json_decode($order->remark);
                array_push($remark, 'Received by user '.$request->user()->name.' at ' . now());
        $order->update([
            'status'=>'success',
            'remark' => json_encode($remark)
        ]);
        return response()->json(['success'=>'Order Accepted'], 200);
    }

    public function destroy($id)
    {
        //
    }

    public function old_detail(Request $request)
    {
        $info = Order::where('user_id',$request->user()->id)->get()->last();
        return response()->json($info, 200);
    }
}
