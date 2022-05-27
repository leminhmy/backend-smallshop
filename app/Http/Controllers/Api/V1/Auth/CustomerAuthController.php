<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\CentralLogics\Helpers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class CustomerAuthController extends Controller
{
     public function login(Request $request)
    {
          $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required|min:6'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $data = [
            'email' => $request->email,
            'password' => $request->password
        ];
        
        if (auth()->attempt($data)) {
            //auth()->user() is coming from laravel auth:api middleware
            $token = auth()->user()->createToken('RestaurantCustomerAuth')->accessToken;
            if(!auth()->user()->status)
            {
                $errors = [];
                array_push($errors, ['code' => 'auth-003', 'message' => trans('messages.your_account_is_blocked')]);
                return response()->json([
                    'errors' => $errors
                ], 403);
            }
            $idUser = auth()->user()->id;
            $updateTokenMessages = [
                'token_messages' => $request->token_messages,
            ];

            DB::table('users')->where('id', $idUser)->update($updateTokenMessages);
          
            return response()->json(['token' => $token, 'is_phone_verified'=>auth()->user()->is_phone_verified], 200);
        } else {
            $errors = [];
            array_push($errors, ['code' => 'auth-001', 'message' => 'Unauthorized.']);
            return response()->json([
                'errors' => $errors
            ], 401);
        }
    }
    
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(),[
              'name' => 'required',
              'email' => 'required|unique:users', 
              'phone' => 'required|unique:users', 
              'password' => 'required|min:6',
        ],[
            'name.required' => 'The first name field is required.',
            'name.required' => 'The phone field is required.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $user = User::create([
            'name'=> $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => bcrypt($request->password),
            'token_messages' => $request->token_messages,

        ]);

         $token = $user->createToken('RestaurantCustomerAuth')->accessToken;

       
        return response()->json(['token' => $token,'token_messages'=>$user->token_messages ,'name'=>$user->name,'email'=>$user->email,'is_phone_verified' => 0, 'phone_verify_end_url'=>"api/v1/auth/verify-phone" ], 200);
    }

   
}
