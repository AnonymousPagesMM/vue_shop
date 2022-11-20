<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    public function index()
    {
        //
    }


    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        $product = Product::select('products.*','categories.name as category_name','users.name as shop_name')
        ->join('categories','products.category_id','categories.id')
        ->join('users','users.id','products.user_id')
        ->where('products.id',$id)->first();
        return response()->json($product, 200);
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
