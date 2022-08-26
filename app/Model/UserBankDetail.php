<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class UserBankDetail extends Model
{
    protected $fillable = [
        'id','user_id', 'bank_id','account_name' ,'account_number','ifsc_code'
    ];

   public function bankDetail(){
        return $this->hasOne('\App\Model\Bank'::class, 'id', 'bank_id');
    }

    public function userDetail(){
		return $this->hasOne('App\User'::class, 'id', 'user_id');
	}

}