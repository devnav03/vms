<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function getRelationName($relation){
        if($relation == 1){
            return 'Father';
        }elseif($relation == 2){
             return 'Mother';
        }elseif($relation == 3){
             return 'Brother';
        }elseif($relation == 4){
             return 'Sister';
        }elseif($relation == 5){
             return 'Wife';
        }elseif($relation == 6){
             return 'Son';
        }elseif($relation == 7){
             return 'Daughter';
        }
    }

    public function toArray($request)
    {
        return [
            'wallet_amount'=>@$this->walletDetail?@$this->walletDetail->amount:0.00,
            'transferrable_balance'=>@$this->DmtWalletDetail?@$this->DmtWalletDetail->amount:0.00,
            'total_wallet_balance'=>(@$this->DmtWalletDetail?@$this->DmtWalletDetail->amount:0.00)+(@$this->walletDetail?@$this->walletDetail->amount:0.00),
            
            'outstanding_amount'=>@$this->outstandingBalanceDetail ? @$this->outstandingBalanceDetail->where('status', 1)->sum('transferred_amount') : 0.00,
            'current_level'=>@$this->current_level > 0 ? @$this->userLevel->name:'NA',
            'current_level_id'=>@$this->current_level,
            'group'=>@$this->group_id,
            'first_name'=>@$this->first_name,
            'last_name'=>@$this->last_name,
            'mobile'=>@$this->mobile,
            'email'=>@$this->email,
            'zoom_user_name'=>@$this->userDetail->zoom_user_name,
            'zoom_user_password'=>@$this->userDetail->zoom_user_password,
            'state'=>@$this->userDetail->state_name,
            'city'=>@$this->userDetail->city_name,
            'pin_code'=>@$this->userDetail->pin_code,
            'address'=>@$this->userDetail->address,
            'notification'=>'',
            'nominee_detail'=>(object)(@$this->nomineeDetail?[
                                'nominee_name'=>@$this->nomineeDetail->nominee_name,
                                'relation_id'=>@$this->nomineeDetail->relation,
                                'relation_name'=>@$this->getRelationName(@$this->nomineeDetail->relation),
                                'email'=>@$this->nomineeDetail->email,
                                'mobile'=>@$this->nomineeDetail->mobile,                
                        ]:[]),
            'kyc_detail'=>(object)(@$this->kycDetail?[
                            'pan_number'=>@$this->kycDetail->pan_number,
                            'pan_photo'=>url('/storage/'.@$this->kycDetail->pan_photo),
                            'id_front'=>url('/storage/'.@$this->kycDetail->id_front),
                            'id_back'=>url('/storage/'.@$this->kycDetail->id_back),
                            'status'=>@$this->kycDetail->status==0?'Processing':(@$this->kycDetail->status==1?'Approved':'Rejected'),
                            'rejection_message'=>@$this->kycDetail->rejection_message,
                        ]:[]),
            'bank_detail'=>(object)(@$this->userBankDetail?[
                            'account_name'=>@$this->userBankDetail->account_name,
                            'account_number'=>@$this->userBankDetail->account_number,
                            'ifsc_code'=>@$this->userBankDetail->ifsc_code,
                            'bank_name'=>@$this->userBankDetail->bankDetail->name,
                        ]:[]),
        ];
    }
}
