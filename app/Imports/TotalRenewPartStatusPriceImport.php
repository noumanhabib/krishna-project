<?php

namespace App\Imports;

use App\Models\RenewPartStatusUpdate;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Facades\DB;

class TotalRenewPartStatusPriceImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        if ($row[0] != "pin") {

            $barcode_id = DB::table('received_parts_barcode_list')
                ->select('received_part_id', 'barcode', 'price')->where('barcode', $row[1])->where('iqc_status_one', '1')->where('status', '2')->first();
            $consumed_id = DB::table('received_parts_barcode_list')
                ->select('received_part_id', 'barcode', 'price')->where('barcode', $row[0])->where('iqc_status_one', '1')->where('status', '2')->first();

            if ($barcode_id != null || $barcode_id == "") {
                if ($consumed_id != null || $consumed_id == "") {

                    session(['barcode_id' => $barcode_id]);

                    if (session()->has('price')) {
                        if ($barcode_id == "" && $consumed_id != "") {
                            session(['price' => $consumed_id->price + session('price')]);
                        } else if ($consumed_id == "" && $barcode_id != "") {
                            session(['price' => $barcode_id->price + session('price')]);
                        } else if ($consumed_id == "" && $barcode_id == "") {
                            session(['price' => session('price')]);
                        } else {
                            session(['price' => $consumed_id->price + $barcode_id->price + session('price')]);
                        }
                    } else {
                        if ($barcode_id == "" && $consumed_id != "") {
                            session(['price' => $consumed_id->price]);
                        } else if ($consumed_id == "" && $barcode_id != "") {
                            session(['price' => $barcode_id->price]);
                        } else if ($consumed_id == "" && $barcode_id == "") {
                            session(['price' => 0]);
                        } else {
                            session(['price' => $consumed_id->price + $barcode_id->price]);
                        }
                        // session(['price' => $consumed_id->price + $barcode_id->price]);
                    }
                } else {
                    die($row[0] . ' Pin status is not pass or not in used');
                }
            } else {
                die($row[1] . ' Pin status is not pass or not in used');
            }


            if ($row[0] != null) {
                if (session()->has('quantity')) {
                    $quantity = session('quantity') + 1;
                } else {
                    $quantity = 1;
                }
                session(['quantity' => $quantity]);
            }
        }
    }
}
