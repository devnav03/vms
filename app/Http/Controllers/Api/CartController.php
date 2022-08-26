<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\UserCart;
use App\Model\ProductInventory;
use App\Http\Resources\CartResource;

class CartController extends Controller
{
    public function addToCart(Request $request){
    	$this->validate($request, [
    		'product_id'=>'required|numeric',
    		'qty'=>'required|numeric',
    		'inventory_id'=>'required|numeric',
    	]);

    	$user_id = $request->user()->id;

    	// if($request->product_id == 1){
    	// 	if($request->qty < 2){
    	// 		return response()->json(['error'=>true, 'message'=>'Min required quanity is 2', 'data'=>[]]);
    	// 	}
    	// }

        if($request->qty == 0){
            UserCart::where(['user_id'=>$user_id, 'product_id'=>$request->product_id, 'inventory_id'=>$request->inventory_id])->delete();
            return response()->json(['error'=>false, 'message'=>'Item deleted']);            
        }

    	$cart = UserCart::firstOrNew(['user_id'=>$user_id, 'product_id'=>$request->product_id, 'inventory_id'=>$request->inventory_id]);
    	$cart->qty = $request->qty;
    	$cart->save();

    	return response()->json(['error'=>false, 'message'=>'Product added to cart']);
    }

    public function cartDetail(Request $request){

    	$user_id = $request->user()->id;

    	$data = UserCart::select('id', 'product_id', 'inventory_id', 'qty')->with(['productDetail', 'inventoryDetail'])->where('user_id', $user_id)->get();

    	$data = CartResource::collection($data);

    	$discount = 0;
    	$total = 0;
    	$sub_total = 0;

    	foreach ($data as $item) {
    		$invt_detail = ProductInventory::select('msp', 'mrp')->where('id', $item->inventory_id)->first();
    		$total += $invt_detail->msp*$item->qty;
    		$discount += ($invt_detail->msp*$item->qty - $invt_detail->mrp*$item->qty);
    	}
    	$sub_total = $total - $discount;


    	if(count($data)){
    		return response()->json(['error'=>false, 'message'=>'Cart Detail', 'data'=>$data, 'total'=>$total, 'sub_total'=>$sub_total, 'discount'=>$discount]);
    	}

    	return response()->json(['error'=>false, 'message'=>'No record Found', 'data'=>$data, 'total'=>$total, 'sub_total'=>$sub_total, 'discount'=>$discount]);
    }
}
