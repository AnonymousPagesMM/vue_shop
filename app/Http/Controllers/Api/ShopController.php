<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\SlideShow;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShopController extends Controller
{

    public function index()
    {
        $data = User::select('name', 'email', 'profile_photo_path')->where('role', 'member')->orderBy('name', 'asc')->get();
        return response()->json($data, 200);
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        logger(request('s'));
        $shop = User::where('id', $id)->where('role', 'member')->first();
        $products = Product::select('products.*', 'categories.name as category_name')
        ->where('products.user_id', $id)
            ->when(request('s'), function ($db) {
                if (strlen(request('s') > 2)) {
                    logger(request('s'));
                    $db->where('products.name', 'like', '%' . request('s') . '%')
                        ->orWhere('products.information', 'like', '%' . request('s') . '%');
                }
            })
            ->join('categories', 'products.category_id', 'categories.id')->get();
        $categories = Category::where('user_id',$id)->get();
        $slides = SlideShow::where('user_id',$id)->orderBy('custom_number','asc')->get();
        $data = [
            'info'=>$shop,
            'products'=>$products,
            'categories'=>$categories,
            'slides' => $slides,
        ];
        return response()->json($data, 200);
    }

    public function search($key)
    {
        $shops = User::select('users.name', 'users.id', 'users.profile_photo_path', DB::raw('count(products.user_id) as total_product'))->join('products', 'users.id', 'products.user_id')
            ->where('users.role', 'member')->where('users.name', 'like', '%' . $key . '%')
            ->groupBy('products.user_id')->get();
        $products = Product::where('name', 'like', '%' . $key . '%')
            ->orWhere('information', 'like', '%' . $key . '%')->get();
        return response()->json(['shops' => $shops, 'products' => $products], 200);
    }

    public function destroy($id)
    {
        //
    }
}
