<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = ['table_name','order','parent_id'];
	protected $hidden = ['created_at','updated_at','deleted_at'];

	public function child(){
    	return $this->hasMany(Menu::class,'parent_id','id')->whereNotNull('controller')->orderBy('order','asc')->where('status','1');
    } 
    public function childs(){
    	return $this->hasMany(Menu::class,'parent_id','id')->orderBy('order','asc');
    } 
    public function permission(){
		return $this->hasOne(Permission::class,'menu_id','id');
	}
}
