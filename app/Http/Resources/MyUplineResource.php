<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\UserUplineResource;
use App\Model\UplineTransferDetail;
use App\User;

class MyUplineResource extends JsonResource
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

    public function userDetail($request, $level_id){
        $datas = [];
        $parent_id = $request->user()->parent_id;

        if($parent_id){
            $my_upline = User::select('id', 'first_name', 'last_name', 'current_level', 'refer_code', 'sponsored_by_id', 'parent_id')->where(['id'=>$parent_id, 'activation_status'=>1])->first();

             if($my_upline){
                $my_upline->setAttribute('level_id', $level_id);
             }

            $my_sponsor = User::select('id', 'refer_code', 'first_name', 'last_name')->where(['id'=>$request->user()->sponsored_by_id, 'activation_status'=>1])->first();


            array_push($datas, $my_upline);
            if($parent_id == $my_sponsor->id){                
                $my_sponsor = User::select('id', 'refer_code', 'first_name', 'last_name')->where('id', $my_upline->parent_id)->first();
            }

            // if($my_upline && $my_upline->parent_id !=null){
            //     if(!$my_sponsor || ($my_upline->parent_id == $my_upline->sponsored_by_id)){                
            //         $my_sponsor = User::select('id', 'refer_code', 'first_name', 'last_name')->where('id', $my_upline->parent_id)->first();
            //     }
            // }

            if($my_sponsor){
                $my_sponsor->setAttribute('level_id', $level_id);
                array_push($datas, $my_sponsor);
            }
        }
        return $datas = collect($datas);
    }




    public function toArray($request)
    {
        if($request->level_id){
            $check_transfer = $this->checkTransferDetail($request->user()->id, $this->id, $this->id);
            return [
                'name' => $this->first_name.' '.$this->last_name,
                'refer_code' => $this->refer_code,        
                'you_paid' => $check_transfer['amount'],          
            ];
        }else{
            return [
                'level_id' => $this->id,
                'level_name' => $this->name,                 
                'level_amount' => $this->levelDetail->amount,                 
                'user_detail' => UserUplineResource::collection($this->userDetail($request, $this->id)),                 
            ];
        }
    }
}
