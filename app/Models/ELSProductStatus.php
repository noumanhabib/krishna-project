<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class ELSProductStatus extends Model
{
    use SoftDeletes;
    protected $table = 'els_product_status';
	protected $primaryKey = 'id';
	protected $fillable = [
		'sub_status_id','name', 'status'
	];
}
