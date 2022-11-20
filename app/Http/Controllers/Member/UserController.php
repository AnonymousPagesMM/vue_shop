<?php

namespace App\Http\Controllers\Member;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Models\OtpCode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function update_image(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()], 200);
        }
        $user = User::find(Auth::user()->id);
        $path = 'images/'.$user->profile_photo_path;
        if (File::exists($path)) {
            File::delete($path);
        }
        $image = 'user-' . $user->id . '-profile-' . time() . '.' . $request->file('image')->getClientOriginalExtension();
        $request->file('image')->move(public_path('images'), $image);
        $user->update([
            'profile_photo_path' => $image,
        ]);
        return response()->json(['success' => "Profile Image Updated.",'path'=>$user->profile_photo_path], 200);

    }

    public function activate()
    {
        if (Auth::user()->activate) {
            return redirect()->route('dashboard');
        }
        OtpCode::otp_sent(Auth::user()->email);
        return view('auth.account-active');
    }
}
