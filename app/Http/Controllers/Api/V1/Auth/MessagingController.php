<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Messages;
class MessagingController extends Controller
{
    //
    public function sendMessages(Request $request)
    {

            $userID = $request->user()->id;


            //check file image if has save
            $image = $request->file('image');
            $name_img = '';
            if($request->hasFile('image'))
            {
                $name_img = $userID.rand().'.'.$image->getClientOriginalName();
                $image->move(public_path('/uploads/messages'),$name_img);
                $name_img = 'messages/'.$name_img;
            }
            $messaging = '';
            if($request->has('messaging')){
                 $messaging = $request['messaging'];

                }
            //send
            $message = new Messages;
            $message->id_send = $userID;
            $message->id_take = (int)$request['id_take'];
            $message->messaging = $messaging;
            $message->image = $name_img;
            $message->created_at = now();
            $message->updated_at = now();



        if($message->save()){
            return response()->json([
                'code' => 0,
                'msg' => 'Success'
            ]);
        }
        return response()->json([
                'code' => -1,
                'msg' => 'fail'
        ]);
    }

    public function getMessages(Request $request){
        $userID = $request->user()->id;


    
        $listMessages = Messages::where('id_send',$userID)->orWhere('id_take',$userID)->get();

      

        return response()->json($listMessages, 200); 
    }
}
