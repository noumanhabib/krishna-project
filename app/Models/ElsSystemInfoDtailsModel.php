<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ElsSystemInfoDtailsModel extends Model
{

	use SoftDeletes;
	protected $table = 'els_system_info_details';
	protected $primaryKey = 'id';
	protected $fillable = [
		'sku_no', 'brand_id', 'model_id', 'colour_id', 'barcode', 'grn_no', 'imei_1', 'imei_2', 'ram', 'rom', 'grade', 'mrp', 'remark', 'quantity', 'is_active', 'resived_date',  'vendor_id',  'status', 'created_by', 'updated_by', 'created_at', 'updated_at', 'deleted_at'
	];
}
