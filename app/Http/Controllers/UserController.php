<?php

namespace App\Http\Controllers;
use App\user;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            'name'      => 'required',
            'email'     => 'required|email',
            'password'  => 'required',
            'c_password'=> 'required|same:password',
        ]);

        if($validator->fails()){
            return response()->json([
                'Error' => $validator->errors(),
                'Status'=> 401
            ]);
        }else{
            $user = User::create([
                'name'     => $request->input('name'),
                'email'    => $request->input('email'),
                'password' => bcrypt($request->input('password'))
            ]);

            $token = $user->createToken('nApp')->accessToken;
            return response()->json([
                'Error'   => false,
                'Message' => 'Registered And Token Created',
                'Status'  => 200,
                'Token'   => $token
            ]);
        }
    }

    public function login(){
        if(Auth::attempt([
            'email' => request('email'), 
            'password' => request('password')
        ])){
            $user = Auth::user();
            $success['token'] =  $user->createToken('nApp')->accessToken;
            return response()->json(['success' => $success], 200);
        }
        else{
            return response()->json(['error'=>'Unauthorised'], 401);
        }
    }


    public function detail(){
        $users = User::all();
        if(!$users){
            return response()->json([
                'Error'   => true,
                'Message' => 'Not Found',
                'Status'  => 404
            ]);
        }else{
            return response()->json([
                'Error'   => false,
                'Message' => 'Seccess',
                'Status'  => 200,
                'Users'   => $users
            ]);
        }
    }
    
}
