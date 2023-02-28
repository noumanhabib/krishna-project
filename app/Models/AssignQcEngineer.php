<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssignQcEngineer extends Model
{
    use SoftDeletes;
    protected $table = 'els_system_allocated_qc_engineer';
	protected $primaryKey = 'id';
	protected $fillable = [
		'els_system_id','engineer_id','status','active','remark'
	];
}
