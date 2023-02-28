<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderRequest extends Model
{
    use SoftDeletes;
    protected $table = 'els_order_request';
	protected $primaryKey = 'id';
	protected $fillable = [
		'grn_no','els_system_id','remarks','status'
	];
}
