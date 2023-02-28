<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InwardDate extends Model
{
    use SoftDeletes;
    protected $table = 'inward_date';
	protected $primaryKey = 'id';
	protected $fillable = [
		'els_system_id','received_date','status'
	];
}
