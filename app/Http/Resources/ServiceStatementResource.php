<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ServiceStatementResource extends JsonResource
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
            'transaction_id' => 'T20CS-'.$this->transaction_id,
            'customer_number' => $this->customer_number,
            'amount' => $this->transactionDetail->amount,
            'service_name' => $this->serviceDetail->service_name,
            'status' => $this->status == 1?'Success':($this->status == 2?'Failed':'Pending'),
            'date' => $this->created_at->toDateTimeString(),
        ];
    }
}
