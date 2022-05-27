<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function info(Request $request)
    {
        $data = $request->user();

        return response()->json($data, 200); 
    }

     public function getAllUsers(Request $request){
        $list= User::where('status', 1)->get();

        return response()->json($list, 200);
    }

    public function getAllAdmin(Request $request){
        $list= User::where('status', 2)->get();

        return response()->json($list, 200);
    }
}
