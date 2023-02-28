<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class ELSProductExtraExpence extends Model
{
    use SoftDeletes;
    protected $table = 'els_system_extra_expence';
	protected $primaryKey = 'id';
	protected $fillable = [
		'els_system_id', 'title', 'amount'
	];
}
