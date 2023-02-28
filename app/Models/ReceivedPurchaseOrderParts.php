<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReceivedPurchaseOrderParts extends Model
{
	use SoftDeletes;
	protected $table = 'received_purchase_order_parts_list';
	protected $primaryKey = 'id';
	protected $fillable = [
		'purchase_order_id', 'brand_id', 'model_id', 'product_type_id', 'part_id', 'sku_id', 'colour_id', 'price', 'gst', 'quantity', 'status', 'created_by'
	];
}
