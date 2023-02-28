<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class QcStatus extends Model
{
    use SoftDeletes;
    protected $table = 'quality_check_status';
	protected $primaryKey = 'id';
	protected $fillable = [
		'name', 'status'
	];
}
