<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterBrandModel extends Model
{
    use SoftDeletes;
    protected $table = 'brand';
	protected $primaryKey = 'id';
	protected $fillable = [
		'bname','bstatus'
	];
}
