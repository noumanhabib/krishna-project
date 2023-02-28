<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class ELSProductSubStatus extends Model
{
    use SoftDeletes;
    protected $table = 'els_product_sub_status';
	protected $primaryKey = 'id';
	protected $fillable = [
		'name', 'status'
	];
}
