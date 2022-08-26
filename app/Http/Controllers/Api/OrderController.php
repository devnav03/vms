<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Events\SmsEvent;
use App\Model\Order;
use App\Model\UserCartData;
use App\Model\UserCart;
use App\Model\ProductInventory;
use App\Model\UserWallet;
use App\Model\DmtWallet;
use App\Model\UserAddress;
use App\Model\Transaction;
use App\Model\FranchiseeOrder;
use App\Http\Resources\OrderResource;
use App\Http\Resources\OrderInvoiceResource;
use Carbon\Carbon;
use App\User;

class OrderController extends Controller
{
   
   public function deliveryOptions(Request $request){
	   	$data = collect([
	   		(object)['id'=>1, 'name'=>'Franchisee', 'delivery_charge'=>0],
	   		// (object)['id'=>2, 'name'=>'Senior', 'delivery_charge'=>0],
	   		(object)['id'=>3, 'name'=>'Courier', 'delivery_charge'=>0],
	  	]);

	  	return response()->json(['error'=>false, 'message'=>'Available delivery options', 'data'=>$data]);
   }


   public function createOrder(Request $request){

   		$this->validate($request, [
   			// 'shipping_id'=>'required|numeric',
   			'billing_address_id'=>'nullable|numeric',
        'delivery_option'=>'required|numeric',
   			'pay_mode'=>'required',
        'upline_refer_id'=>'nullable|exists:users,refer_code'
   			// 'delivery_charge'=>'required|numeric',
   		]);

      if($request->delivery_option == 1){
        $this->validate($request, [
          'franchisee_id'=>'required',
        ]);
      }

      if($request->pay_mode != 'wallet'){
        return response()->json(['error'=>true, 'message'=>'invalid paymode!']);
      }

      $user_id = $request->user()->id;

      if($user_id != 1){
        return response()->json(['error'=>true, 'message'=>'This Service is Under Maintannce!']); 
      }

      $delivery_charge = 0;

      // $delivery_options = collect([
      //     (object)['id'=>1, 'name'=>'Franchisee', 'delivery_charge'=>0],
      //     (object)['id'=>2, 'name'=>'Upline', 'delivery_charge'=>0],
      //     (object)['id'=>3, 'name'=>'Courier', 'delivery_charge'=>0],
      // ]);

      if($request->delivery_option){

            $delivery_option_id = $request->delivery_option;

            // $delivery_option = $delivery_options->where('id', $request->delivery_option)->first();

            // $delivery_charge = $delivery_option->delivery_charge;
            $delivery_charge = 0;

            if($request->delivery_option == 3){
                $user_address = UserAddress::where('id', $request->billing_address_id)->first();
            }
        }


   		$cart_data = UserCart::select('id', 'product_id', 'inventory_id', 'qty')->with(['inventoryDetail'])->where('user_id', $user_id)->get();

      if(!$cart_data){
        return response()->json(['error'=>true, 'message'=>'No data found in your cart']);
      }

    	$discount = 0;
    	$total = 0;
    	$sub_total = 0;
      $total_bv = 0;
    	$tax = 0;

    	foreach ($cart_data as $key=>$item) {
    		$invt_detail = ProductInventory::select('product_id', 'msp', 'mrp', 'bv', 'igst', 'rate')->where('id', $item->inventory_id)->first();

          $total += $invt_detail->mrp*$item->qty;
          $discount += ($invt_detail->mrp*$item->qty - $invt_detail->msp*$item->qty);
          $total_bv += $invt_detail->bv*$item->qty;
          $tax += ($invt_detail->rate*$invt_detail->igst/100);
          
          if($key == 0){
            $product_id = $invt_detail->product_id;  
          }
    		// $tax += $invt_detail->tax*$item->qty;
    	}

    	$sub_total = $total - $discount + $delivery_charge;

      $transaction_type_id = 5; #Order Placement

      // if($request->pay_mode == 'wallet'){
        $wallet = DmtWallet::select('id', 'amount')->where('user_id', $user_id)->first();
        if(!$wallet || $wallet->amount < $sub_total){

          return response()->json(['error'=>true, 'message'=>'Insufficient fund in your wallet!']);
        }

        $wallet->amount -= $sub_total;
        $wallet->save();

        $pay_mode=1; #wallet
        $pay_status = 1;

        $transaction_id = $this->saveTransaction($type='dr', $amount=$sub_total, $transaction_type_id, $user_id, $user_id, $status=1, $pay_mode);
      // }


      // else{
      //   $pay_mode=2; #online
      //   $transaction_id = $this->saveTransaction($type='dr', $amount=$sub_total, $transaction_type_id, $user_id, $user_id, $status=0, $pay_mode);
      //   $pay_status = 0;
      // }

    	$order = new Order();
      $order->user_id = $user_id;
      $order->device = 2;
      $order->transaction_id = $transaction_id;
      $order->order_status_id = 1;
      $order->total = $total;
      $order->sub_total = $sub_total;
      $order->discount = $discount;
      $order->bv = $total_bv;
      $order->tax = $tax;
      $order->delivery_option = $request->delivery_option;
      if($request->delivery_option == 3){
        $order->shipping_address = $user_address->name.', '.$user_address->address.', '.$user_address->city_name.', '.$user_address->state_name.', '.$user_address->pin_code.', Mobile-'.$user_address->mobile;         
      }
      $order->payment_status = $pay_status;
      $order->upline_refer_id = $request->upline_refer_id;
      $order->payment_mode = $pay_mode;
      $order->shipping_charge = $delivery_charge;
      $order->save();

      $delete_stock = true;
      if($request->pay_mode == 'online'){
        $delete_stock = false;
      }

      $this->saveCartData((object)$cart_data, $order->id,  $delete_stock);

      $user_id = $request->user()->id;

      if(!Order::select('id')->where('user_id', $user_id)->whereIn('order_status_id', [2,3,5])->exists() && $product_id < 3){
          $transaction_id = $this->saveTransaction($type='dr', $amount=2000, $transaction_type_id=6, $from=0, $to=$user_id, $status=0, $pay_mode=1);
          $user_wallet = UserWallet::firstOrNew(['user_id'=>$user_id]);
          $user_wallet->amount += 2000;
          $user_wallet->save();

          #usertable autopool

          $fetch_parent = $this->fetchParentId($request->user()->sponsored_by_id);

          if(!$fetch_parent){
              $fetch_parent['parent_id'] = 1;            
          }

          $request->user()->parent_id = $fetch_parent['parent_id'];
          $request->user()->activation_date = Carbon::now();
          $request->user()->current_level += 1;
          $request->user()->activation_status = 1;
          $request->user()->save();
          $order->order_type = 1; #1st purchase

      }
      $order->order_status_id = 2;
      $order->order_type = 2; #Repurchase
      $order->save();

      if($request->delivery_option == 1){
        
          $franchise_order = new FranchiseeOrder();
          $franchise_order->order_id = $order->id;
          $franchise_order->franchise_id = $request->franchisee_id;
          $franchise_order->save();
      }

      UserCart::where('user_id', $user_id)->delete();

      if ($request->user()->mobile){

        // event(new SmsEvent($request->user()->mobile, 'Order Placed: Your order with Order ID ANJ/GST/19-20/0'.$order->id.' for amount Rs. '.$order->sub_total.' and BV '.$total_bv.' has been received at T20cs.com . We will send you another message when your order is packed/shipped. Thankyou! Team T20cs.'));

      }

      return response()->json(['error'=>false, 'message'=>'Order Successfully Created', 'data'=>new OrderInvoiceResource($order)]);


    	$data = [
    		'amount' => $sub_total,
    		'transaction_id' => $transaction_id,
    	];
      if($request->pay_mode == 'online'){
        
        return response()->json(['error'=>false, 'message'=>'Order Created For online Payment', 'data'=>$data]);
      }

    	return response()->json(['error'=>false, 'message'=>'Order Successfully Created', 'data'=>$data]);
   }

   public function saveTransaction($type, $amount, $transaction_type_id, $from, $to, $status, $pay_mode){

      $transaction = new Transaction();
      $transaction->type = $type;
      $transaction->transaction_type_id = $transaction_type_id;
      $transaction->pay_mode = $pay_mode;
      $transaction->from_user_id = $from;
      $transaction->to_user_id = $to;
      $transaction->amount = $amount;
      $transaction->status = $status;
      $transaction->save();

      return $transaction->id;
    }

   public function saveCartData($cart_data, $order_id){
	   	foreach ($cart_data as $item) {

	   		$invt_detail = ProductInventory::select('msp', 'mrp', 'available_stock', 'rate', 'igst', 'cgst', 'sgst')->where('id', $item['inventory_id'])->first();
    		$user_cart = new UserCartData();
    		$user_cart->order_id = $order_id;
    		$user_cart->product_id = $item->product_id;
    		$user_cart->inventory_id = $item->inventory_id;
    		$user_cart->qty = $item->qty;
        $user_cart->rate = $invt_detail->rate;
        $user_cart->cgst = $invt_detail->cgst;
        $user_cart->sgst = $invt_detail->sgst;
    		$user_cart->msp = $invt_detail->msp;
    		$user_cart->mrp = $invt_detail->mrp;
    		$user_cart->available_stock = $invt_detail->available_stock;
    		$user_cart->save();
	   	}
	   	return 'Successful';
   	}

   	public function updateOrder(Request $request){
   		$this->validate($request, [
   			'pay_mode'=>'required'
   		]);
   	}

    public function myOrders(Request $request){

      $user_id = $request->user()->id;

      $data = Order::select('id', 'user_id', 'total', 'sub_total', 'delivery_option', 'order_status_id', 'payment_mode', 'payment_status', 'shipping_charge', 'created_at')->where(['user_id'=>$user_id, 'payment_status'=>1])->with(['orderStatus'=>function($query){
        $query->select('id', 'name');
      }, 'cartData'=>function($query){
        $query->with(['productDetail'=>function($query){
          $query->select('id', 'name', 'image');
        }]);
      }])->get();

      if(count($data)){
        $data = OrderResource::collection($data);
        return response()->json(['error'=>false, 'message'=>'My Orders', 'data'=>$data]);
      }

      return response()->json(['error'=>false, 'message'=>'No Record Found', 'data'=>$data]);
    }

    public function orderInvoice(Request $request){

        $this->validate($request, [
          'order_id'=>'required|exists:orders,id'
        ]);

      $user_id = $request->user()->id;

      $data = Order::where(['id'=>$request->order_id, 'user_id'=>$user_id])->with(['userDetail'=>function($query){
        $query->select('id', 'first_name', 'last_name', 'mobile', 'refer_code', 'email');
      }, 'cartData'=>function($query){
        $query->select('order_id', 'inventory_id', 'product_id', 'qty', 'msp')->with(['productDetail'=>function($query){
          $query->select('id', 'name', 'image');
        }]);
      }, 'orderStatus'=>function($query){
        $query->select('id', 'name');
      }])->first();

      if($data){

        $data = new OrderInvoiceResource($data);

        return view('api.order-invoice');

        return response()->json(['error'=>false, 'message'=>'Order Invoice', 'data'=>$data]);
      }
      return view('api.order-invoice');
      return response()->json(['error'=>false, 'message'=>'No Record Found', 'data'=>$data]);
    }

    public function fetchParentId($user_id){        

        $user_detail = User::select('id', 'parent_id', 'activation_status')->where('id', $user_id)->first();

        if($user_detail->activation_status == 1){

          $user_downlines = User::select('id')->where(['parent_id'=>$user_id, 'activation_status'=>1])->get();          

          if(count($user_downlines) >= 2){      

            $left_downline = $this->getParentId($user_downlines[0]->id);

            if($left_downline['total_downline'] < 2){

              return ['parent_id'=>$left_downline['parent_id'], 'team_group'=>$left_downline['team_group']];

            }else{

                $right_downline = $this->getParentId($user_downlines[1]->id);          


                if($left_downline['total_downline'] <= $right_downline['total_downline']){


                    return ['parent_id'=>$left_downline['parent_id'], 'team_group'=>$left_downline['team_group']];

                }else{

                  return ['parent_id'=>$right_downline['parent_id'], 'team_group'=>$right_downline['team_group']];
                }
            }
          }else{
            if(count($user_downlines) == 0){
              return ['parent_id'=>$user_id, 'team_group'=>1];
            }else{
              return ['parent_id'=>$user_id, 'team_group'=>2];
            }
          }
        }

        $current_id = $user_detail->parent_id;

        while($current_id > 0)
        {  
          $parent_detail = User::select('id', 'parent_id', 'activation_status')->where(['id'=>$current_id])->first();
          if($parent_detail->activation_status == 1){

              $parent_downlines = User::select('id')->where(['parent_id'=>$parent_detail->id, 'activation_status'=>1])->get();

              if(count($parent_downlines) >= 2){      

                $left_downline = $this->getParentId($parent_downlines[0]->id);

              if($left_downline['total_downline'] < 2){

                return ['parent_id'=>$left_downline['parent_id'], 'team_group'=>$left_downline['team_group']];

              }else{

                  $right_downline = $this->getParentId($parent_downlines[1]->id);

                  if($left_downline['total_downline'] <= $right_downline['total_downline']){

                      return ['parent_id'=>$left_downline['parent_id'], 'team_group'=>$left_downline['team_group']];

                  }else{

                    return ['parent_id'=>$right_downline['parent_id'], 'team_group'=>$right_downline['team_group']];
                  }
              }
            }else{
              if(count($parent_downlines) == 0){
                return ['parent_id'=>$parent_detail->id, 'team_group'=>1];
              }else{
                return ['parent_id'=>$parent_detail->id, 'team_group'=>2];
              }
            }
          }  

          $current_id = $parent_detail->id;
        }
        return ['parent_id'=>$user_id, 'team_group'=>1];
    }

    public function getParentId($user_id){        

        $current_ids = [];

        $user_ids = '';

        $count_downline = 0;

        $downlines = User::where(['parent_id'=>$user_id, 'activation_status'=>1])->get();

        if(count($downlines) < 2){
            if(count($downlines) == 0){
              return ['parent_id'=>$user_id, 'total_downline'=>count($downlines), 'team_group'=>1];
            }else{
              return ['parent_id'=>$user_id, 'total_downline'=>count($downlines), 'team_group'=>2];
            }
        }


        $current_ids = $downlines->pluck('id')->toArray();        

        if(count($current_ids)){

            $user_ids = (implode(',',$current_ids));            

            $count_downline += count($current_ids);
        }

        while(count($current_ids))
        {              
            foreach ($current_ids as $current_id) {
                $downlines = User::select('id')->where(['parent_id'=>$current_id, 'activation_status'=>1])->get();                
                if(count($downlines) < 2){

                  if(count($downlines) == 0){
                    return ['parent_id'=>$current_id, 'total_downline'=>$count_downline, 'team_group'=>1];
                  }else{
                    return ['parent_id'=>$current_id, 'total_downline'=>$count_downline, 'team_group'=>2];
                  }
                }
            }     

            $data = DB::select('SELECT `id` FROM `users` WHERE `parent_id` in ('.$user_ids.') AND `activation_status` = 1');

            $current_ids = array_pluck($data, 'id');

            if(count($current_ids)){

                $count_downline += count($current_ids);

                $user_ids = (implode(',',$current_ids));
            }
        }

        return ['parent_id'=>null, 'total_downline'=>$count_downline, 'team_group'=>null];
    }
}
