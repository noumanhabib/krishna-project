<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class ELSProductWarranty extends Model
{
    use SoftDeletes;
    protected $table = 'els_product_warranty';
	protected $primaryKey = 'id';
	protected $fillable = [
		'els_system_id', 'duration', 'type', 'start_date','end_date','remark'
	];
}
