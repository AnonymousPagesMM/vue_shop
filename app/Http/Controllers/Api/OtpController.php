<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OtpCode;
use Illuminate\Http\Request;

class OtpController extends Controller
{
    public function create(Request $request)
    {
        OtpCode::where('email',$request->email)->delete();
        OtpCode::create([
            'email'=>$request->email,
            'code'=>random_int(100000,999999),
        ]);
        return response()->json(['success','Otp code sent.'], 200);
    }
}
