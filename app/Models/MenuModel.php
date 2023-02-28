<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MenuModel extends Model
{
     use SoftDeletes;
    protected $table="menu"; 
	protected $primaryKey = 'id';
	protected $fillable = [
		'main_menu','menu','slug','status'
	];
}
