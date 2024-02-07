<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

use Laravel\Passport\RefreshToken;
use Laravel\Passport\Token;

class ApiAuthController extends Controller
{
    //register method
    public function register (Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            //'password' => 'required|string|min:6|confirmed',
            'password' => 'required|string|min:6',
            'type' => 'integer',
        ]);
        if ($validator->fails())
        {
            return response(['errors'=>$validator->errors()->all()], 422);
        }
        $request['password']=Hash::make($request['password']);
        $request['remember_token'] = Str::random(10);
        $request['type'] = $request['type'] ? $request['type']  : 0;
        $user = User::create($request->toArray());
        //$token = $user->createToken('Laravel Password Grant Client')->accessToken;
        $token = $user->createToken('myApp')->accessToken;
        $response = ['token' => $token];
        return response($response, 200);
    }


    //login
    public function login (Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            //'password' => 'required|string|min:6|confirmed',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails())
        {
            return response(['errors'=>$validator->errors()->all()], 422);
        }
        $user = User::where('email', $request->email)->first();
        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                //$token = $user->createToken('Laravel Password Grant Client')->accessToken;
                $token = $user->createToken('myApp')->accessToken;
                $response = ['token' => $token];
                return response($response, 200);
            } else {
                $response = ["message" => "Password mismatch"];
                return response($response, 422);
            }
        } else {
            $response = ["message" =>'User does not exist'];
            return response($response, 422);
        }
    }


    //logout
    public function logout (Request $request) {
        //$token = $request->user()->token();
        //var_dump($request); 
        //var_dump($toke); 
        //exit('edit');
        //$header = $request->header('Authorization');
        //var_dump($header);
        //
        //return Auth::user()->token();

        //$result = $request->user()->currentAccessToken()->revoke();                  
        //if($result){
        //        $response = response()->json(['error'=>false,'message'=>'User logout successfully.','result'=>[]],200);
        //  }else{
        //        $response = response()->json(['error'=>true,'message'=>'Something is wrong.','result'=>[]],400);            
        //  }   
        //return $response; 
        //var_dump($request);
        $token = $request->user()->token();
        $token->revoke();
        $response = ['message' => 'You have been successfully logged out!'];
        return response($response, 200);
    }



}
