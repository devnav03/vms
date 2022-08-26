<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DmtStatementResource extends JsonResource
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
            'utr' => $this->utr,
            'transaction_id' => 'T20CS-'.$this->transaction_id,
            'transferred_amount' => $this->amount,
            'fee' => $this->transactionDetail->admin_charge,
            'total' => $this->transactionDetail->amount+$this->transactionDetail->admin_charge,
            'channel' => $this->channel == 2 ? 'IMPS':'NEFT',
            'account_number' => $this->account_number,
            'status' => $this->status == 1?'Success':($this->status == 2?'Failure':'Pending'),
            'date' => $this->created_at->toDateTimeString(),
        ];
    }
}
