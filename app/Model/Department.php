<?php
namespace App\Model;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Department extends Model
{
   public function getLocation(){
     return $this->hasOne('App\Model\Location'::class,'id', 'location_id');
   }
   public function building()
   {
     return $this->hasOne('App\Model\Building'::class,'id', 'building_id');
   }

   public function getDeviceDepartment()
   {
     return $this->hasMany('App\Model\DeviceDepartment'::class,'department_id', 'id');
   }
	
   public function getSingleDeviceDepartment()
   {
     return $this->hasOne('App\Model\DeviceDepartment'::class,'department_id', 'id');
   }
	
    public function user()
    {
      return $this->hasMany('App\User'::class,'department_id', 'id');
    }




}
