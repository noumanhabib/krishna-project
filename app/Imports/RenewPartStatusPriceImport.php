<?php

namespace App\Imports;

use DB;
use App\Models\RenewPartStatusUpdate;
use Maatwebsite\Excel\Concerns\ToModel;

class RenewPartStatusPriceImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // dd($row);
        $pin = $row[0];
        $test = DB::table('els_order_request_parts')->where('barcodes',$pin)->first();
        
        $gettotalcount = DB::table('els_order_request_parts')->where('brand_id',$test->brand_id)->where('model_id',$test->model_id)->where('part_type_id',$test->part_type_id)->where('part_id',$test->part_id)->select('spare_part_price_id')->count();
        $getprice = DB::table('els_order_request_parts')->where('brand_id',$test->brand_id)->where('model_id',$test->model_id)->where('part_type_id',$test->part_type_id)->where('part_id',$test->part_id)->select(DB::raw('SUM(spare_part_price_id) as totalprice'))->get();
        // dd($getprice[0]->totalprice);
        // dd($gettotalcount);
        $totalprice = $getprice[0]->totalprice;
        $newprice = $totalprice/$gettotalcount;
        // dd($newprice);
        $test = DB::table('els_order_request_parts')
        ->where('barcodes', $pin)
        ->update(['spare_part_price_id' => $newprice,
                    'new_pin' => null]);
        // ->select(DB::raw('SUM(spare_part_price_id) '))
        // $test = DB::table('els_order_request_parts')
        // ->where('barcodes', $row[0])
        // ->update(['status' => $row[1]]);
        // // dd($test);
        return;
    }
}
