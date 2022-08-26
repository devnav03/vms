<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
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
            'product_id' => $this->product_id,
            'inventory_id' => $this->inventory_id,
            'product_name' => $this->productDetail->name,
            'product_image' => asset('storage/'.$this->productDetail->image),
            'mrp' => $this->inventoryDetail->mrp,
            'sell_price' => $this->inventoryDetail->msp,
            'discount' => $this->inventoryDetail->mrp*$this->qty - $this->inventoryDetail->msp*$this->qty,
            'qty' => $this->qty,
            'sub_total' => $this->inventoryDetail->msp*$this->qty,
            
        ]; 
    }
}
