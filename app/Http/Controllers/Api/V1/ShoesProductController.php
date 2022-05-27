<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Models\Shoes;
use App\Models\ShoesType;
use App\Models\Orders;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ShoesProductController extends Controller
{

    public function get_shoes_type(Request $request)
    {
        $list= ShoesType::where('parent_id', 1)->get();

        $data = [
                'total_size' => $list->count(),
                'parent_id' => 1,
                'shoes_type' =>$list
        ];

        return response()->json($data, 200);
    }

    public function get_shoes_product(Request $request)
    {
        $list= Shoes::orderBy('created_at', 'DESC')->get();

        foreach ($list as $item){
                $item['description']=strip_tags($item['description']);
                $item['description']=$Content = preg_replace("/&#?[a-z0-9]+;/i"," ",$item['description']);
            }
            
        $data = [
                'total_size' => $list->count(),
                'products' =>$list
        ];

        return response()->json($data, 200);
    }


    public function get_leather_shoes_proucts(Request $request){
        $list = Shoes::where('type_id',2)->orderBy('created_at', 'DESC')->get();

            foreach ($list as $item){
                $item['description']=strip_tags($item['description']);
                $item['description']=$Content = preg_replace("/&#?[a-z0-9]+;/i"," ",$item['description']);
            }

            $data = [
                'total_size' => $list->count(),
                'type_id' => 2,
                'offset' => 0,
                'products' => $list
            ];

            return response()->json($data, 200);
    }

   public function updateStatus(Request $request,$id, $status){


        if($request->has('message'))
        {
              $message = $request['message'];
                 $updateStatus = [
            'status' => $status,
            'message' => $message
                    ];
        }
        else{
              $updateStatus = [
            'status' => $status,
                    ];
        }
      

        DB::table('orders')->where('id', $id)->update($updateStatus);

        $order = Orders::where('id',$id)->get();

         return response()->json(['message' => trans('messages.updated_successfully'),'order_id'=>$order], 200);

    }

    public function update(Request $request, $id)
    {

        $shoes = [
            'name' => $request['name'],
            'sub_title' => $request['sub_title'],
            'price' => $request['price'],
            'type_id'=>$request['type_id'],
            'description' => $request['description'],
            'color' => $request['color'],
            'size' => $request['size'],
            // 'img' => $request->img,
            'released'=> $request['released'],
            'created_at' => now(), 
            'updated_at' => now()
        ];

        DB::table('shoes')->where('id', $id)->update($shoes);

        $productdetail = Shoes::where('id', $id)->get();

        return response()->json(['message' => trans('messages.updated_successfully'),'productupdate'=>$productdetail], 200);
    }

    public function delete($id){
        $shoes = Shoes::find($id);
        $result = $shoes->delete();
        if($result)
        {
            return ["result" => "delete_successfully"];
        }else{
            return ["result" => "delete_faill"];
        }

    }

    public function uploadFile(Request $request){

        // upload only img
        // $image = $request->file('image');
        // if($request->hasFile('image')){
        //      $new_name = rand().'.'.$image->getClientOriginalName();
        //     $image->move(public_path('/uploads/shoes'),$new_name);
        //     return response()->json($new_name);
        // }else{
        //     return response()->json('image null');
        // }

        // upload multiple img
       
        $images = $request->file('image');
        $id =  $request['id'];
        $imageName = '';
        if($request->hasFile('image')){
            foreach($images as $image){
            $new_name = rand().'.'.$image->getClientOriginalName();
            $image->move(public_path('/uploads/shoes'),$new_name);
            $imageName = $imageName.$new_name.",";
        }


        }else{
            return response()->json('image null');
        }
        $imagedb=$imageName;

        $product = Shoes::find($id);
        $listimg = $product['listimg'].$imagedb;
        $shoes = [
            'listimg' => $listimg,
        ];

        DB::table('shoes')->where('id', $id)->update($shoes);

        $productdetail = Shoes::where('id', $id)->get();

        return response()->json(['message' => trans('messages.updated_successfully'),'productupdate'=>$productdetail], 200);
    }

    public function deleteImg($id, $nameimg){

        $product = Shoes::find($id);
        $listimg_new = $product['listimg'];
        $nameimgdelete = $nameimg.',';
        $listimg_new2 = str_replace($nameimgdelete, '',$listimg_new);

        $imgshoesnew = [
            'listimg' => $listimg_new2,
        ];

        DB::table('shoes')->where('id', $id)->update($imgshoesnew);

        $productdetail = Shoes::where('id', $id)->get();

         return response()->json(['message' => trans('messages.updated_successfully'),'productNew'=>$productdetail], 200);

    }
 

    public function addToProduct(Request $request){
        //file convert image
        $images = $request->file('image');
        $imageName = '';
        if($request->hasFile('image')){
            foreach($images as $image){
            $new_name = rand().'.'.$image->getClientOriginalName();
            $image->move(public_path('/uploads/shoes'),$new_name);
            $imageName = $imageName.$new_name.",";
        }


        }else{
            return response()->json('image null');
        }
        $imagedb=$imageName;
        $imageArray = explode(",",$imagedb);
        $index = count($imageArray);
        //imagethumbnail
        $imageThumbnail = 'shoes/'.$imageArray[$index - 2];

        //imagelist
        $arraynew = $imageArray;
        array_splice($arraynew,$index-2,2); 
        $listimage = implode(",",$arraynew).',';


        //data
        $shoes = new Shoes;
        $shoes->name=$request['name'];
        $shoes->sub_title=$request['sub_title'];
        $shoes->price=(int)$request['price'];
        $shoes->color=$request['color'];
        $shoes->size=$request['size'];
        $shoes->type_id=(int)$request['type_id'];
        $shoes->description=$request['description'];
        $shoes->img=$imageThumbnail;
        $shoes->listimg=$listimage;
        $shoes->released=(int)$request['released'];

        if($shoes->save()){
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

}
