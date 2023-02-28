<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ElsSystemPackagingVideo extends Model
{
    use SoftDeletes;
    protected $table = 'els_system_packaging_video';
	protected $primaryKey = 'id';
	protected $fillable = [
		'els_system_id','video_path','status'
	];
}
