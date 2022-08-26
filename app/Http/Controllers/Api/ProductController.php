<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Model\Product;

use App\Http\Resources\ProductResource;

class ProductController extends Controller

{
    public function availableProducts(Request $request){

    	$data = Product::select('id', 'name', 'image', 'tax', 'description')->with(['ProductInventories'=>function($query){

    		$query->select('id', 'product_id', 'mrp', 'msp', 'min_qty', 'max_qty');

    	}])->where('product_type', 0)->limit(2)->get();

    	$data = ProductResource::collection($data);

    	return response()->json(['error'=>false, 'message'=>'Available Products', 'data'=>$data]);
    
    }

    public function repurchaseProducts(Request $request){

    	$data = Product::select('id', 'name', 'image', 'tax', 'description')->with(['ProductInventories'=>function($query){

    		$query->select('id', 'product_id', 'mrp', 'msp', 'min_qty', 'bv', 'max_qty');

    	}])->where('product_type', 1)->get();

    	$data = ProductResource::collection($data);

    	return response()->json(['error'=>false, 'message'=>'Repurchase Products', 'data'=>$data]);

    }

}

