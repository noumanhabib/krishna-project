<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SystemReportDetails extends Model
{
    use SoftDeletes;
    protected $table="system_report_details"; 
	protected $primaryKey = 'id';
	protected $fillable = [
		'system_info_id','store_pick_id','response'
	];
}
