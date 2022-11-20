<?php

namespace App\Http\Controllers\Member;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{

    public function index()
    {
        $products = Product::where('user_id', Auth::user()->id)->when(strlen(request('s')) >= 2, function ($q) {
            $q->where('name', 'like', '%' . request('s') . '%')
            ->orWhere('information', 'like', '%' . request('s') . '%')
            ->orWhere('description', 'like', '%' . request('s') . '%');
        })->orderBy('id', 'desc')
            ->paginate(10);
        return view('page.product.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::where('user_id',Auth::user()->id)->orderBy('name','asc')->get();
        return view('page.product.add',compact('categories'));
    }

    public function store(Request $request)
    {
        $this->productValidator($request, 'store');
        $product = Product::create($this->productSave($request, null));
        return redirect()->route('member#product')->with('success', 'Product Create Successful!');
    }

    public function show($id)
    {
        $product = Product::find($id);
        $this->checkUser($product);
        return view('page.product.view',compact('product'));
    }

    public function edit($id)
    {
        $product = Product::find($id);
        if ($product) {
            $this->checkUser($product);
            $categories = Category::where('user_id',Auth::user()->id)->orderBy('name','asc')->get();
            return view('page.product.edit', compact('product','categories'));
        }
        return abort(404);
    }

    public function update(Request $request, $id)
    {
        $this->productValidator($request, 'update');
        $product = Product::find($id);
        $this->checkUser($product);
        $product->update($this->productSave($request, $product));
        return redirect()->route('member#product')->with('success', 'Product Update Successful!');
    }

    public function destroy(Request $request)
    {
        $product = Product::find($request->id);
        $this->imageDelete($product->image);
        $product->delete();
        return response()->json(['success' => 'Delete Successful'], 200);
    }

    // ==================================
    private function productValidator($request, $functionName)
    {
        $request->validate([
            'name' => 'required',
            'categoryId' => 'required',
            'qty' => 'required',
            'information' => 'required',
            'description' => 'required',
            'image'=>'image',
        ]);
        if ($functionName == 'store') {
            $request->validate([
                'image' => 'required',
            ]);
        }
    }
    private function productSave($request, $db)
    {
        $return = [
            'name' => $request->name,
            'category_id' => $request->categoryId,
            'qty' => $request->qty,
            'information' => $request->information,
            'description' => $request->description,
            'user_id' => Auth::user()->id,
        ];
        if ($request->hasFile('image')) {
            $image = Auth::user()->id . '-' . time() . '-' . uniqid() . '.' . $request->file('image')->getClientOriginalExtension();
            if ($db) {
                $this->imageDelete($db->image);
                $this->imageStore($request, $image);
            } else {
                $this->imageStore($request, $image);
            }
            $return['image'] = $image;
        }
        return $return;
    }

    private function imageDelete($image)
    {
        $path = 'images/' . $image;
        if (File::exists($path)) {
            File::delete($path);
        }
    }

    private function imageStore($request, $image_name)
    {
        $request->file('image')->move(public_path('images'), $image_name);
    }
    private function checkUser($product)
    {
        if ($product->user_id != Auth::user()->id) {
            return abort(403, 'Unauthorized action.');
        }
    }
}
