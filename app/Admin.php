<?php
namespace App;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable{
      use Notifiable,SoftDeletes;
      /**     * The attributes that are mass assignable.     *     * @var array     */
       protected $fillable = ['name', 'email', 'password',];
       /**     * The attributes that should be hidden for arrays.     *     * @var array     */
       protected $hidden = ['password', 'remember_token',];
       public function role(){
         return $this->hasOne(Model\Role::class,'id','role_id');
       }
       public function getDepart(){
         return $this->hasOne(Model\Department::class,'id','department_id');
       }
       public function roles()    {
         return $this->belongsToMany(Model\Role::class, 'role_users');
       }
       public function hasAccess(string $permissions) :bool    {
         if($this->role->hasAccess($permissions)) {
           return true;
         }
         return false;
       }    /**     * Checks if the user belongs to role.     */
       public function permission($permission)    {
         return $this->roles()->where('slug', $roleSlug)->count() == 1;
       }

       public function getDevice(){
         return $this->hasOne(Model\Device::class,'id','device_id');
       }
		
	   public function getLocation(){
         return $this->hasOne(Model\Location::class,'id','location_id');
       }
		
	   public function getBuilding(){
         return $this->hasOne(Model\Building::class,'id','building_id');
       }
	   public function getDepartment(){
         return $this->hasOne(Model\Department::class,'id','department_id');
       }




 }
