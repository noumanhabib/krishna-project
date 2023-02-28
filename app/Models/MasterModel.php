<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterModel extends Model
{
    use SoftDeletes;
    protected $table = 'model';
	protected $primaryKey = 'id';
	protected $fillable = [
		'mname','brand_id','mstatus'
	];
}
