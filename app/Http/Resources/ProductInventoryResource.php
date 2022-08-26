<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductInventoryResource extends JsonResource
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
            'product_id'=>$this->product_id,
            'inventory_id'=>$this->id,
            'mrp'=>$this->mrp,
            'selling_price'=>$this->msp,
            'bv'=>$this->bv,
            'tax'=>$this->tax??0,
            'discount'=>$this->mrp - $this->msp,
        ];
    }
}
