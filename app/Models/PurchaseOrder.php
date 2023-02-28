<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseOrder extends Model
{
    use SoftDeletes;
    protected $table = 'purchase_order_list';
	protected $primaryKey = 'id';
	protected $fillable = [
		'grn_no','vendor_id','created_by','status'
	];
}
