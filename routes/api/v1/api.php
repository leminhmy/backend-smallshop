<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
/*Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/shoes','ShoesController@index');
Route::get('/shoes2','Api\V1\ShoesProductController@get_leather_shoes_proucts');
Route::post('/submitInfo','App\Http\Controllers\ShoesController@submitInfo');*/

//Route::post('/updateStatus','App\Http\Controllers\ShoesController@updateStatus');

Route::group(['namespace' => 'Api\V1'], function (){

    Route::group(['prefix' => 'products',], function (){
        Route::get('leather', 'ShoesProductController@get_leather_shoes_proucts');
        Route::get('shoes', 'ShoesProductController@get_shoes_product');
        Route::get('shoes-types', 'ShoesProductController@get_shoes_type');
        Route::post('update/{id}', 'ShoesProductController@update');
        Route::put('update/{id}/status/{status}', 'ShoesProductController@updateStatus');
        Route::delete('delete/{id}', 'ShoesProductController@delete');
        Route::post('uploadfile', 'ShoesProductController@uploadFile');
        Route::post('add', 'ShoesProductController@addToProduct');
        Route::delete('delete/{id}/img/{nameimg}', 'ShoesProductController@deleteImg');
    });

    Route::group(['prefix' => 'auth', 'namespace' => 'Auth'], function (){
        Route::post('register', 'CustomerAuthController@register');
        Route::post('login', 'CustomerAuthController@login');
    });

      Route::group(['prefix' => 'map'], function (){
        Route::get('provine', 'MapController@getMapProvine');
        Route::get('district/{idProvince}', 'MapController@getMapDistrict');
        Route::get('commune/{idDistrict}', 'MapController@getMapCommune');
        Route::post('set-provine', 'MapController@setMapProvine');
    });

     Route::group(['prefix' => 'messaging', 'namespace' => 'Auth','middleware' => 'auth:api'], function (){
        Route::post('send', 'MessagingController@sendMessages');
        Route::get('get', 'MessagingController@getMessages');
    });

    Route::group(['prefix' => 'customer', 'middleware' => 'auth:api'], function(){

         Route::get('info', 'CustomerController@info');
         Route::get('listusers', 'CustomerController@getAllUsers');
         Route::get('listadmin', 'CustomerController@getAllAdmin');
     });

    Route::group(['prefix' => 'order', 'middleware' => 'auth:api'], function (){
        Route::post('place', 'OrderController@placeorder');
        Route::get('list', 'OrderController@get_order_list');
        Route::get('listadmin', 'OrderController@get_order_list_admin');
    });

});


