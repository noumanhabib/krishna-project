<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuPermissionModel extends Model
{
    protected $table = 'menu_permission_tb';
	protected $primaryKey = 'id';
	protected $fillable = [
		'role_id', 'menu_id_permission'
	];
	public $timestamps = false;
}
