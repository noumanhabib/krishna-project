<?php

namespace App\Http\Controllers;

use DB;
use Carbon\Carbon;
use App\Exports\GRNExport;
use Illuminate\Http\Request;
use App\Exports\RenewhubStatus;
use App\Models\GoodReceiveNote;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\PriceRenewPartImport;
use App\Imports\RenewPartStatusImport;
use App\Imports\RenewPartStatusPriceImport;
use App\Imports\TotalRenewPartStatusPriceImport;

class ExportController extends Controller
{
    public function export(Request $request)
    {
        $request->validate([
            'start' => 'required',
            'last' => 'required',
        ]);
        $sdate = $request->start . " 00:00:01";
        $edate = $request->last . " 23:59:59";

        $GRNs = GoodReceiveNote::whereBetween('created_at', [$sdate, $edate])->get();
        foreach ($GRNs as $GRN) {
            $items = json_decode($GRN->items);
            foreach ($items as $item) {
                $test[] =
                    [
                        'GRN_NO' => 'GRN' . $GRN->id,
                        'Date' => date('Y-m-d', strtotime($GRN->created_at)),
                        'Vendor' => \App\Models\MasterVendorModel::find($GRN->vendor_id)->vname,
                        'Customer' => \App\Models\User::find($GRN->customer_id)->name,
                        'Corriour by' => $GRN->corriour_by,
                        'PO Nmber' => $GRN->po_no,
                        'Stock' => $GRN->stock,
                        'Part' => \DB::table('part')->where('id', $item->part_id)->first()->name,
                        'Model' => \DB::table('model')->where('id', $item->model_id)->first()->mname,
                        'Color' => \DB::table('colour')->where('id', $item->color_id)->first()->name,
                        'Quantity' => $item->quantity,
                        'SKU' => $item->sku,
                    ];
            }
        }

        $users = [

            $test

        ];

        return Excel::download(new GRNExport($users), 'GRN.csv');
    }



    public function renewstatusupdate(Request $request)
    {
        Excel::import(new RenewPartStatusImport, $request->file);
        return redirect()->back();
    }

    public function export_renewhub_status(Request $request)
    {
        $created_at = $request->created_at;
        $barcode = $request->barcode;
        $bname = $request->bname;
        $mname = $request->mname;
        $name = $request->name;
        $sku_no = $request->sku_no;
        $status = $request->status;
        if (count($request->new_pin)) {
            foreach ($request->new_pin as $key => $val) {
                $createat = explode(" ", $created_at[$key]);
                $test[] =
                    [
                        'Date' => $createat[0],
                        'Barcode' => $barcode[$key],
                        'Brand_Name' => $bname[$key],
                        'Model_Name' => $mname[$key],
                        'Part_Name' => $name[$key],
                        'SKU_NO' => $sku_no[$key],
                        'Status' => $status[$key],
                    ];
            }
        }
        $users = [

            $test

        ];

        return Excel::download(new RenewhubStatus($users), 'renew.csv');
    }

    public function renewstatusupdateprice(Request $request)
    {
        if ($request->session()->has('price')) {
            session()->forget('price');
        }
        session(['quantity' => 0]);
        session(['count' => 0]);

        Excel::import(new TotalRenewPartStatusPriceImport, $request->file);
        $pricetotal = session('price');
        $totalquantity = session('quantity');
        // dd($pricetotal, $totalquantity);
        $average = $pricetotal / $totalquantity;
        // dd($average);
        session(['average' => $average]);

        Excel::import(new PriceRenewPartImport, $request->file);
        return redirect()->back();
    }
}
