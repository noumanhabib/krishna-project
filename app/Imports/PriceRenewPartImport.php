<?php

namespace App\Imports;

use carbon\Carbon;
use App\Models\RenewPartStatusUpdate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use App\Models\ReceivedPurchaseOrderParts;

class PriceRenewPartImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {

        if ($row[0] != "pin" && $row[0] != null) {
            $barcodes = DB::table('els_order_request_parts')
                ->where('old_pin', $row[0])->first();
            $spare_price = DB::table('spare_part_price_list')
                ->where('id', $barcodes->spare_part_price_id)->first();

            $po_id = DB::table('received_purchase_order_parts_list')
                ->orderBy('id', 'DESC')->first();

            // dd($po_id);

            if (session()->has('average')) {
                $sprice = session('average');
                $sprice = $spare_price->price + $sprice;
            } else {
                $sprice = $spare_price->price;
            }

            $auth_id = Auth::id();
            if (session()->get('count') == 0) {
                $poid = $po_id->purchase_order_id + 1;
                $data = array(
                    [
                        'purchase_order_id' => $poid,
                        'brand_id' => $barcodes->brand_id,
                        'model_id' => $barcodes->model_id,
                        'product_type_id' => 3,
                        'part_id' => $barcodes->part_id,
                        'sku_id' => $barcodes->spare_part_price_id,
                        'colour_id' => $barcodes->colour_id,
                        'price' => $sprice,
                        'gst' => 0,
                        'quantity' => $barcodes->quantity,
                        'status' => 1,
                        'created_by' => $auth_id,

                    ]
                );

                // dd($data);
                DB::table('received_purchase_order_parts_list')->insert($data);

                $variable = ReceivedPurchaseOrderParts::where('purchase_order_id', $poid)
                    ->where('brand_id', $barcodes->brand_id)
                    ->where('model_id', $barcodes->model_id)
                    ->where('product_type_id', 3)
                    ->where('part_id', $barcodes->part_id)
                    ->where('sku_id', $barcodes->spare_part_price_id)
                    ->where('colour_id', $barcodes->colour_id)
                    // ->where('price', $sprice)
                    ->where('gst', 0)
                    ->where('quantity', $barcodes->quantity)
                    ->where('status', 1)
                    ->where('created_by', $auth_id)
                    ->first();
                // dd($variable, 'if');
                $count = 1;
                session(['count' => $count]);
            } else {
                $data = array(
                    [
                        'purchase_order_id' => $po_id->purchase_order_id,
                        'brand_id' => $barcodes->brand_id,
                        'model_id' => $barcodes->model_id,
                        'product_type_id' => 3,
                        'part_id' => $barcodes->part_id,
                        'sku_id' => $barcodes->spare_part_price_id,
                        'colour_id' => $barcodes->colour_id,
                        'price' => $sprice,
                        'gst' => 0,
                        'quantity' => $barcodes->quantity,
                        'status' => 1,
                        'created_by' => $auth_id,

                    ]
                );
                DB::table('received_purchase_order_parts_list')->insert($data);
                $variable = ReceivedPurchaseOrderParts::where('purchase_order_id', $po_id->purchase_order_id)
                    ->where('brand_id', $barcodes->brand_id)
                    ->where('model_id', $barcodes->model_id)
                    ->where('product_type_id', 3)
                    ->where('part_id', $barcodes->part_id)
                    ->where('sku_id', $barcodes->spare_part_price_id)
                    ->where('colour_id', $barcodes->colour_id)
                    // ->where('price', $sprice)
                    ->where('gst', 0)
                    ->where('quantity', $barcodes->quantity)
                    ->where('status', 1)
                    ->where('created_by', $auth_id)
                    ->first();
                // dd($variable, 'else');
            }

            // dd($variable);
            $today = Carbon::today();
            $received_parts_barcode_list_new_record = [
                'received_part_id' => $variable->id,
                'price' => $sprice,
                'iqc_status' => 2,
                'status' => 3,
                'tester_id' => 0,
                'received_date' => $today,
                'dispatch_date' =>  NULL,
                'remark' => NULL,
                'iqc_status_one' => 2,
                'vendor_name' => 0,
                'uploaded_by' => $auth_id,
            ];
            if ($row[1]) {
                DB::table('received_parts_barcode_list')->where('barcode', $row[1])->update([
                    'iqc_status' => '',
                    'status' => '5',
                    'iqc_status_one' => ''
                ]);
            }
            // if ($row[0]) {
            //     DB::table('received_parts_barcode_list')->where('barcode', $row[0])->update([
            //         'iqc_status' => '',
            //         'status' => '5',
            //         'iqc_status_one' => ''
            //     ]);
            // }
            DB::table('received_parts_barcode_list')->updateOrInsert([
                'barcode' => $row[0],
            ], $received_parts_barcode_list_new_record);
            // dd($row[0]);
            DB::table('els_order_request_parts')->where('old_pin', $row[0])->update(['status' => 'Consumed']);
        }
        return;
    }
}
