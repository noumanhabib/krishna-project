<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReceivedPurchaseOrderPartsBarcode extends Model
{
	use SoftDeletes;
	protected $table = 'received_parts_barcode_list';
	protected $primaryKey = 'id';
	protected $fillable = [
		'received_part_id', 'barcode', 'iqc_status', 'price', 'status', 'tester_id', 'received_date', 'remark'
	];
}