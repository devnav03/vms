<?php



namespace App\Model;



use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;



class Transaction extends Model

{

 
   use SoftDeletes;
	protected $fillable = ['transaction_type_id', 'credit_by', 'debit_to', 'amount', 'kyc_status', 'tds_charge', 'admin_charge', 'status', 'remark'];



    public function fromUserDetail(){

		return $this->hasOne('App\User'::class, 'id', 'from_user_id');

	}



	public function toUserDetail(){

		return $this->hasOne('App\User'::class, 'id', 'to_user_id');

	}



	public function transactionType(){

		return $this->hasOne(TransactionType::class, 'id', 'transaction_type_id');

	}



	public function franchiseeDetail(){

    	return $this->hasOne('App\Franchisee'::class, 'id', 'to_user_id');

    }



    public function toFranchiseeDetail(){

		return $this->hasOne('App\Franchisee'::class, 'id', 'to_user_id');

	}



	public function commissionDetail(){

		return $this->hasOne(FranchiseeCommissionDetail::class, 'transaction_id', 'id');

	}



	public function repurchaseIncomeDetail(){

		return $this->hasOne(RepurchaseIncomeDetail::class, 'transaction_id', 'id');

	}
	
	public function pinHistory(){
		return $this->hasOne(UserPinHistory::class, 'transaction_id', 'id');
	}
}

