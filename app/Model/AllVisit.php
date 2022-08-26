<?php



namespace App\Model;

use GuzzleHttp\Psr7\Request;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;



class AllVisit extends Model

{


   use SoftDeletes;

   public function OfficerDetail(){

       return $this->hasOne('\App\Admin'::class, 'id', 'officer_id');

   }
   public function OfficerDepartment(){

       return $this->hasOne('\App\Model\Department'::class, 'id', 'department_id');

   }

   public function visitorGroup(){
       return $this->hasMany('\App\Model\VisitorGroup'::class, 'user_id', 'id');
   }
   public function Country(){
     return $this->hasOne('\App\Model\Country'::class, 'id', 'country_id');
   }
   public function State(){
     return $this->hasOne('\App\Model\State'::class, 'id', 'state_id');
   }
   public function City(){
     return $this->hasOne('\App\Model\City'::class, 'id', 'city_id');
   }
   public function location(){
     return $this->hasOne('\App\Model\Location'::class, 'id', 'location_id');
   }
   public function building(){
     return $this->hasOne('\App\Model\Building'::class, 'id', 'location_id');
   }
   public function OrgaCountry(){
     return $this->hasOne('\App\Model\Country'::class, 'id', 'orga_country_id');
   }
   public function OrgaState(){
     return $this->hasOne('\App\Model\State'::class, 'id', 'orga_state_id');
   }
   public function OrgaCity(){
     return $this->hasOne('\App\Model\City'::class, 'id', 'orga_city_id');
   }
  
   public function getVisitor(){
     return $this->hasOne('\App\User'::class, 'id', 'user_id');
   }


   
}

