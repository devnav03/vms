<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use App\Model\MoneyTransferBank;
use App\Model\BeneficiaryList;
use App\Model\MoneyTransferDetail;
use App\Model\UserWallet;
use App\Model\DmtWallet;
use App\Model\Transaction;
use App\Helpers\pay2AllApi;
use App\Events\SmsEvent;
use App\Model\UplineTransferDetail;
use App\User;

class MoneyTransferController extends Controller
{
    public function getBankList(){
   		
   		$data = MoneyTransferBank::select('bank_name', 'bank_code')->get();

        return response()->json(['error'=>false, 'message'=>'Bank List', 'data'=>$data]);   
   }

   public function senderDetail(Request $request){


        $url = 'https://www.pay2all.in/api/dmr/v2/verification';
        $data = [
        	'mobile_number'=>$request->user()->mobile,
        	'vendor_id'=>config('PAY2ALL_VENDOR_ID')
        ];
        $response = pay2AllApi::callApi($method='POST', $url, $data, $header=true);

        $response = (object)$response;

        if(isset($response->status)){
        	if($response->status == 0){
        		return response()->json(['error'=>false, 'message'=>'Sender Successfully Authorized', 'data'=>$response, 'otp'=>false]);   
        	}elseif($response->status == 274){ #customer_id does not exist in system

        		return $this->addSender($request->user()->mobile, $request);

        	}elseif($response->status == 3){

        		return response()->json(['error'=>false, 'message'=>$response->message, 'data'=>$response, 'otp'=>true]);
        	}
        }else{
			return response()->json(['error'=>true, 'message'=>'Something Went wrong', 'data'=>'', 'otp'=>true]);           	
        }
   }


   public function addSender($mobile, $request){

        $url = 'https://www.pay2all.in/api/dmr/v2/add_sender';
        $data = [
        	'mobile_number'=>$request->user()->mobile,
        	'first_name'=>$request->user()->first_name,
        	'last_name'=>$request->user()->last_name,
        	'vendor_id'=>config('PAY2ALL_VENDOR_ID')
        ];
        $response = pay2AllApi::callApi($method='POST', $url, $data, $header=true);

        $response = (object)$response;

        if(isset($response->status)){
        	if($response->status == 0){
        		return response()->json(['error'=>false, 'message'=>'Otp sent to registered mobile no.', 'data'=>$response, 'otp'=>true]);   
        	}else{
        		return response()->json(['error'=>true, 'message'=>$response->message, 'data'=>'', 'otp'=>true]);	
        	}
        }else{
			return response()->json(['error'=>true, 'message'=>$response->message, 'data'=>'', 'otp'=>true]);           	
        }
   	}

   	public function verifySender(Request $request){
   		$this->validate($request, [
   			'sender_id'=>'required',
   			'otp'=>'required'
   		]);


        $url = 'https://www.pay2all.in/api/dmr/v2/add_sender_confirm';
        $data = [
        	'mobile_number'=>$request->user()->mobile,
        	'senderid'=>$request->sender_id,
        	'otp'=>$request->otp,
        	'vendor_id'=>config('PAY2ALL_VENDOR_ID')
        ];
        $response = pay2AllApi::callApi($method='POST', $url, $data, $header=true);

        $response = (object)$response;

        if(isset($response->status)){
        	if($response->status == 0){
        		return response()->json(['error'=>false, 'message'=>$response->message, 'data'=>$response, 'otp'=>false]);   
        	}else{
        		return response()->json(['error'=>true, 'message'=>$response->message, 'data'=>'', 'otp'=>true]);	
        	}
        }else{
			return response()->json(['error'=>true, 'message'=>$response->message, 'data'=>'', 'otp'=>true]);           	
        }
   	}

   	public function addBeneficiary(Request $request){
   		$this->validate($request, [
   			'sender_id'=>'required',
   			'account_number'=>'required|numeric',
   			'ifsc_code'=>'required|max:15',
   			'bank_code'=>'required|exists:money_transfer_banks,bank_code',
   			'bank_name'=>'required',
   			'name'=>'required|max:80',
   		]);
   		

        $url = 'https://www.pay2all.in/api/dmr/v2/add_beneficiary';
        $data = [
        	'mobile_number'=>$request->user()->mobile,
        	'account'=>$request->account_number,
        	'ifsc'=>$request->ifsc_code,
        	'bankcode'=>$request->bank_code,
        	'senderid'=>$request->sender_id,
        	'name'=>$request->name,
        	'vendor_id'=>config('PAY2ALL_VENDOR_ID')
        ];
        $response = pay2AllApi::callApi($method='POST', $url, $data, $header=true);

        $response = (object)$response;

        if(isset($response->status)){
        	if($response->status == 0){
        		$this->saveBeneficiary($request, $response);
        		return response()->json(['error'=>false, 'message'=>'Beneficiary Successfully Added', 'data'=>$response]);   
        	}else{
        		return response()->json(['error'=>true, 'message'=>$response->message, 'data'=>'']);	
        	}
        }else{
			return response()->json(['error'=>true, 'message'=>$response->message, 'data'=>'']);           	
        }
   	}

   	public function saveBeneficiary($request, $response){

   		$ben_detail = BeneficiaryList::firstOrNew(['beneficiary_id'=>$response->beneficiaryid, 'account_number'=>$request->account_number]);
   		$ben_detail->user_id = $request->user()->id;
   		$ben_detail->sender_id = $request->sender_id;
   		$ben_detail->account_name = $request->name;
   		$ben_detail->account_number = $request->account_number;
   		$ben_detail->ifsc = $request->ifsc_code;
   		$ben_detail->bank_code = $request->bank_code;
   		$ben_detail->bank_name = $request->bank_name;
   		$ben_detail->save();

   		return response()->json(['error'=>false, 'message'=>'Beneficiary Successfully Added']);
   	}

   	public function beneficiaryList(Request $request){
   		$this->validate($request, [
   			'sender_id'=>'required'
   		]);

        $url = 'https://www.pay2all.in/api/dmr/v2/get_all_beneficiary';
        $data = [
        	'mobile_number'=>$request->user()->mobile,
        	'vendor_id'=>config('PAY2ALL_VENDOR_ID')
        ];
        $response = pay2AllApi::callApi($method='POST', $url, $data, $header=true);

        $response = (object)$response;

        if($response->status == 2){
            return response()->json(['error'=>false, 'message'=>'No Record Found', 'data'=>[]]);   
        }


        foreach($response->data as $ben){
        	if(!BeneficiaryList::select('beneficiary_id')->where(['beneficiary_id'=>$ben->beneficiary_code])->exists()){
        		$add_ben = new BeneficiaryList();
        		$add_ben->user_id = 1;
        		$add_ben->sender_id = $request->sender_id;
        		$add_ben->beneficiary_id = $ben->beneficiary_code;
        		$add_ben->account_name = $ben->recipient_name;
        		$add_ben->bank_name = $ben->bank;
        		$add_ben->account_number = $ben->account;
        		$add_ben->ifsc = $ben->ifsc;
        		$add_ben->save();
        	}
        } 

        if(isset($response->status)){
        	if($response->status == 0){
        		return response()->json(['error'=>false, 'message'=>'Beneficiary List', 'data'=>$response->data]);   
        	}else{
        		return response()->json(['error'=>true, 'message'=>$response->message, 'data'=>$response]);	
        	}
        }else{
			return response()->json(['error'=>true, 'message'=>$response->message, 'data'=>$response]);           	
        }
   	}

   	public function transferMoney(Request $request){

    //  return response()->json(['error'=>true, 'message'=>'This Service will be available in few moments.']);
      
   		$this->validate($request, [
   			'sender_id'=>'required',
   			'beneficiary_id'=>'required',
   			'account'=>'required|numeric',
   			'amount'=>'required|integer|between:110,5000',
   			'channel'=>'required',
   		]);

   		$amount = (int)$request->amount;
      $user_id = $request->user()->id;

      $user_wallet = DmtWallet::select('id', 'user_id', 'amount')->where('user_id', $user_id)->first();

      if(!$user_wallet || $user_wallet->amount < $amount){
          return response()->json(['error'=>true, 'message'=>'Insufficient fund in your wallet!']);
      }

      $count_referal = User::select('id')->where('sponsored_by_id', $user_id)->get();

      if(count($count_referal) < 2){
        return response()->json(['error'=>true, 'message'=>'Atleast two direct is required to withdraw money!']);
      }

      $sender_current_level = $request->user()->current_level;

      $received_detail = UplineTransferDetail::where(['to_user_id'=>$user_id, 'plan_level_id'=>$sender_current_level])->get();

      $received_detail_count = count($received_detail);

      $received_amnt = $received_detail->sum('amount');

      $sent_count = UplineTransferDetail::where(['from_user_id'=>$user_id, 'plan_level_id'=>$sender_current_level+1])->count();

      if($received_detail_count == 3 && $sent_count < 1){
          return response()->json(['error'=>true, 'message'=>'You need to send money to your at least one upline in next level!']);
      }

      if($received_detail_count > 3 && $sent_count < 2){
          return response()->json(['error'=>true, 'message'=>'You need to send money to your upline in next level!']);
      }


   		if(Cache::has('transfer_'.$request->user()->id)){
   			return response()->json(['error'=>true, 'message'=>'Invalid attempt, Please try after some time!']);
   		}

   		Cache::put('transfer_'.$request->user()->id, 'true', now()->addSeconds(90));

      $url = 'https://www.pay2all.in/api/dmr/v2/balance';
      $check_balance = pay2AllApi::callApi($method='POST', $url, [], $header=true);

      $pay2all_balance = (object)$check_balance;

      if($pay2all_balance->balance < $amount){
        return response()->json(['error'=>true, 'message'=>'Service is under maintance. Kindly try after some time.']);
      }

   		$transaction_id = $this->saveTransaction($user_id, $type='dr', $amount=$amount);

      $user_wallet->amount -= $amount;
      $user_wallet->save();

      $transfer_amount = $amount - $amount*2/100;

      $fee = $amount*2/100;

   		$detail = new MoneyTransferDetail();
   		$detail->transaction_id = $transaction_id;
   		$detail->user_id = $user_id;
   		$detail->sender_id = $request->sender_id;
   		$detail->beneficiary_id = $request->beneficiary_id;
   		$detail->channel = $request->channel;
   		$detail->account_number = $request->account;
   		$detail->amount = (int)$transfer_amount;
      $detail->fee = (int)$fee;
   		$detail->status = 0;
      $detail->device = 2;  #Mobile App
   		$detail->save();

        $url = 'https://www.pay2all.in/api/dmr/v2/transfer';
        $data = [
        	'mobile_number'=>$request->user()->mobile,
        	'beneficiaryid'=>$request->beneficiary_id,
        	'senderid'=>$request->sender_id,
        	'account'=>$request->account,
        	'amount'=>(int)$transfer_amount,
        	'channel'=>$request->channel,
        	'client_id'=>$transaction_id,
        	'vendor_id'=>config('PAY2ALL_VENDOR_ID')
        ];

        $response = pay2AllApi::callApi($method='POST', $url, $data, $header=true);

        $response = (object)$response;

        \Log::channel('dmt_log')->info(['App_User_Request'=>$request->all(), 'App_Dmt_Response'=>$response]);

        $detail->message = $response->message??null;
        $detail->save();

        if(isset($response->status)){
            $detail->payid = $response->payid?$response->payid:null;
            $detail->orderid = $response->orderid?$response->orderid:null;
            $detail->txnid = $response->txnid?$response->txnid:null;
            $detail->save();

            if($response->status == 0){
              $detail->status = 1;
              $detail->utr = $response->utr;
              $detail->save();

              event(new SmsEvent($request->user()->mobile, 'Rs. '.$transfer_amount.' has been successfully transfered in your bank account '.$request->account.' from T20cs.com. Thankyou for using our services!'));
              
              return response()->json(['error'=>false, 'message'=>'Money Transfered Successfully', 'data'=>$response]);
   
            }elseif($response->status == 2){

        		  $transaction_id = $this->saveTransaction($user_id, $type='cr', $amount=$amount);
              $user_wallet->amount += $amount;
              $user_wallet->save();

              $detail->refund_status = 1;
              $detail->save();

              return response()->json(['error'=>true, 'message'=>$response->message, 'data'=>$response]); 

        	}elseif($response->status == 3){
        		$detail->status = 3;
	           $detail->save();
	            return response()->json(['error'=>true, 'message'=>$response->message, 'data'=>$response]);	
        	}else{
        		return response()->json(['error'=>true, 'message'=>$response->message, 'data'=>$response]);	
        	}
        }else{
        	 $transaction_id = $this->saveTransaction($user_id, $type='cr', $amount=$amount);
            $user_wallet->amount += $amount;
            $user_wallet->save();

            $detail->status = 2;
            $detail->refund_status = 1;
            $detail->save();
			     return response()->json(['error'=>true, 'message'=>$response->message, 'data'=>$response]);           	
        }
   	}

   	public function saveTransaction($user_id, $type, $amount){

      $admin_charge = 0;
       $total = $amount;
       $remark = 'App DMT Failure Refund';

      if($type=='dr'){
        $admin_charge = $amount*2/100;
        $total = $amount - $admin_charge;

         $remark = 'Money Transfer through App';
      }

        $transaction = new Transaction();
        $transaction->type = $type;
        $transaction->transaction_type_id = 4;  #Money Transfer
        $transaction->pay_mode = 1;
        $transaction->from_user_id = $user_id;
        $transaction->to_user_id = $user_id;
        $transaction->amount = $total;
        $transaction->admin_charge = $admin_charge;
        $transaction->remark = $remark;
        $transaction->save();

        return $transaction->id;
    }


}


