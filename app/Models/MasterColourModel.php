<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterColourModel extends Model
{
	use SoftDeletes;
    protected $table = 'colour';
	protected $primaryKey = 'id';
	protected $fillable = [
		'name','status'
	];
}
