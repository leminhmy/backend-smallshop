<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shoes;
use Illuminate\Support\Facades\DB;

class ShoesController extends Controller
{
    //
    public function index(){
        $info = Shoes::all();
        return json_decode($info);
    }

    public function submitInfo(Request $request){
        //file convert image
        $images = $request->file('image');
        $imageName = '';
        if($request->hasFile('image')){
            foreach($images as $image){
            $new_name = rand().'.'.$image->getClientOriginalName();
            // $image->move(public_path('/uploads/shoes'),$new_name);
            $imageName = $imageName.$new_name.",";
        }


        }else{
            return response()->json('image null');
        }
        $imagedb=$imageName;
        $imageArray = explode(",",$imagedb);
        $index = count($imageArray);
        //imagethumbnail
        $imageThumbnail = $imageArray[$index - 2];

        //imagelist
        $arraynew = $imageArray;
        array_splice($arraynew,$index-2,2); 
        $listimage = implode(",",$arraynew).',';


        //data
        $info = new Shoes;
        $info->name=$request[];
        $info->sub_title=$request->post('sub_title');
        $info->type_id=$request->post('type_id');
        $info->description=$request->post('description');

        if($info->save()){
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

    public function updateStatus($id, $status){

        $updateStatus = [
            'status' => $status
        ];

        DB::table('orders')->where('id', $id)->update($updateStatus);

        $order = Orders::where('id',$id)->get();

         return response()->json(['message' => trans('messages.updated_successfully'),'order_id'=>$order], 200);

    }

    
}
