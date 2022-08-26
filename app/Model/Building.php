<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class Building extends Model

{
	public function getLocation()
    {
        return $this->hasOne('App\Model\Location'::class,'id', 'location_id');
    }
		public function department(){
	    return $this->hasMany('\App\Model\Department'::class, 'building_id', 'id');
	  }





}
