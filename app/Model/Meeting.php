<?php
namespace App\Model;
use Illuminate\Database\Eloquent\Model;

//use Illuminate\Database\Eloquent\SoftDeletes;

class Meeting extends Model
{
    //use SoftDeletes;
    // protected $fillable = [
    // 	'id'
    // ];
    public function confrence(){
        return $this->hasOne('\App\Model\ConferenceRoom'::class,'id','room_id');
    }
    // public function getBuilding(){
    //     return $this->hasOne('\App\Model\Building'::class,'id','building_id');
    // }
    // public function getDepartment(){
    //     return $this->hasOne('\App\Model\Department'::class,'id','department_id');
    // }
    // public function getAdmins(){
    //     return $this->hasOne('\App\Admin'::class,'id','officer_id');
    // }
}




