<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseOrderParts extends Model
{
    use SoftDeletes;
    protected $table = 'purchase_order_parts_list';
	protected $primaryKey = 'id';
	protected $fillable = [
		'purchase_order_id','request_order_id','brand_id','model_id','product_type_id','part_id','hsn_code','series_id','colour_id','price','gst','quantity','status'
	];
}
