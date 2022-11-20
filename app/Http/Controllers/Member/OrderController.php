<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{

    public function index()
    {
        $orders = Order::where('member_id', Auth::user()->id)
            ->when(request('status'), function ($db) {
                $db->where('status', request('status'));
            })->when(strlen(request('s') > 2),function($db){
                $db->where('order_id','like','%'.request('s').'%');
            })
            ->orderBy('created_at', 'desc')->paginate(10);
        return view('page.order.list', compact('orders'));
    }

    public function delivered($id,Request $request)
    {
        $order = Order::where('member_id', Auth::user()->id)->where('order_id', $id)->first();
        $remark = json_decode($order->remark);
                array_push($remark, 'Delivered at ' . now());
        $order->update([
            'status' => 'delivered',
            'remark' => json_encode($remark)
        ]);
        return back();
    }

    public function deliver_date($id,Request $request)
    {
        $order = Order::where('member_id', Auth::user()->id)->where('order_id', $id)->first();
        $order->update([
            $order->deliver_date = $request->date,
        ]);
        return back();
    }

    public function show($id)
    {
        $order = Order::where('member_id', Auth::user()->id)->where('order_id', $id)->first();
        return view('page.order.info', compact('order'));
    }

    public function reject($id, Request $request)
    {
        $order = Order::where('member_id', Auth::user()->id)->where('order_id', $id)->first();
        if ($order->status == 'pending') {
            if ($order->remark == '') {
                $remark = [
                    'Reject at ' . now(),
                    'Reject reason is' . $request->reason,
                ];
            } else {
                $remark = json_decode($order->remark);
                array_push($remark, 'Reject at ' . now());
                array_push($remark, 'Reject reason is' . $request->reason);
            }
            $order->update([
                'status' => 'reject',
                'remark' => json_encode($remark),
            ]);
            return back();
        }
    }
    public function remark($id,Request $request)
    {
        $order = Order::where('member_id', Auth::user()->id)->where('order_id', $id)->first();
        if ($order->remark == '') {
            $remark = [
                $request->remark .' - '.now()
            ];
        } else {
            $remark = json_decode($order->remark);
            array_push($remark,$request->remark .' - '.now());
        }
        $order->update([
            'remark' => json_encode($remark),
        ]);
        return back();

    }
    public function accept($id)
    {
        $order = Order::where('member_id', Auth::user()->id)->where('order_id', $id)->first();
        if ($order->status == 'pending') {
            foreach ($order->order_items as $item) {
                if ($item->qty > $item->product->qty) {
                    return back()->with('error', $item->product->name . 'is not enough');
                }
            }
            if ($order->remark == '') {
                $remark = [
                    'Accept at ' . now(),
                ];
            } else {
                $remark = json_decode($order->remark);
                array_push($remark, 'Accept at ' . now());
            }
            $order->update([
                'status' => 'accept',
                'remark' => json_encode($remark),
            ]);
            foreach ($order->order_items as $item) {
                $product = Product::find($item->product_id);
                $product->update([
                    'qty' => $product->qty - $item->qty,
                ]);
            }
            return back();
        } else {

        }
    }

    public function destroy($id)
    {
        //
    }
}
