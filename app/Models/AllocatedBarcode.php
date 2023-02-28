<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AllocatedBarcode extends Model
{
    use SoftDeletes;
    protected $table = 'els_system_allocated_parts_barcode';
	protected $primaryKey = 'id';
	protected $fillable = [
		'els_system_id','barcode_id','series_id','status'
	];
}
