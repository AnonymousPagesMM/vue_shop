<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        //
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'email'=>'required|',
            'password'=>'required|string|min:8',
        ]);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()->all()], 200);
        }
        $user = User::where('email',$request->email)->first();
        if (!$user) {
            return response()->json(['error'=>['These credentials do not match our records.']], 200);
        }
        if (Hash::check($request->password,$user->password)) {
            $token = $user->createToken('vue-shop')->plainTextToken;
            $data = [
                'token'=>$token,
                'user'=>$user
            ];
            return response()->json(['success'=>$data], 200);
        }else{
            return response()->json(['error'=>['These credentials do not match our records.']], 200);
        }
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(),[
                'name'=>'required',
                'email'=>'required|string|unique:users,email',
                'password'=>'required|string|confirmed',
                'phone'=>'required',
                'gender'=>'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()->all()], 200);
        }
        $user = User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
            'phone'=>$request->phone,
            'gender'=>$request->gender,
        ]);
        $token = $user->createToken('vueshop')->plainTextToken;
        $data = [
            'user'=>$user,
            'token'=>$token
        ];
        return response()->json(['success'=>$data], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['success'=>'logout'], 200);
    }


    public function update(Request $request, $id)
    {
        //
    }
    public function destroy($id )
    {
        //
    }
}
