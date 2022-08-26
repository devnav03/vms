<?php
namespace App\Model;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class DeviceDepartment extends Model
{

  public function device()
  {
    return $this->hasOne('App\Model\Device'::class,'id', 'device_id');
   }

   public function department()
   {
     return $this->hasOne('App\Model\Department'::class,'id', 'department_id');
    }

}
