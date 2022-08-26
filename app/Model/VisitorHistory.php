<?php

namespace App\Model;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class VisitorHistory extends Model
{
	use SoftDeletes;
	protected $fillable=['last_synchronize_date'];
	
}





