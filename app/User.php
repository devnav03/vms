<?php



namespace App;

use Laravel\Passport\HasApiTokens;

use Illuminate\Notifications\Notifiable;

use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Database\Eloquent\SoftDeletes;



class User extends Authenticatable

{
    // use SoftDeletes;

    use HasApiTokens, Notifiable;


    /**

     * The attributes that are mass assignable.

     *

     * @var array

     */

    protected $fillable = [

        'name', 'email', 'address_1', 'password', 'status', 'current_level', 'activation_status', 'refer_code', 'group_id', 'team_promotion_level'

    ];



    /**

     * The attributes that should be hidden for arrays.

     *

     * @var array

     */

    protected $hidden = [

        'password', 'remember_token',

    ];



    /**

     * The attributes that should be cast to native types.

     *

     * @var array

     */



    public function userDetail(){

        return $this->hasOne('\App\Model\UserDetail'::class, 'user_id', 'id');

    }


    public function parentDetail(){

        return $this->hasOne('\App\Admin'::class, 'id', 'added_by');

    }

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
      return $this->hasOne('\App\Model\Building'::class, 'id', 'building_id');
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
    
	  public function all_visit(){
      return $this->hasOne('\App\Model\AllVisit'::class, 'user_id', 'id');
    }
    
	

	  public function getInOutStatus(){
      return $this->hasOne('\App\Model\AllVisit'::class, 'user_id', 'id');
    }
	
	 public function role(){
         return $this->hasOne(Model\Role::class,'id','type');
       }
      
}
