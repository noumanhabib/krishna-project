<?php

namespace App\Imports;

use App\Models\RenewPartStatusUpdate;
use App\Models\Status;
use Maatwebsite\Excel\Concerns\ToModel;
use DB;

class RenewPartStatusImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // dd($row[0]);
        // $test = DB::table('els_order_request_parts')->update('status',$row[1])->where('barcode',$row[0]);
        if ($row[0] != "pin") {
            $status = Status::select('status_name')
                ->where('status', 1)
                ->where('status_name', $row[1])
                ->first();
            // dd($status);
            if ($status != "") {
                // dd($row[0]);
                $test = DB::table('els_order_request_parts')
                    ->where('old_pin', $row[0])
                    ->update(['status' => $row[1]]);
            } else {
                echo "Invalid Status. Please first add the add from master then import it";
                die;
            }
        }
        // dd($test);
        return;
    }
}
