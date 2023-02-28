<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SparePartPriceList extends Model
{
    use SoftDeletes;
    protected $table = 'spare_part_price_list';
	protected $primaryKey = 'id';
	protected $fillable = [
		'series_id', 'colour_id','sku_no','price'
	];
}
