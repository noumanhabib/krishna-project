<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SparePartListModel extends Model
{
    use SoftDeletes;
    protected $table = 'spare_part_list';
	protected $primaryKey = 'id';
	protected $fillable = [
		'brand_id', 'model_id', 'type_id', 'part_id', 'colour_id', 'series_no', 'price', 'created_by','updated_by'
	];
}
