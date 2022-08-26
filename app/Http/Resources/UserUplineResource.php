<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Model\UplineTransferDetail;

class UserUplineResource extends JsonResource
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
            return ['amount'=>$transfer_detail->transferred_amount];
        }else{
            return ['amount'=>0];
        }
    }

     public function toArray($request)
    {
        if($this){
            $check_transfer = $this->checkTransferDetail($request->user()->id, $this->id, $this->level_id);
            return [
                'name' => $this->first_name.' '.$this->last_name,
                'refer_code' => $this->refer_code,        
                'you_paid' => $check_transfer['amount']??0.0,                
            ];
        }else{
            return [];
        }
        
        
    }
}
