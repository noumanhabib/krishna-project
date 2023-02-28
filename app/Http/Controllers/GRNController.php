<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Session;
use App\Models\GRN;
use App\Models\MasterVendorModel;
use App\Models\User;

class GRNController extends Controller
{
    public function ShowGoodReceiveNotes(Request $request)
    {
        // dd($request->all());
        $data = [];
        $data['items'] = DB::table('part')->get();
        $data['colours'] = DB::table('colour')->get();
        $data['models'] = DB::table('model')->get();
        // dd($data['models']);
        $data['vendors'] = MasterVendorModel::all();
        $data['customers'] = User::get();
        // if (\Helper::checkMenuElegible()) {
        $data['title'] = "Good Re3ceive Notes";
        $data['mygrn'] = GRN::orderBy('id', 'Desc')->get();
        // dd($data);
        return view('good-receive-notes', $data);
        // }
        abort(403, "Don't have permission to access.");
    }

    public function StoreGoodReceiveNotes(Request $request)
    {


        $count = 0;
        $q = $request['item'];
        for ($i = 1; $i <= count($q); $i++) {
            if (($q[$i]['quantity'] > $q[$i]['old_quantity']) | ($q[$i]['sku'] == "")) {
                $count++;
            }
        }
        if ($count == 0) {

            GRN::create([
                'vendor_id' => $request->get('vendor_id'),
                'customer_id' => $request->get('customer_id'),
                'corriour_by' => $request->get('courror_by'),
                'po_no' => $request->get('po_no'),
                'stock' => $request->get('stock'),
                'items' => $request->get('item')
            ]);


            $j = 0;
            for ($i = 1; $i <= count($q); $i++) {

                $data = \App\Models\PurchaseOrderParts::whereNull('deleted_at')->where('purchase_order_id', $request->get('po_no'))->get();
                $val = $data[$j]->remaining_quantity - $q[$i]['quantity'];
                $data[$j]->remaining_quantity = $val;
                $data[$j]->display_quantity = $q[$i]['quantity'];
                $data[$j]->save();
                $j++;
            }
            Session::flash('message', 'GRN has been created succesfully.');
            Session::flash('alert-class', 'alert-success');
            return redirect()->back();
        } else {
            Session::flash('message', 'Something Went Wrong.');
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back();
        }
    }

    public function get_data(Request $request)
    {

        $array = [];

        $po = DB::table('purchase_order_list')
            ->where("id", $request->po_no)
            ->first();
        if (is_null($po)) {
            return response()->json(['array' => [], 'vname' => ""]);
        }
        // dd($po);
        // $vendor = $po->vendor_id;
        $vendor = DB::table('vendor')
            ->where('id', $po->vendor_id)
            ->first();
        if (is_null($vendor)) {
            return response()->json(['array' => [], 'vname' => ""]);
        }
        $vname = $vendor->id;
        $data = \App\Models\PurchaseOrderParts::whereNull('deleted_at')->where('purchase_order_id', $request->po_no)->get();

        // dd($data);
        for ($i = 0; $i < count($data); $i++) {

            // if (($data[$i]->display_quantity) != null) {
            $array['quantity'][$i] = $data[$i]->remaining_quantity;
            // } else if (($data[$i]->display_quantity) == null) {
            //     $array['quantity'][$i] = 0;
            // }
            $array['brand'][$i] = DB::table('brand')
                ->select('id')
                ->where('id', $data[$i]->brand_id)
                ->first();
            // dd($array['brand']);
            $array['model'][$i] = DB::table('model')
                ->select('id', 'mname')
                ->where('id', $data[$i]->model_id)
                ->first();
            $array['part'][$i] = DB::table('part')
                ->select('id', 'name')
                ->where('id', $data[$i]->part_id)
                ->first();
            // $product_type = DB::table('product_type')
            //     ->select('name')
            //     ->where('id', $data->product_type_id)
            //     ->first();
            $array['color'][$i] = DB::table('colour')
                ->select('id', 'name')
                ->where('id', $data[$i]->colour_id)
                ->first();
            // }
        }

        // dd($array);










        // dd($part, $model, $brand);
        // dd($data->colour_id);
        // if (!$data->isEmpty()) {
        //     $brand = \App\Models\MasterBrandModel::where('bstatus', '1')->where()->whereNull('deleted_at')->pluck('bname', 'id');
        //     $vendor = \App\Models\MasterVendorModel::where('status', '1')->whereNull('deleted_at')->pluck('vname', 'id');
        //     $model = \App\Models\MasterModel::where('mstatus', '1')->whereNull('deleted_at')->pluck('mname', 'id');
        //     $parts = \App\Models\MasterPartModel::where('status', '1')->whereNull('deleted_at')->pluck('name', 'id');
        //     // dd($vendor);
        //     // dd($parts);
        //     $product_type = \App\Models\ProductTypeModel::where('status', '1')->whereNull('deleted_at')->pluck('type', 'id');
        //     // return view('po/edit_purchase_order_form', compact('data', 'brand', 'vendor', 'model', 'product_type', 'parts', 'id', 'title'));
        // }
        // dd($vendor);
        return response()->json(['array' => $array, 'vname' => $vname]);
        // return response()->json(['quantity' => $quantity, 'color' => $color, 'vname' => $vname, 'brand' => $brand, 'model' => $model,  'part' => $part, 'color' => $color]);
        // return response()->json(['data' => []]);
    }

    // public function get_sku_print(Request $request)
    // {
    //     // dd('here');
    //     // dd($request->all());
    //     $data6 = DB::table('els_order_request_parts')
    //         ->where('els_order_request_parts.new_pin', '1')

    //         ->join('brand', 'els_order_request_parts.brand_id', '=', 'brand.id')
    //         ->join('model', 'els_order_request_parts.model_id', '=', 'model.id')
    //         // ->join('colour', 'els_order_request_parts.colour_id', '=', 'colour.id')
    //         ->join('part', 'els_order_request_parts.part_id', '=', 'part.id')
    //         ->join('spare_part_price_list', 'els_order_request_parts.spare_part_price_id', '=', 'spare_part_price_list.id')
    //         ->select('brand.bname', 'model.mname', 'spare_part_price_list.sku_no', 'part.name', 'els_order_request_parts.barcodes', 'els_order_request_parts.status', 'els_order_request_parts.new_pin', 'model.mstatus', 'brand.bstatus')
    //         ->get();

    //     // dd($data6);
    //     $count = count($data6);
    //     // dd($count);
    //     $sku = null;
    //     $bar = null;
    //     for ($i = 0; $i < $count; $i++) {
    //         if ($data6[$i]->barcodes == $request->print_pin) {
    //             $sku = $data6[$i]->sku_no;
    //             $bar = $data6[$i]->barcodes;
    //             break;
    //         }
    //     }

    //     return view('print', compact('bar', 'sku'));
    //     // return response()->json(['sku' => $sku, 'bar' => $bar]);
    // }

    public function printpin(Request $request, $barcode)
    {
        // dd('here');
        // dd($barcode);
        // dd($request->all());
        $data6 = DB::table('els_order_request_parts')
            ->where('els_order_request_parts.new_pin', '1')
            ->join('brand', 'els_order_request_parts.brand_id', '=', 'brand.id')
            ->join('model', 'els_order_request_parts.model_id', '=', 'model.id')
            ->join('part', 'els_order_request_parts.part_id', '=', 'part.id')
            ->join('spare_part_price_list', 'els_order_request_parts.spare_part_price_id', '=', 'spare_part_price_list.id')
            ->select('brand.bname', 'model.mname', 'spare_part_price_list.sku_no', 'part.name', 'els_order_request_parts.barcodes', 'els_order_request_parts.status', 'els_order_request_parts.new_pin', 'model.mstatus', 'brand.bstatus')
            ->get();

        // dd($data6);
        $count = count($data6);
        // dd($count);
        $sku = null;
        $bar = null;
        for ($i = 0; $i < $count; $i++) {
            if ($data6[$i]->barcodes == $barcode) {
                $sku = $data6[$i]->sku_no;
                $bar = $data6[$i]->barcodes;
                break;
            }
        }

        return view('print', compact('barcode', 'sku'));
    }

    public function generate_bar_code(Request $request)
    {
        $pin = $request->query("pin");
        $imei = $request->query("imei");

        return view('generate_bar_code', compact('pin', 'imei'));
    }
}
