<?php

namespace App\Http\Controllers\Member;

use App\Models\SlideShow;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class SlideShowController extends Controller
{

    public function index()
    {
        $slides = SlideShow::where('user_id',Auth::user()->id)->orderBy('custom_number','asc')->get();
        return view('page.slideshow.list',compact('slides'));
    }

    public function number_change($old_num,$new_num)
    {
        $old_slide = SlideShow::where('user_id',Auth::user()->id)->where('custom_number',$old_num)->first();
        $new_slide = SlideShow::where('user_id',Auth::user()->id)->where('custom_number',$new_num)->first();
        $old_slide->update([
            'custom_number'=>$new_num,
        ]);
        $new_slide->update([
            'custom_number'=>$old_num,
        ]);
        return back();
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'=>'required',
            'image'=>'required|image',
        ]);
        if ($request->hasFile('image')) {
            $image = 'SS-'.Auth::user()->id.time().'.'.$request->file('image')->getClientOriginalExtension();
            $request->file('image')->move(public_path('images'),$image);
        }
        $order = SlideShow::create([
            'title'=>$request->title,
            'image'=>$image,
            'link'=>$request->link,
            'description'=>$request->description,
            'user_id'=>Auth::user()->id
        ]);
        $this->customNumber();
        return back()->with('success','Slide Show Create Successful.');
    }

    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        //
    }


    public function destroy($id)
    {
        SlideShow::where('user_id',Auth::user()->id)->where('id',$id)->delete();
        $this->customNumber();
        return back();
    }

    private function customNumber(){
        $slide_shows = SlideShow::where('user_id',Auth::user()->id)->orderBy('custom_number','asc')->get();
        foreach ($slide_shows as $key => $slide_show) {
            $loop_slide = SlideShow::find($slide_show->id);
            $loop_slide->update([
                'custom_number'=>$key+1
            ]);
        }
    }
}
