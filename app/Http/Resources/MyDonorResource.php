<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Model\UplineTransferDetail;

class MyDonorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    private function checkTransferDetail($from_user_id, $to_user_id, $level_id){
        $transfer_detail = UplineTransferDetail::select('transferred_amount', 'status', 'created_at')->where(['from_user_id'=>$from_user_id, 'plan_level_id'=>$level_id, 'to_user_id'=>$to_user_id])->first();
        if($transfer_detail){
            return ['amount'=>$transfer_detail->transferred_amount, 'status'=>$transfer_detail->status == 1?'Paid':'Outstanding', 'date'=>$transfer_detail->created_at->toDateTimeString()];
        }else{
            return ['amount'=>'NA', 'status'=>'NA', 'date'=>'NA'];
        }
    }

    public function toArray($request)
    {   
        if($request->level_id){
            $check_transfer = $this->checkTransferDetail($this->id, $request->user()->id, $request->level_id);
            return [
                'name' => $this->first_name.' '.$this->last_name,
                'refer_code' => $this->refer_code,        
                'activation_status' => $this->activation_status == 1? 'Active':'Inactive',        
                'current_level' => $this->userLevel->name??'NA',        
                'pay_status' => $check_transfer['status'],        
                'amount' => $check_transfer['amount'],        
                'date' => $check_transfer['date'],        
            ];
        }else{
            return [
                'level_id' => $this->plan_level_id,
                'level_name' => $this->planLevelDetail->name,                 
            ];
        }
    }
}

