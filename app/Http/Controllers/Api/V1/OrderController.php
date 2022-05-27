<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use App\Models\Orders;
use App\Models\Shoes;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{

    
    
    public function placeorder(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'order_amount' => 'required',
            'address' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $product_price = 0;

        $order = new Orders();
        $order->user_id=$request->user()->id;
        $order->phone=$request->user()->phone;
        $order->address=$request['address'];
        $order->order_amount=$request['order_amount'];
        $order->created_at = now(); //checked
        $order->updated_at = now();//checked
    
       
 
        foreach($request['cart'] as $c){
            $product = Shoes::find($c['id']);
            if($product){

                $price = $product['price'];

                 $or_d = [
                        'product_id' => $c['id'], //checked
                        'quantity' => $c['quantity'], //checked
                        'name' => $c['name'],
                        'color' => $c['color'], //checked
                        'img' => $c['img'],
                        'size' => $c['size'],
                        'shoes_details' => json_encode($product),
                        'price' => $price, //checked
                        'created_at' => now(), //checked
                        'updated_at' => now(), //checked 
                    ];
                    $product_price += $price*$or_d['quantity'];

                    $order_items[] = $or_d;
            }else {
                    return response()->json([
                        'errors' => [
                            ['code' => 'food', 'message' => 'not found!']
                        ]
                    ], 401);
                }

        }

        

            try {
           $save_order= $order->id;
           $total_price= $product_price;
           $order->order_amount = $total_price;
            $order->save(); 
            foreach ($order_items as $key => $item) {
                $order_items[$key]['orders_id'] = $order->id;
            }

            OrderItem::insert($order_items);

            return response()->json([
                'message' => trans('messages.order_placed_successfully'),
                'order_id' =>  $save_order,
                
            ], 200);
        } catch (\Exception $e) {
            return response()->json([$e], 403);
        }


         return response()->json([
            'errors' => [
                ['code' => 'order_time', 'message' => trans('messages.failed_to_place_order')]
            ]
        ], 403);
    
    }


    public function get_order_list(Request $request)
    {

        $userID = $request->user()->id;


        $listOrders = Orders::where('user_id', $userID)->orderBy('created_at', 'DESC')->get()->map(function ($data){
            $order_items = OrderItem::where('orders_id', $data->id)->get();
            $data->order_items= $order_items;
            
            return $data;
        });




        $data = [
            'total_size' => $listOrders->count(),
            'orders' => $listOrders
        ];

        return response()->json($data, 200); 

    }

    public function get_order_list_admin(Request $request)
    {
        $listOrdersAdmin = Orders::orderBy('created_at', 'DESC')->get()->map(function ($data){
             $order_items = OrderItem::where('orders_id', $data->id)->get();
            $data->order_items= $order_items;
            return $data;
        });

        foreach($listOrdersAdmin as $items){
            $items['created_at'] = $items['created_at']->format('Y-m-d');
        }

        $data = [
            'total_size' => $listOrdersAdmin->count(),
            'orders' => $listOrdersAdmin
        ];

        return response()->json($data, 200); 
    }


}
