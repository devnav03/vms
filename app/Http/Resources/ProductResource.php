<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ProductInventoryResource;

class ProductResource extends JsonResource
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
            'product_id'=>$this->id,
            'product_name'=>$this->name,
            'product_image'=>asset('storage/'.$this->image),
            'product_description'=>strip_tags($this->description),
   
            'inventories' => ProductInventoryResource::collection($this->ProductInventories),
        ];
    }
}
