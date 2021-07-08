<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\Models\User;

use Carbon\Carbon;

use Validator;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function superadmin_register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8',
            'role_id' => 'required'
        ]);

        if($validator->fails()){
            return response(['message' => 'Validation errors', 'errors' =>  $validator->errors(), 'status' => false], 422);
        }

        $input = $request->all();
            
        if(!User::where('email', $input['email'])->first()) {
            $input = $request->all();
            $input['password'] = Hash::make($input['password']);
            
            $user = User::create($input);
            
            /** User authentication access token generated **/
            $user->createToken('api_groupesecurexpert')->accessToken;
            $newUser =  $user->email;

            return response(['data' => [], 'message' => $newUser . ' account created successfully!', 'status' => true]);
        } else {
            return response(['data' => [], 'message' => 'Unauthorized', 'status' => false]);
        }

    }


    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8',
            'role_id' => 'required'
        ]);

        if($validator->fails()){
            return response(['message' => 'Validation errors', 'errors' =>  $validator->errors(), 'status' => false], 422);
        }

        $rol = User::find($request->user()->id)->role->id;

        if ( $rol == 1 ) {
            $input = $request->all();
            
            if(User::where('email', $input['email'])->first()) {
                return response([
                    'status' => false,
                    'message' => 'There is a registered user with this account',
                    'data' => []
                ]);
            }
            
            $input['password'] = Hash::make($input['password']);
            
            $user = User::create($input);
            
            /**Take note of this: Your user authentication access token is generated here **/
            $user->createToken('api_groupesecurexpert')->accessToken;
            $newUser =  $user->email;

            return response(['data' => [], 'message' => $newUser . ' account created successfully!', 'status' => true]);
        } else {
            return response(['data' => [], 'message' => 'Unauthorized', 'status' => false]);
        }

    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);
        
        if($validator->fails()) {
            return response(['status' => false, 'message' => $validator->errors()->all(), 'data' => []]);
        }

        $user = User::where('email', $request->email)->first();

        if($user && $user->active == 1) {

            if (Hash::check($request->password, $user->password)) {
                $token = $user->createToken('api_groupesecurexpert')->accessToken;
                $lastLoggedIn = Carbon::now();
                $user->last_logged_in = $lastLoggedIn->toDateTimeString();
                $user->save();
                
                $role = $user->role->name;
                $logged['name'] = $user->name;
                $logged['lastname'] = $user->last_name;
                $logged['email'] = $user->email;
                $logged['last_logged_in'] = $user->last_logged_in;
                $logged['role'] = $role;

                return response([ 'status' => true, 'message' => '', 'data'=>['token' => $token, 'user' => $logged ] ]);
            } else {
                return response([ 'status' => false, 'message' => 'Password mismatch', 'data' => [] ]);
            }

        } else {
            return response(['status' => false, 'message' => 'User does not exist', 'data' => []]);
        }
    }

    public function logout(Request $request)
    {
        $token = $request->user()->token();
        $token->revoke();
        return response(['status' => true, 'message' => 'You have been successfully logged out!']);
    }
    
    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|min:8',
            'new_password' => 'required|min:8'
        ]);

        if($validator->fails()){
            return response(['message' => 'Validation errors', 'errors' =>  $validator->errors(), 'status' => false], 422);
        }

        $email = $request->user()->email;        
        $userUpdatePassword = User::where('email', $email)->first();

        $currentPassword = $request->current_password;

        if (Hash::check($currentPassword, $userUpdatePassword->password)) {
            $newPassword = $request->new_password;
            $newPassword = Hash::make($newPassword);
    
            $userUpdatePassword->password = $newPassword;
    
            if(!$userUpdatePassword->save()) {
                return response(['status'=>false, 'message'=>'Password changed fail', 'data'=>'']);
            } else {
                return response(['status'=>true, 'message'=>'Password changed successful', 'data'=>'']);
            }
        } else {
            return response(['status'=>false, 'message'=>'Password dosn´t mismatch', 'data'=>[]]);
        }

    }

    public function updateProfile(Request $request)
    {
        $user = User::find($request->user()->id);
        $user->name = $request->name ?? $user->name;
        $user->last_name = $request->lastname ?? $user->last_name;
        $user->email = $request->email ?? $user->email;
        
        $user->role()->associate($request->role != '' ? $request->role : $user->role->id);

        if ($user->save()) {
            return response(['status'=>true, 'message'=>'Successfully updated!', 'data'=>[
              'name' => $user->name,
              'lastname' => $user->last_name,
              'email' => $user->email,
              'last_logged_in' => $user->last_logged_in,
              'role' => $user->role->name
              ]]);
        }

    }

    public function getUsers(Request $request) {
        $rol = User::find($request->user()->id)->role->name;

        if($rol == 'Admin') {
            $users = User::where('email', '!=', $request->user()->email)->get();
            if(!$users) {
                return response([ 'status'=>false, 'message'=>'No users', 'data'=>[] ]);
            }
            return response([ 'status'=>true, 'message'=>'', 'data'=>[$users] ]);
        } else {
            return response(['status'=>false, 'message'=>'Unauthorized', 'data'=>[]]);
        }

    }

    public function deleteUser($id, Request $request) {
        $rol = User::find($request->user()->id)->role->name;

        if($rol == 'Admin') {
            $users = User::findOrFail($id);
            if(!$users->delete()) {
                return response([ 'status'=>false, 'message'=>'Can´t deleted the user', 'data'=>[] ]);
            }
            return response([ 'status'=>true, 'message'=>'User deleted', 'data'=>[$users] ]);
        } else {
            return response(['status'=>false, 'message'=>'Unauthorized', 'data'=>[]]);
        }

    }

    public function activeUser($id, Request $request) {
        $rol = User::find($request->user()->id)->role->name;

        if($rol == 'Admin') {
            $user = User::findOrFail($id);
            $user->active = !$user->active;
            if(!$user->save()) {
                return response([ 'status'=>false, 'message'=>'Can´t deleted the user', 'data'=>[] ]);
            }
            return response([ 'status'=>true, 'message'=>'User deleted', 'data'=>[] ]);
        } else {
            return response(['status'=>false, 'message'=>'Unauthorized', 'data'=>[]]);
        }

    }

    public function profile(Request $request) {
        $user = $request->user();

        if($user) {
            $role = User::find($request->user()->id)->role->name;

            $data['name'] = $request->user()->name;
            $data['lastname'] = $request->user()->last_name;
            $data['email'] = $request->user()->email;
            $data['last_logged_in'] = $request->user()->last_logged_in;
            $data['role'] = $role;

            return response(['status'=>true, 'message'=>'', 'data'=>$data]);
        }

        return response(['status'=>false, 'message'=>'No user', 'data'=>[]]);

    }

}
