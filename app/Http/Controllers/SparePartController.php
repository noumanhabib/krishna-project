<?php

namespace App\Http\Controllers;

use App\Models\MasterBrandModel;
use App\Models\MasterPartModel;
use App\Models\MasterColourModel;
use App\Models\ProductTypeModel;
use Illuminate\Http\Request;
use Auth;
use App\Helper;
use DB;

class SparePartController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}

	public function SparepartList()
	{
		if (\Helper::checkMenuElegible()) {
			$title = "Sparepart List";
			$data = \App\Models\SparePartPriceList::join('spare_part_list', function ($join) {
				$join->on('spare_part_price_list.series_id', 'spare_part_list.id');
				$join->whereNull('spare_part_price_list.deleted_at');
			})->join('brand', 'spare_part_list.brand_id', '=', 'brand.id')
				->join('model', 'spare_part_list.model_id', '=', 'model.id')
				->join('product_type', 'spare_part_list.type_id', '=', 'product_type.id')
				->join('part', 'spare_part_list.part_id', '=', 'part.id')
				->join('colour', 'spare_part_price_list.colour_id', '=', 'colour.id')
				->orderBy('spare_part_list.id', 'desc')
				->select('spare_part_list.*', 'brand.bname', 'model.mname', 'product_type.type', 'part.name', 'spare_part_price_list.sku_no', 'colour.name as colour_name', 'spare_part_price_list.id as sku_id')->get();
			return view('sparepart.sparepart_list', compact('title', 'data'));
		}
		abort(403, "Don't have permission to access.");
	}
	public function SparepartListsku()
	{
		if (\Helper::checkMenuElegible()) {
			$title = "Sparepart List";
			// 			$data=\App\Models\SparePartPriceList::join('spare_part_list',function($join){
			// 				$join->on('spare_part_price_list.series_id','spare_part_list.id');
			// 				$join->whereNull('spare_part_price_list.deleted_at');
			// 			})->join('brand','spare_part_list.brand_id','=','brand.id')
			// 			->join('model','spare_part_list.model_id','=','model.id')
			// 			->join('product_type','spare_part_list.type_id','=','product_type.id')
			// 			->join('part','spare_part_list.part_id','=','part.id')
			// 			->join('colour','spare_part_price_list.colour_id','=','colour.id')
			// 			->orderBy('spare_part_list.id','desc')
			// 			->select('spare_part_list.*','brand.bname','model.mname','product_type.type','part.name','spare_part_price_list.sku_no','colour.name as colour_name','spare_part_price_list.id as sku_id')->get();

			$data1 = \App\Models\SparePartPriceList::join('spare_part_list', function ($join) {
				$join->on('spare_part_price_list.series_id', 'spare_part_list.id');
				$join->whereNull('spare_part_price_list.deleted_at');
			})->join('brand', 'spare_part_list.brand_id', '=', 'brand.id')
				->join('model', 'spare_part_list.model_id', '=', 'model.id')
				->join('product_type', 'spare_part_list.type_id', '=', 'product_type.id')
				->join('part', 'spare_part_list.part_id', '=', 'part.id')
				->join('colour', 'spare_part_price_list.colour_id', '=', 'colour.id')
				->orderBy('spare_part_list.id', 'desc')
				->select('spare_part_list.*', 'brand.bname', 'model.mname', 'product_type.type', 'part.name', 'spare_part_price_list.sku_no', 'colour.name as colour_name', 'spare_part_price_list.id as sku_id')->get();





			return view('sparepart.sparepart_list_sku', compact('title', 'data1'));
		}
		abort(403, "Don't have permission to access.");
	}

	public function SparepartListAuto()
	{
		if (\Helper::checkMenuElegible()) {
			$title = "Sparepart Autogenerate List";
			$data = \App\Models\SparePartListModel::join('model', function ($join) {
				// $join->on('spare_part_price_list.series_id','spare_part_list.id');
				$join->on('spare_part_list.model_id', '=', 'model.id');
				// $join->whereNull('spare_part_price_list.deleted_at');
			})->join('brand', 'spare_part_list.brand_id', '=', 'brand.id')

				->join('product_type', 'spare_part_list.type_id', '=', 'product_type.id')
				->join('part', 'spare_part_list.part_id', '=', 'part.id')
				->orderBy('spare_part_list.id', 'desc')
				->groupBy('spare_part_list.model_id')
				->select('spare_part_list.*', 'brand.bname', 'model.mname', 'product_type.type')->get();
			return view('sparepart.sparepart_list_auto', compact('title', 'data'));
		}
		abort(403, "Don't have permission to access.");
	}

	public function SparepartForm(Request $request, $id = null)
	{
		$data = [];
		$multiple_list = [];
		if ($id) {
			$title = "Edit Sparepart";
			$data = \App\Models\SparePartListModel::where('id', $id)->first();
			if ($data) {

				$multiple_list = \App\Models\SparePartPriceList::where('series_id', $id)->get();

				$color_list = MasterColourModel::where('status', '1')->whereNull('deleted_at')->pluck('name', 'id');

				$model = \App\Models\MasterModel::where('brand_id', $data->brand_id)->whereNull('deleted_at')->pluck('mname', 'id');
			}
		} else {
			$title = "Add Sparepart";
			$model = [];
		}
		$part_name_list = MasterPartModel::where('status', '1')->whereNull('deleted_at')->pluck('name', 'id');
		$brand_list = MasterBrandModel::where('bstatus', '1')->whereNull('deleted_at')->pluck('bname', 'id');
		$color_list = MasterColourModel::where('status', '1')->whereNull('deleted_at')->pluck('name', 'id');
		$type_list = ProductTypeModel::where('status', '1')->whereNull('deleted_at')->pluck('type', 'id');

		return view('sparepart.sparepart_form', compact('title', 'data', 'brand_list', 'part_name_list', 'color_list', 'type_list', 'model', 'multiple_list'));
	}

	public function SparepartFormAuto(Request $request, $id = null)
	{
		// echo $id;die();
		$data = [];
		$multiple_list = [];
		if ($id) {
			$title = "Edit Sparepart";
			$data = \App\Models\SparePartListModel::where('model_id', $id)->first();
			if ($data) {

				$multiple_list = \App\Models\SparePartPriceList::where('model_id', $id)->orderBy('id', 'ASC')->get();

				$color_list = MasterColourModel::where('status', '1')->whereNull('deleted_at')->pluck('name', 'id');

				$model = \App\Models\MasterModel::where('brand_id', $data->brand_id)->whereNull('deleted_at')->pluck('mname', 'id');
			}
		} else {
			$title = "Add Sparepart";
			$model = [];
		}
		$part_name_list = MasterPartModel::where('status', '1')->whereNull('deleted_at')->pluck('name', 'id');
		$brand_list = MasterBrandModel::where('bstatus', '1')->whereNull('deleted_at')->pluck('bname', 'id');
		$color_list = MasterColourModel::where('status', '1')->whereNull('deleted_at')->pluck('name', 'id');
		$type_list = ProductTypeModel::where('status', '1')->whereNull('deleted_at')->pluck('type', 'id');

		return view('sparepart.sparepart_form_auto', compact('title', 'data', 'brand_list', 'part_name_list', 'color_list', 'type_list', 'model', 'multiple_list'));
	}


	public function checkSkuNumber(Request $request)
	{
		$data = \App\Models\SparePartListModel::join('spare_part_price_list', function ($join) {
			$join->on('spare_part_list.id', 'spare_part_price_list.series_id');
			$join->whereNull('spare_part_price_list.deleted_at');
		})->whereNull('spare_part_list.deleted_at')->where('spare_part_list.part_id', '!=', $request->part_id)->where('spare_part_price_list.colour_id', '!=', $request->colour_id)->where('spare_part_price_list.sku_no', $request->sku_no)->first();
		if ($data) {
			return response()->json(['status' => true, 'data' => [], 'code' => 200, 'message' => 'SKU Number Already Exist.']);
		}
		return response()->json(['status' => false, 'data' => [], 'code' => 401, 'message' => 'No data found.']);
	}

	public function SaveSparepart(Request $request)
	{
		$save = \App\Models\SparePartListModel::updateOrCreate([
			'brand_id'  => $request->brand_id,
			'model_id'  => $request->model_id,
			'part_id'   => $request->part_id,
			'type_id'   => $request->product_type_id
		], [
			'updated_by' => Auth::user()->id,
		]);

		$series_id = $save->id;
		$colour = $request->part_color_id;
		foreach ($colour as $key => $value) {
			$save_series = \App\Models\SparePartPriceList::updateOrCreate([
				'series_id' => $series_id,
				'colour_id' => $request['part_color_id'][$key],
			], [
				'sku_no'   => $request['sku_no'][$key],
			]);
			$save_series_id = $save_series->id;
			DB::table('spare_part_price_list')
				->where('id', $save_series_id)
				->update(['model_id'   => $request->model_id]);
		}

		return redirect()->route('sparepart_list');
	}

	public function SaveSparepartAuto(Request $request)
	{

		$part_id = $request->part_id;
		//   print_r($part_id);
		//   die("hi");
		foreach ($part_id as $key => $value) {
			$save = \App\Models\SparePartListModel::updateOrCreate([
				'brand_id'  => $request->brand_id,
				'model_id'  => $request->model_id,
				'part_id'   => $request['part_id'][$key],
				'type_id'   => $request->product_type_id
			], [
				'updated_by' => Auth::user()->id,
			]);

			$series_id = $save->id;

			$model = $request->model_id;

			$sid = DB::table('spare_part_price_list')->where('series_id', $series_id)->orderBy('id', 'DESC')->first();

			if (!empty($sid)) {
				$sidsku = DB::table('spare_part_price_list')->where('id', $sid->id)->first();


				if (!empty($sidsku->colour_id == NULL)) {

					DB::table('spare_part_price_list')
						->where('id', $sid->id)
						->update(['colour_id' => $request['part_color_id'][$key], 'sku_no'   => $request['sku_no'][$key], 'model_id'   => $model]);
				} else {
					$save_series = \App\Models\SparePartPriceList::updateOrCreate([
						'series_id' => $series_id,
						'colour_id' => $request['part_color_id'][$key],
					], [
						'sku_no'   => $request['sku_no'][$key],
						'model_id'  => $model,
					]);


					// 			$save_series = DB::table('spare_part_price_list')
					//                 ->where('id', $sid->id)
					//                 ->update(['colour_id' => $request['part_color_id'][$key],'sku_no'   =>$request['sku_no'][$key],'model_id'   =>$model]);

					//     $save_series_id=$save_series->id;
					// DB::table('spare_part_price_list')
					//             ->where('id', $save_series_id)
					//             ->update(['model_id'   =>$model]);

				}
			} else {

				$save_series = \App\Models\SparePartPriceList::updateOrCreate([
					'series_id' => $series_id,
					'colour_id' => $request['part_color_id'][$key],
				], [
					'sku_no'   => $request['sku_no'][$key],
				], ['model_id' => $model]);



				$save_series_id = $save_series->id;
				DB::table('spare_part_price_list')
					->where('id', $save_series_id)
					->update(['model_id'   => $model]);
			}
		}


		return redirect()->back();
	}


	public function DeleteSparepart(Request $request)
	{
		$id = $request->id;
		\App\Models\SparePartPriceList::where('id', $id)->delete();
		$arr = array(
			"code" => 200,
			"message" => "Delete Successfully",
			"data" => ''
		);
		return \Response::json($arr);
	}
	public function  GetHtmlPriceAndColor(Request $request)
	{
		$id = $request->id;
		$color_list = \App\Models\MasterColourModel::where('status', '1')->whereNull('deleted_at')->pluck('name', 'id');
		$html = view('sparepart/multiple_colour_and_price_list', compact('color_list', 'id'));
		$idd = ($request->id) + 1;
		return response()->json(['html' => \Helper::compressHtml($html), 'idd' => $idd]);
	}
	public function  GetHtmlPriceAndColorAuto(Request $request)
	{
		$id = $request->id;
		$part_name_list = MasterPartModel::where('status', '1')->whereNull('deleted_at')->pluck('name', 'id');
		$color_list = \App\Models\MasterColourModel::where('status', '1')->whereNull('deleted_at')->pluck('name', 'id');
		$html = view('sparepart/multiple_colour_and_price_list_auto', compact('color_list', 'part_name_list', 'id'));
		$idd = ($request->id) + 1;
		return response()->json(['html' => \Helper::compressHtml($html), 'idd' => $idd]);
	}

	public function  GetHtmlSkuAuto(Request $request)
	{
		$part_color_id = $request->part_color_id;
		$part_id = $request->part_id;
		$brand_id = $request->brand_id;
		$model_id = $request->model_id;
		$product_type_id = $request->product_type_id;
		$id = $request->id;



		$part = DB::table('part')->where('id', $part_id)->first();
		$modal = DB::table('model')->where('id', $model_id)->first();
		$brand = DB::table('brand')->where('id', $brand_id)->first();
		$product_type = DB::table('product_type')->where('id', $product_type_id)->first();
		$colour = DB::table('colour')->where('id', $part_color_id)->first();


		$cn = $colour->name;
		$pt = $product_type->type;
		$mn = $modal->mname;
		$bn = $brand->bname;
		$pn = $part->name;



		// $cnn=$cn[0].$cn[strlen($cn) - 1];
		$clarr = explode(' ', $cn);
		if (count($clarr) == 1) {
			$cnc = $clarr[0];
			$cnn = $cnc[0] . $cnc[strlen($cnc) - 1];
		} else if (count($clarr) == 2) {
			$cnc = $clarr[0];
			$cnc1 = $clarr[1];
			$cnn = $cnc[0] . $cnc1[0];
		} else {
			$cnc = $clarr[0];
			$cnc1 = $clarr[1];
			$cnc2 = $clarr[2];
			$cnn = $cnc[0] . $cnc1[0] . $cnc2[0];
		}

		$pnar = explode(' ', $pn);
		if (count($pnar) == 1) {
			$pnc = $pnar[0];
			$pnn = $pnc[0] . $pnc[1];
		} else if (count($pnar) == 2) {
			$pnc = $pnar[0];
			$pnc1 = $pnar[1];
			$pnn = $pnc[0] . $pnc1[0];
		} else {
			$pnc = $pnar[0];
			$pnc1 = $pnar[1];
			$pnc2 = $pnar[2];
			$pnn = $pnc[0] . $pnc1[0] . $pnc2[0];
		}



		// $ptt=$pt[0].$pt[strlen($pt) - 1];

		$plarr = explode(' ', $pt);
		$ptt = $plarr[0][0] . $plarr[1][0];


		$mnns = explode(' ', $mn);
		$mnn = '';
		for ($ii = 1; $ii < count($mnns); $ii++) {
			$mnn .= $mnns[$ii];
		}


		$bnn = $bn[0] . $bn[1];

		//   $pnn=$pn[0].$pn[strlen($pn) - 1];


		$string = $bnn . "/" . $mnn . "/" . $part_id . "/" . $pnn . "/" . $cnn;
		$srn = strtoupper($string);
		return response()->json(['html' => $srn]);
	}




	public function GetColorPriceDetailBySeriesid(Request $request)
	{
		$id = $request->id;

		$data = \App\Models\SparePartPriceList::select('spare_part_price_list.*', 'colour.name')
			->join('colour', 'spare_part_price_list.colour_id', '=', 'colour.id')
			->where('spare_part_price_list.series_id', $id)->get()->toarray();

		$arr = array(
			"code" => 200,
			"message" => "Delete Successfully",
			"data" => $data,
		);
		return \Response::json($arr);
	}

	public function dashboardSpareParts(Request $request)
	{
		header("Content-Type: application/xls");
		header("Content-Disposition: attachment; filename=SparePartsReport.xls");
		header("Pragma: no-cache");
		header("Expires: 0");
		$status = \App\Models\ELSProductStatus::whereNull('deleted_at')->where('status', '1')->get();

		$columnHeader = "Sr NO" . "\t" . "Part Name" . "\t" . "Part Model" . "\t" . "Part Brand" . "\t" . "Type" . "\t" . "Colour" . "\t" . "SKU Number" . "\t";

		$data = \App\Models\SparePartPriceList::join('spare_part_list', function ($join) {
			$join->on('spare_part_price_list.series_id', 'spare_part_list.id');
			$join->whereNull('spare_part_price_list.deleted_at');
		})->join('brand', 'spare_part_list.brand_id', '=', 'brand.id')
			->join('model', 'spare_part_list.model_id', '=', 'model.id')
			->join('product_type', 'spare_part_list.type_id', '=', 'product_type.id')
			->join('part', 'spare_part_list.part_id', '=', 'part.id')
			->join('colour', 'spare_part_price_list.colour_id', '=', 'colour.id')
			->orderBy('spare_part_list.id', 'desc')
			->select('spare_part_list.*', 'brand.bname', 'model.mname', 'product_type.type', 'part.name', 'spare_part_price_list.sku_no', 'colour.name as colour_name', 'spare_part_price_list.id as sku_id')->get();

		$i = 1;
		$setData = '';
		if (!$data->isEmpty()) {
			foreach ($data as $value) {
				$total = $variance = 0;
				$rowData = '';
				$rowData .= '"' . $i . '"' . "\t";
				$rowData .= '"' . $value->name . '"' . "\t";
				$rowData .= '"' . $value->mname . '"' . "\t";
				$rowData .= '"' . $value->bname . '"' . "\t";
				$rowData .= '"' . $value->type . '"' . "\t";
				$rowData .= '"' . $value->colour_name . '"' . "\t";
				$rowData .= '"' . $value->sku_no . '"' . "\t";
				$setData .= trim($rowData) . "\n";
				$i++;
			}
		}
		echo ucwords($columnHeader) . "\n" . $setData . "\n";
	}


	public function dashboardSparePartssku(Request $request)
	{
		header("Content-Type: application/xls");
		header("Content-Disposition: attachment; filename=SparePartsReport.xls");
		header("Pragma: no-cache");
		header("Expires: 0");
		$status = \App\Models\ELSProductStatus::whereNull('deleted_at')->where('status', '1')->get();

		$columnHeader = "Sr NO" . "\t" . "Part Name" . "\t" . "Part Model" . "\t" . "Part Brand" . "\t" . "Type" . "\t" . "Colour" . "\t" . "SKU Number" . "\t" . "Quantity" . "\t" . "Failed Quantity" . "\t";

		$data = \App\Models\SparePartPriceList::join('spare_part_list', function ($join) {
			$join->on('spare_part_price_list.series_id', 'spare_part_list.id');
			$join->whereNull('spare_part_price_list.deleted_at');
		})->join('brand', 'spare_part_list.brand_id', '=', 'brand.id')
			->join('model', 'spare_part_list.model_id', '=', 'model.id')
			->join('product_type', 'spare_part_list.type_id', '=', 'product_type.id')
			->join('part', 'spare_part_list.part_id', '=', 'part.id')
			->join('colour', 'spare_part_price_list.colour_id', '=', 'colour.id')
			->orderBy('spare_part_list.id', 'desc')
			->groupBy('spare_part_price_list.sku_no')
			->select('spare_part_list.*', 'brand.bname', 'model.mname', 'product_type.type', 'part.name', 'spare_part_price_list.sku_no', 'colour.name as colour_name', 'spare_part_price_list.id as sku_id')->get();

		$i = 1;
		$setData = '';
		if (!$data->isEmpty()) {
			foreach ($data as $value) {

				$usersd = DB::table('received_purchase_order_parts_list')
					->select('quantity', 'id')
					->where('sku_id', $value->sku_id)
					->get();


				$quantity = array();
				$idd = array();
				for ($iR = 0; $iR < count($usersd); $iR++) {

					$idd[] = $usersd[$iR]->id;
					$quantity[] = $usersd[$iR]->quantity;
				}

				if (!empty($usersd[0])) {
					$usersdd = DB::table('received_parts_barcode_list')
						->selectRaw('count(id) as live')
						->whereIn('received_part_id', $idd)
						->where('iqc_status_one', '1')
						->where('status', '2')
						->get();

					if (!empty($usersdd)) {
						$quanti = $usersdd[0]->live;
					}
				} else {
					$quanti = 0;
				}
				if (!empty($usersd[0])) {
					$sum_quanti = array_sum($quantity);
				} else {
					$sum_quanti = 0;
				}


				if (!empty($usersd[0])) {
					$usersddf = DB::table('received_parts_barcode_list')
						->selectRaw('count(id) as live')
						->whereIn('received_part_id', $idd)
						->where('iqc_status_one', '0')
						->where('status', '2')
						// ->groupBy('received_part_id')
						->get();



					if (!empty($usersddf)) {
						$quantif = $usersddf[0]->live;
					}
				} else {
					$quantif = 0;
				}




				$total = $variance = 0;
				$rowData = '';
				$rowData .= '"' . $i . '"' . "\t";
				$rowData .= '"' . $value->name . '"' . "\t";
				$rowData .= '"' . $value->mname . '"' . "\t";
				$rowData .= '"' . $value->bname . '"' . "\t";
				$rowData .= '"' . $value->type . '"' . "\t";
				$rowData .= '"' . $value->colour_name . '"' . "\t";
				$rowData .= '"' . $value->sku_no . '"' . "\t";
				$rowData .= '"' . $quanti . '"' . "\t";
				$rowData .= '"' . $quantif . '"' . "\t";
				$setData .= trim($rowData) . "\n";
				$i++;
			}
		}
		echo ucwords($columnHeader) . "\n" . $setData . "\n";
	}

	public function importSpartParts(Request $request)
	{
		set_time_limit(0);
		$file = $request->file('file_csv');
		if (in_array(strtoupper($file->getClientOriginalExtension()), ['CSV'])) {
			$fileD = fopen($file, "r");
			$column = fgetcsv($fileD);
			if ($column) {
				foreach ($column as $k => $d) {
					if ($d == 'Brand') {
						$barnd_key = $k;
					}
					if ($d == 'Model') {
						$model_key = $k;
					}
					if ($d == 'Product Type') {
						$product_type_key = $k;
					}
					if ($d == 'Part Name') {
						$part_name_key = $k;
					}
					if ($d == 'Part Colour') {
						$colour_key = $k;
					}
					if ($d == 'SKU Number') {
						$sku_key = $k;
					}
				}
			}
			$i = 2;
			$error = false;
			$massage = '';
			$dataArr = [];
			while (!feof($fileD)) {
				$msg = 'Row No ' . $i . ' ';
				$rowData = fgetcsv($fileD);
				if (isset($rowData[$barnd_key])) {
					$brand = \App\Models\MasterBrandModel::where('bstatus', '1')->whereNull('deleted_at')->where('bname', $rowData[$barnd_key])->first();
					if ($brand) {
						$row['brand_id'] = $brand->id;
					} else {
						$msg .= 'Brand,';
						$error = true;
					}
				}
				if (isset($rowData[$model_key])) {
					$model = \App\Models\MasterModel::where('mstatus', '1')->whereNull('deleted_at')->where('mname', $rowData[$model_key])->first();
					if ($model) {
						$row['model_id'] = $model->id;
					} else {
						$msg .= 'Model,';
						$error = true;
					}
				}
				if (isset($rowData[$product_type_key])) {
					$type = \App\Models\ProductTypeModel::where('status', '1')->whereNull('deleted_at')->where('type', $rowData[$product_type_key])->first();
					if ($type) {
						$row['type_id'] = $type->id;
					} else {
						$msg .= 'Product Type';
						$error = true;
					}
				}

				if (isset($rowData[$part_name_key])) {
					$part = \App\Models\MasterPartModel::where('status', '1')->whereNull('deleted_at')->where('name', $rowData[$part_name_key])->first();
					if ($part) {
						$row['part_id'] = $part->id;
					} else {
						$msg .= 'Part Name';
						$error = true;
					}
				}

				if (isset($rowData[$colour_key])) {
					$colour = \App\Models\MasterColourModel::where('status', '1')->whereNull('deleted_at')->where('name', $rowData[$colour_key])->first();
					if ($colour) {
						$row['colour_id'] = $colour->id;
					} else {
						$msg .= 'Colour';
						$error = true;
					}
				}
				if (isset($rowData[$sku_key])) {
					$row['sku_no'] = $rowData[$sku_key];
				}

				$dataArr[] = $row;
				if ($error) $massage .= $msg . ' Not Found. Please Correct The Details.';
				$i++;
			}
			if ($error) {
				return response()->json(['status' => false, 'data' => [], 'code' => 401, 'message' => $massage]);
			}

			if ($dataArr) {
				foreach ($dataArr as $val) {
					$d = (object) $val;
					$save = \App\Models\SparePartListModel::updateOrCreate([
						'brand_id'  => $d->brand_id,
						'model_id'  => $d->model_id,
						'part_id'   => $d->part_id,
						'type_id'   => $d->type_id,
					], [
						'created_by' => Auth::user()->id,
					]);

					$series_id = $save->id;
					$save_series = \App\Models\SparePartPriceList::updateOrCreate([
						'series_id' => $series_id,
						'colour_id' => $d->colour_id,
					], [
						'sku_no'   => $d->sku_no,
					]);
				}
			}
			// return response()->json(['status'=>true, 'data' => [], 'code'=>200, 'message'=> 'Product successfully imported.']);
		}
		// return response()->json(['status'=>false, 'data' => [], 'code'=>401, 'message'=> 'Please choose a correct formate.']);
		return redirect()->back();
	}
}
