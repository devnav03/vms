<?php



namespace App\Model;



use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;



class UserBank extends Model

{

	use SoftDeletes;

	protected $fillable = ['user_id'];
	

    public function bankDetail(){

        return $this->hasOne(BankList::class, 'id', 'bank_id');

    }



    public function userDetail(){

    	return $this->hasOne('App\User'::class, 'id', 'user_id');	

    }

    public function dmtWalletDetail(){
		return $this->hasOne('App\Model\DmtWallet'::class, 'user_id', 'user_id');	    	
    }

}

