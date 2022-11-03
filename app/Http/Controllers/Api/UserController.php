<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\OtpCode;
use App\Models\Session;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $validator = $this->registerValidation($request);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()->all()], 200);
        }else{
            User::create([
                'name'=>$request->name,
                'email'=>$request->email,
                'password'=>Hash::make($request->password),
                'phone'=>$request->phone,
                'gender'=>$request->gender,
            ]);
            $this->otpCreate($request->email);
            return response()->json(['success'=>'Please Activate'], 200);
        }
    }
    //Active Accout
    public function active(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'code'=>'required|string|max:6|min:6',
            'email'=>'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()->all()], 200);
        }else{
            $user = User::where('email',$request->email)->first();
            $otp = OtpCode::where('email',$request->email)->get()->last();
            if (!$otp) {
            return response()->json(['error'=>['Please Request Otp Code Again!']], 200);
            }
            if ($otp->code == $request->code) {
                $token  = $this->sessionCreate($request,$user);
                $user->update([
                    'activate'=>true
                ]);
                $this->otpDeleteAll($request->email);
            return response()->json(['success'=>'Active Success.','data'=>$token], 200);
            }else{
                return response()->json(['error'=>['The Otp code is not right!']], 200);
            }
        }
    }

    //User Login
    public function login(Request $request)
    {
        $validator = $this->loginValidator($request);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()->all()], 200);
        }else{
            $user = User::where('email',$request->email)->first();
            if (!$user) {
                return response()->json(['error'=>['There is no account!']], 200);
            }
            if (Hash::check($request->password,$user->password)) {
                $data = $this->sessionCreate($request,$user);
                return response()->json(['success'=>'Login success.','data'=>$data], 200);
            }else{
                return response()->json(['error'=>['Password is wrong.']], 200);
            }
        }
    }


    // =================================================================
    private function loginValidator($request){
        $result = Validator::make($request->all(),[
            'email'=>'required',
            'password'=>'required|string|min:8'
        ]);
        return $result;
    }

    private function registerValidation($request)
    {
        $result = Validator::make($request->all(),[
            'name'=>'required|unique:users,email',
            'email'=>'required',
            'password'=>'required|string|min:8',
            'confirm_password'=>'required|same:password',
            'phone'=>'required',
            'gender'=>'required'
        ]);
        return $result;
    }

    //Otp Create
    private function otpCreate($email)
    {
        OtpCode::create([
            'email'=>$email,
            'code'=>random_int(100000,999999),
        ]);
    }

    private function otpDeleteAll($email){
        OtpCode::where('email',$email)->delete();
    }

    private function sessionCreate($request,$user){
        return Session::create([
            'user_id'=>$user->id,
            'token'=>time().'-'. Str::random(20).'-'.uniqid(),
            'user_agent'=>$request->device
        ]);
    }
}
