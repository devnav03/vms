<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\MoneyTransferDetail;
use App\Model\ServiceTransactionDetail;
use App\Http\Resources\DmtStatementResource;
use App\Http\Resources\ServiceStatementResource;
use App\Model\Transaction;
use App\Model\PvMatchingIncome;

class StatementController extends Controller
{

	public function moneyTransferStatement(Request $request){

		$user_id = $request->user()->id;

		$datas = MoneyTransferDetail::orderBy('created_at', 'asc')->select('id', 'transaction_id', 'amount', 'channel', 'account_number', 'utr', 'status', 'message', 'created_at')->with(['transactionDetail'=>function($query){
            	$query->select('id', 'amount', 'tds_charge', 'admin_charge');
       }])->where('user_id', $user_id)->get();

		$data = DmtStatementResource::collection($datas);

		return response()->json(['error'=>false, 'message'=>'Money Transfer History', 'data'=>$data]);

	}

	public function serviceStatement(Request $request){

		$user_id = $request->user()->id;

		$datas = ServiceTransactionDetail::orderBy('created_at', 'asc')->select('id', 'transaction_id', 'service_id', 'customer_number', 'provider_id', 'status', 'txstatus_desc', 'created_at')->with(['serviceDetail'=>function($query){
                $query->select('service_id', 'service_name');
            }, 'transactionDetail'=>function($query){
                $query->select('id', 'amount');
            }])->where('user_id', $user_id)->get();

		$data = ServiceStatementResource::collection($datas);

		return response()->json(['error'=>false, 'message'=>'Services History', 'data'=>$data]);
	}

	public function repurchaseIncome(Request $request){

		$user_id = $request->user()->id;

		$repurchase_incomes = Transaction::orderBy('id','asc')->select('id', 'type', 'transaction_type_id', 'amount', 'created_at')->with(['repurchaseIncomeDetail'=>function($query){
        $query->select('transaction_id', 'matching_bv', 'from_date', 'to_date', 'pair_rate', 'status', 'team_a_total_bv', 'team_b_total_bv', 'team_a_current_bv', 'team_b_current_bv', 'self_bv');
      }])->where(['transaction_type_id'=>11, 'to_user_id'=>$user_id])->get();

		return response()->json(['error'=>false, 'message'=>'Repurchase Income', 'data'=>$repurchase_incomes]);
	}	

	public function pvMatchingIncome(Request $request){

		$user_id = $request->user()->id;

		$pv_matching_income = PvMatchingIncome::select('id', 'total_team_a_pv', 'total_team_b_pv', 'matching_pv', 'matching_rate', 'from_date', 'to_date', 'paid_amount', 'tds_charge', 'admin_charge', 'created_at')->where('user_id', $user_id)->get();

		return response()->json(['error'=>false, 'message'=>'PV Matching Income', 'data'=>$pv_matching_income]);
	}
}
