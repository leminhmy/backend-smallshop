<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Notification;
use App\Models\Messages;
use Illuminate\Support\Facades\DB;
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

    public function setIsSeeMessaging(Request $request){
        $userID = $request->user()->id;
        $userIDTake = $request['userid_take'];

    
        $setSee = [
            'see' => 1,
        ];
        DB::table('messages')->where('id_send',$userIDTake)->Where('id_take',$userID)->update($setSee);
      
        return response()->json("setSee success", 200); 
    }

    public function getTotalMessNotSee(Request $request){

        $userID = $request->user()->id;
        $listMessages = Messages::where('see', 0)->where('id_take',$userID)->get();

    

        return response()->json($listMessages, 200); 

    }

     public function saveNotification(Request $request){

        $notification = new Notification;
        $notification->user_id=$request->user()->id;
        $notification->user_idsend=$request['user_idsend'];
        $notification->title=$request['title'];
        $notification->body=$request['body'];
    
        if($notification->save()){
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

    public function getNotification(Request $request){
        $userID = $request->user()->id;
        $listNotification = Notification::where('user_idsend',$userID)->orderBy('created_at', 'DESC')->get()->map(function($data){
            $userSend = User::where('id',$data->user_id)->first();
            $data->image = $userSend->image;
            $data->name = $userSend->name;
            return $data;
        });

    

        return response()->json($listNotification, 200);

    }

}
