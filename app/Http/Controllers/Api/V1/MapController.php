<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Map;
use Illuminate\Support\Facades\DB;

class MapController extends Controller
{
    //
    public function getMapProvine(Request $request){


        $map = DB::table('map_provine_vn')->get();

         return response()->json($map);
    }

    public function getMapDistrict(Request $request,$idProvince){

        $map = DB::table('map_district_vn')->where('idProvince', $idProvince)->get();

         return response()->json($map);
    }

    public function getMapCommune(Request $request,$idDistrict){

        $map = DB::table('map_commune_vn')->where('idDistrict', $idDistrict)->get();

         return response()->json($map);
    }

     public function setMapProvine(Request $request){


        $table_map = $request['table_map'];

    
        foreach($request['list_map'] as $m)
        {
            $items = [
                        'idDistrict' => $m['idDistrict'],
                        'idCommune' => $m['idCommune'],
                        'name' => $m['name'], 
                    ];
                    $map_items[] = $items;
        }




        DB::table($table_map)->insert($map_items);



         return response()->json($map_items);
    }
}
