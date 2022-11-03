<?php

namespace App\Http\Controllers\Member;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{

    public function index()
    {
        $categories = Category::where('user_id',Auth::user()->id)
        ->when(strlen(request('s')) >= 2,function($q){
            $q->where('name','like','%'.request('s').'%');
        })->orderBy('updated_at','desc')
        ->get();
        return view('page.category.index',compact('categories'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required|unique:categories,name',
            'description'=>'required',
        ]);
        Category::create([
            'name'=>$request->name,
            'description'=>$request->description,
            'user_id'=>Auth::user()->id
        ]);
        return redirect()->route('member#category')->with('success','Category create successful');
    }


    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $category = Category::find($id);
        if ($category->user_id != Auth::user()->id) {
            return response()->json(['error'=>'Access Denied!'], 200);
        }else{
            return response()->json(['success'=>'Can Access','data'=>$category], 200);
        }
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'name'=>'required|unique:categories,name,'.$id,
            'description'=>'required',
        ]);
        $category = Category::find($id);
        if ($category->user_id != Auth::user()->id) {
            return abort(403, 'Unauthorized action.');
        }
        $category->update([
            'name'=>$request->name,
            'description'=>$request->description,
        ]);
        return redirect()->route('member#category')->with('success','Category update successful.');
    }

    public function destroy(Request $request)
    {
        Category::find($request->id)->delete();
        return response()->json(['success' => 'Delete Successful'], 200);
    }
}
