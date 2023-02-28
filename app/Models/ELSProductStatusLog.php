<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class ELSProductStatusLog extends Model
{
    use SoftDeletes;
    protected $table = 'els_system_status_log';
	protected $primaryKey = 'id';
	protected $fillable = [
		'els_system_allocation_id','els_system_id', 'vendor_id', 'status'
	];
}
