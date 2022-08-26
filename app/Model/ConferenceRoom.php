<?php
namespace App\Model;
use Illuminate\Database\Eloquent\Model;

//use Illuminate\Database\Eloquent\SoftDeletes;

class ConferenceRoom extends Model
{
    //use SoftDeletes;
    // protected $fillable = [
    // 	'id'
    // ];
    public function getLocation(){
        return $this->hasOne('\App\Model\Location'::class,'id','location_id');
    }
    public function getBuilding(){
        return $this->hasOne('\App\Model\Building'::class,'id','building_id');
    }
    public function getDepartment(){
        return $this->hasOne('\App\Model\Department'::class,'id','department_id');
    }
    public function getAdmins(){
        return $this->hasOne('\App\Admin'::class,'id','officer_id');
    }
    public function getDevice(){
        return $this->hasOne('App\Model\Device'::class,'id', 'device_id');
    }
}




