<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderRequestPart extends Model
{
	use SoftDeletes;
	protected $table = 'els_order_request_parts';
	protected $primaryKey = 'id';
	protected $fillable = [
		'request_order_id', 'brand_id', 'part_type_id', 'model_id', 'part_id', 'series_id', 'colour_id', "status", 'quantity', 'spare_part_price_id', 'barcodes', 'new_pin', 'old_pin'
	];
}
