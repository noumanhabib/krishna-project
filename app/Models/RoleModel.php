<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class RoleModel extends Model
{
    use SoftDeletes;
    protected $table = 'role';
	protected $primaryKey = 'id';
	protected $fillable = [
		 'name', 'IsActive'
	];
}
