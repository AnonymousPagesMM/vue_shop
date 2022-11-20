<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OtpCode;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OtpController extends Controller
{
    public function create(Request $request)
    {
        OtpCode::otp_sent($request->email);
        return response()->json(['success','Otp code sent.'], 200);
    }

    public function check_token(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'code'=>'required|string|min:6',
            'email'=>'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()->all()], 200);
        }
        $otp = OtpCode::where('email',$request->email)->get()->last();
        if ($otp) {
            if ($otp->created_at >= Carbon::now()->subMinutes(15)) {
                if ($otp->code == $request->code) {
                    $user = User::where('email',$request->email)->first();
                    $user->update([
                        'activate'=>true
                    ]);
                    $otp->delete();
                    return response()->json(['success'=>'Success'], 200);
                }else{
                    return response()->json(['error'=>['Otp Code is not Math']], 200);
                }
            }else{
                return response()->json(['error'=>['Otp Code is Expired!']]);
                $otp->delete();
            }
        }else{
            return response()->json(['error',['Please Request OTPs again!']], 200);
        }
    }
}
