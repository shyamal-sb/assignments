<?php

namespace App\Http\Controllers;

use Validator;
use Carbon\Carbon;
use App\Models\User;
//use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    //Rgister User Method
    /**
    * Create user
    *
    * @param  [string] name
    * @param  [string] email
    * @param  [string] password
    * @param  [string] password_confirmation
    * @return [string] message
    */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|',
            'confirm_password'=>'required|same:password',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);
       
        $token = $user->createToken('LaravelAuthApp')->accessToken;
 
        //return response()->json(['token' => $token], 200);

        if($user){
            return response()->json([
                'message' => 'Successfully created user!',
                'token' => $token,
            ], 201);
        }else{
            return response()->json(['error'=>'Provide proper details']);
        }
    }

    //Login Method
    /**
    * Login user and create token
    *
    * @param  [string] email
    * @param  [string] password
    * @param  [boolean] remember_me
    * @return [string] access_token
    * @return [string] token_type
    * @return [string] expires_at
    */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember_me' => 'boolean'
        ]);

        $data = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if(Auth::attempt($data, $request->remember)) 
        {
    
            $user = Auth::user();
            //$success['token'] =  $request->user()->createToken('MyApp')->accessToken;
            $user = $request->user();
            $tokenResult = $user->createToken('Personal Access Token');
            $token = $tokenResult->token;
           
            if ($request->remember_me)
            $token->expires_at = Carbon::now()->addWeeks(1);
            $token->save();
            $success['token'] = $tokenResult->accessToken;
            $success['token_type'] = 'Bearer';
            $success['expires_at'] = Carbon::parse($token->expires_at)->toDateTimeString();
            $success['user_data'] = $user;

            return response()->json(['success' => $success], 200);
    
        }
        return response()->json(['error'=>'Unauthorised'], 401);
    }

    //Users Method to get all users    
    /**
    * Get the authenticated User
    *
    * @return [json] user object
    */
    public function user(Request $request)
    {
        return response()->json($request->user()->all());
    }

    //Logout Method
    /**
    * Logout user (Revoke the token)
    *
    * @return [string] message
    */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
        'message' => 'Successfully logged out'
        ]);
    }

}
