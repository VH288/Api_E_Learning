<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use DB;
use Mail;
use App\Mail\VerifyMail;
use Validator;
class UserController extends Controller
{
    //
    public function register(Request $request){
        $registrationData = $request->all();
        $validate = Validator::make($registrationData,[
            'username' => 'required|unique:users',
            'password' => 'required',
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'required|email:rfc,dns|unique:users',
        ]);
        if($validate->fails()){
            return response(['message' => $validate->errors()],400);
        }
        $token = rand(100000, 999999);
        DB::table('users')->insert([
            'username' => $registrationData['username'],
            'email' => $registrationData['email'],
            'password' => $registrationData['password'],
            'firstname' => $registrationData['firstname'],
            'lastname' => $registrationData['lastname'],
            'token' => $token,
            'verified' => false,
        ]);
        try{
            $detail = ['body'=>$token];
            Mail::to($registrationData['email'])->send(new VerifyMail($detail));
            return response([
                'message' => 'Register Success',
            ], 200);

        }catch(Exception $e){
            return response([
                'message' => 'Register Success without Mail',
            ], 200);
        }
        
    }
    public function login(Request $request){
        $loginData = $request->all();
        $validate = Validator::make($loginData,[
            'username' => 'required',
            'password' => 'required',
        ]);
        if($validate->fails()){
            return response(['message' => $validate->errors(),400]);
        }
        $user = DB::table('users')
                ->where('username',$loginData['username'])->first();
        if($user == null){
            return response(['message' => 'Username Not Found'], 404);
        }
        else if($user->password != $loginData['password']){
            return response(['message' => 'Invalid Credentials'], 401);
        }
        return response([
            'message' => 'Authenticated',
            'user' => $user,
        ]);
    }
    public function verified(Request $request, $id){
        $user = User::find($id);
        if($user == null){
            return response(['message' => 'User Not Found'], 404);
        }else if($user->verified == true){
            return response(['message' => 'User Verified'], 401);
        }
        DB::table('users')->where('id',$user->id)->update(['verified'=>true]);
        $user = User::find($id);
        return response([
            'message' => 'Verified',
            'user' => $user,
        ]);
    }
    public function update(Request $request, $id){
        $updateData = $request->all();
        $validate = Validator::make($updateData,[
            'firstname' => 'required',
            'lastname' => 'required',
            'birthdate' => 'required',
            'schoolname' => 'required',
            'address' => 'required',
            'photo' => ''
        ]);
        if($validate->fails()){
            return response(['message' => $validate->errors(),400]);
        }
        $user = User::find($id);
        if($user == null){
            return response(['message' => 'User Not Found'], 404);
        }
        DB::table('users')->where('id',$user->id)->update([
            'firstname'=>$updateData['firstname'],
            'lastname'=>$updateData['lastname'],
            'birthdate'=>$updateData['birthdate'],
            'schoolname'=>$updateData['schoolname'],
            'address'=>$updateData['address'],
            'photo'=>$updateData['photo']
        ]);
        $user = User::find($id);
        return response([
            'message' => 'Updated',
            'user' => $user,
        ]);
    }
}
