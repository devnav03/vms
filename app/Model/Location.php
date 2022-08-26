<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class Location extends Model

{

  public function building(){
    return $this->hasMany('\App\Model\Building'::class, 'location_id', 'id');
  }

  
}
