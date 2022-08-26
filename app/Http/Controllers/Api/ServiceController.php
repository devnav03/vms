<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use App\Model\ServiceTransactionDetail;
use App\Http\Resources\ServicesResource;
use App\Model\ServiceProvider;
use App\Model\ServiceCircle;
use App\Model\Transaction;
use App\Helpers\pay2AllApi;
use App\Model\DmtWallet;
use App\Model\Service;
use App\User;
use Carbon\Carbon;
use Razorpay\Api\Api;
use App\Events\SmsEvent;


class ServiceController extends Controller
{

    public function getServices(){

        $data = Service::select('service_id', 'service_name', 'icon')->whereIn('id', [1,2,3,4,6,8,15])->get();

        $data = ServicesResource::collection($data);

        return response()->json(['error'=>false, 'message'=>'Available Services List', 'data'=>$data]);
    }

	public function getServiceProviders(Request $request){

		$this->validate($request, [
            'service_id'=>'required',
        ]);

        $data = ServiceProvider::select('service_id', 'provider_id', 'service_name', 'provider_name', 'provider_code', 'provider_image')->where('service_id', $request->service_id)->get();

		return response()->json(['error'=>false, 'message'=>'Service Providers List', 'data'=>$data]);
	}


	public function getCircle(Request $request){

        $data = ServiceCircle::select('circle_id', 'circle_name')->get();

		return response()->json(['error'=>false, 'message'=>'Circle List', 'data'=>$data]);
	}

    public function checKbillAmount(Request $request){
        $this->validate($request, [
            'provider_id'=>'required',
            'customer_number'=>'required|numeric',
        ]);

        $method = 'GET';
        $url = 'https://www.pay2all.in/web-api/check-bill';
        $data = pay2AllApi::apiToken();  #Pay2all token
        $data['number'] = $request->customer_number;
        $data['provider_id'] = $request->provider_id;

        $response = pay2AllApi::callApi($method, $url, $data);

        $response = (object)$response;

        // return response()->json(['error'=>false, 'message'=>'Bill Amount', 'data'=>$response]);

        if($response->status == 1){
            return response()->json(['error'=>false, 'message'=>'Bill Amount', 'data'=>$response]);
        }else{
            return response()->json(['error'=>true, 'message'=>$response->message, 'data'=>'']);
        }

    }

	public function payBill(Request $request){      
        
     //   return response()->json(['error'=>true, 'message'=>'This Service will be available in few moments.']);

		$this->validate($request, [
            'service_id'=>'required',
            'provider_id'=>'required|exists:service_providers,provider_id',
            'customer_number'=>'required|numeric',
            'amount'=>'required|numeric|min:10',
        ]);

        if($request->service_id == 1 || $request->service_id == 4){  #Mobile Recharge
            $this->validate($request, [
                'customer_number'=>'min:10',
            ]);
        }

        $amount = $request->amount;

        $user_id = $request->user()->id;

        $company_profit = 0;

        $cashback_amount = 0;

        $method = 'GET';
        $url = 'https://www.pay2all.in/web-api/get-commission';
        $data = pay2AllApi::apiToken();  #Pay2all token
        $response = pay2AllApi::callApi($method, $url, $data);

        $response = (object)($response);

        $response = collect($response->commission);

        if($response){

            $data = $response->where('provider_id', $request->provider_id)->first();

            if ($data) {
                
                $company_profit = $amount*$data->profit/100;  #in Rs.

                $cashback_amount = $company_profit;

                if($cashback_amount >= 2){

                    #give 50% of commission as cashback to user from 09-07-2019
                    $cashback_amount = $company_profit*50/100;
                }
            }
        }

        if(Cache::has('service_'.$request->provider_id.$amount.$request->customer_number)){
            return response()->json(['error'=>true, 'message'=>'Same amount same number. Kindly try after some time']);
        }

        Cache::put('service_'.$request->provider_id.$amount.$request->customer_number, 'true', now()->addSeconds(90));

        $user_wallet = DmtWallet::select('id', 'user_id', 'amount')->where('user_id', $user_id)->first();

        if(!$user_wallet || $user_wallet->amount < $amount){
            return response()->json(['error'=>true, 'message'=>'Insufficient fund in your wallet!']);
        }

        $url = 'https://www.pay2all.in/api/dmr/v2/balance';
        $check_balance = pay2AllApi::callApi($method='POST', $url, [], $header=true);

        $pay2all_balance = (object)$check_balance;

        if($pay2all_balance->balance < $amount){
            return response()->json(['error'=>true, 'message'=>'Service is under maintance. Kindly try after some time.']);
        }

        $transaction_id = $this->saveTransaction($type='dr', $amount=$amount, $user_id);     

        $user_wallet->amount -= $amount;
        $user_wallet->save();   

        $service = new ServiceTransactionDetail();
        $service->transaction_id = $transaction_id;
        $service->user_id = $user_id;
        $service->service_id = $request->service_id;
        $service->provider_id = $request->provider_id;
        $service->customer_number = $request->customer_number;
        $service->amount = $amount;
        $service->company_commission = $company_profit;
        $service->cashback_given = $cashback_amount;
        $service->status = 0; #initiated
        $service->device = 2; #Mobile App
        $service->save();

		$method = 'GET';
		$url = 'https://www.pay2all.in/web-api/paynow';
        $data = pay2AllApi::apiToken();  #Pay2all token
        $data['number'] = $request->customer_number;
        $data['provider_id'] = $request->provider_id;
        $data['amount'] = $amount;
        $data['client_id'] = $transaction_id; #order_id

        $response = pay2AllApi::callApi($method, $url, $data);

        $response = (object)$response;

        \Log::channel('recharge_utility_log')->info(['App_User_Request'=>$request->all(), 'App_Dmt_Response'=>$response]);
        $service->message = $response->message??null;
        $service->txstatus_desc = $response->txstatus_desc??null;

        if($response->status == 'success'){
            $service->status = 1;
            $service->payid = $response->payid;
            $service->save();
            event(new SmsEvent($request->user()->mobile, 'Recharge of '.$amount.' has been successfully done on '.$data['number'].' from T20cs.com. Thankyou!'));

            if($service->cashback_given > 0){

                $transaction_id = $this->saveTransaction($type='cr', $amount=$service->cashback_given, $user_id, $remark='Recharge Cashback');
                $user_wallet->amount += $service->cashback_given;
                $user_wallet->save();

                event(new SmsEvent($request->user()->mobile, 'Congratulations! Cashback Rs '.$service->cashback_given.' has been credited into your wallet for recharge on '.$data['number'].' from T20cs.com. Thankyou!'));

            }
        	return response()->json(['error'=>false, 'message'=>$response->message, 'data'=>$response]);

        }elseif($response->status == 'failure'){
            $service->status = 2;
            $service->payid = $response->payid;
            $service->txstatus_desc = 'failed';
            $service->company_commission = 0;
            $service->cashback_given = 0;
            $service->save();

            $transaction_id = $this->saveTransaction($type='cr', $amount=$amount, $user_id);
            $user_wallet->amount += $amount;
            $user_wallet->save();

            $service->refund_status = 1;
            $service->save();
        	return response()->json(['error'=>false, 'message'=>$response->message, 'data'=>$response]);

        }elseif($response->status == 'Pending' || $response->status == 'Initiated'){
            $service->status = 3;
            $service->payid = $response->payid;
            $service->save();
        	return response()->json(['error'=>false, 'message'=>$response->message, 'data'=>$response]);
            
        }else{

            $transaction_id = $this->saveTransaction($type='cr', $amount=$amount, $user_id);
            $user_wallet->amount += $amount;
            $user_wallet->save();

           $service->txstatus_desc = 'failed';
            $service->status = 2;
            $service->refund_status = 1;
            $service->company_commission = 0;
            $service->cashback_given = 0;
            $service->save();
            return response()->json(['error'=>true, 'message'=>'Something went wrong', 'data'=>'']);
        }
	}

    public function saveTransaction($type, $amount,$user_id, $remark=null){

        $type_id = 3;

        if($remark == 'Recharge Cashback'){

            $type_id = 12;

        }else{

            $remark = 'Recharge Transaction';

            if($type == 'cr'){
                $remark = 'Web Reharge Failure Refund';
            }
        }

        $transaction = new Transaction();
        $transaction->type = $type;
        $transaction->transaction_type_id = 3;  #Recharge & Services
        $transaction->pay_mode = 1;
        $transaction->from_user_id = $user_id;
        $transaction->to_user_id = $user_id;
        $transaction->amount = $amount;
        $transaction->remark = $remark;
        $transaction->save();

        return $transaction->id;
    }

}