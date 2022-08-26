<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
	protected $fillable = ['key'];
	protected $hidden = ['created_at','updated_at'];
    public function permission(){
		return $this->hasOne(PermissionRole::class,'permission_id','id');
	}
	public function roles(){
		return $this->hasMany(PermissionRole::class,'permission_id','id');
	}
	public function menu(){
		return $this->hasOne(Menu::class,'id','menu_id');
	}
}
