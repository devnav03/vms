<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderInvoiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            // 'id' => $this->id,
            'order_id' => $this->id,
            'total' => $this->total,
            'amount' => $this->sub_total,
            'delivery_option' => $this->delivery_option == 1 ? 'Head Office':($this->delivery_option == 2 ?'Upline':'Courier'),
            'payment_mode' => $this->payment_mode == 1?'Wallet':'Online',
            'payment_status' => $this->payment_status == 1?'Done':'Pending',
            'shipping_charge' => $this->shipping_charge,
            'order_status' => $this->orderStatus->name,    
            'shipping_address' => $this->shipping_address,        
            'billing_address' => $this->billing_address,        
            'user_name' => $this->userDetail->first_name.' '.$this->userDetail->last_name,        
            'user_mobile' => $this->userDetail->mobile,        
            'user_refer_code' => $this->userDetail->refer_code,        
            'user_eamil' => $this->userDetail->email,        
            'cart_data' => CartResource::collection($this->cartData),
        ];
    }
}
