<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Transaction;
use Carbon\Carbon;

class AllTransactionController extends Controller
{
    public function index(Request $request){

        if($request->ajax()){

            $datas = Transaction::orderBy('created_at','desc')->select('id', 'type',  'transaction_type_id', 'from_user_id', 'to_user_id', 'amount', 'tds_charge', 'admin_charge',  'remark', 'created_at')->with(['fromUserDetail'=>function($query){
                $query->select('id', 'first_name', 'refer_code');
                }, 'toUserDetail'=>function($query){
                $query->select('id', 'first_name', 'refer_code');
            }, 'transactionType'=>function($query){
                $query->select('id', 'name');
            }]);

            $paid_amount = Transaction::where('transaction_type_id', $request->transaction_type);
            $tds_charge = Transaction::where('transaction_type_id', $request->transaction_type);
            $admin_charge = Transaction::where('transaction_type_id', $request->transaction_type);

            if($request->transaction_type > 0){

                $datas->where('transaction_type_id', $request->transaction_type);

                $result['transaction_detail'] = [];

            }

            if($request->date_type == 0){

                $datas->whereDate('created_at', Carbon::today());

                if($request->transaction_type > 0){
                    $paid_amount = $paid_amount->whereDate('created_at', Carbon::today());
                    $tds_charge = $tds_charge->whereDate('created_at', Carbon::today());
                    $admin_charge = $admin_charge->whereDate('created_at', Carbon::today());
                }

            }else{

                $this->validate($request, [
                    'date_from'=>'required'
                ]);
                
                if($request->date_from && $request->date_to){
                    $from_date = $request->date_from.' 00:00:00';
                    $to_date = $request->date_to.' 23:59:59';
                    $datas->whereBetween('created_at', [$from_date, $to_date]);

                 if($request->transaction_type > 0){

                    $paid_amount = $paid_amount->whereBetween('created_at', [$from_date, $to_date]);
                    $tds_charge = $tds_charge->whereBetween('created_at', [$from_date, $to_date]);
                    $admin_charge = $admin_charge->whereBetween('created_at', [$from_date, $to_date]);
                }

                }elseif($request->date_from){
                    $datas->whereDate('created_at', $request->date_from);

                    if($request->transaction_type > 0){

                        $paid_amount = $paid_amount->whereDate('created_at', $request->date_from);
                        $tds_charge = $tds_charge->whereDate('created_at', $request->date_from);
                        $admin_charge = $admin_charge->whereDate('created_at', $request->date_from);
                    }
                }                
            } 

            if($request->transaction_type > 0){

                $result['transaction_detail']['amount'] = $paid_amount->sum('amount');
                $result['transaction_detail']['tds_charge'] = $tds_charge->sum('tds_charge');
                $result['transaction_detail']['admin_charge'] = $admin_charge->sum('admin_charge');
                $result['transaction_detail']['total'] = $result['transaction_detail']['amount']+$result['transaction_detail']['tds_charge']+$result['transaction_detail']['admin_charge'];  
            }

            $search = $request->search['value'];

            if ($search) {

                $datas->whereHas('fromUserDetail', function($query) use($search){

                    $query->where('name', 'like', '%'.$search.'%')

                    ->orWhere('refer_code', 'like', '%'.$search.'%');

                })->orWhereHas('toUserDetail', function($query) use ($search){

                    $query->where('name', 'like', '%'.$search.'%')

                    ->orWhere('refer_code', 'like', '%'.$search.'%');                    
                });

            }

            # set datatable parameter 

            $totaldata = $datas->count();

            $result["length"] = $request->length;

            $result["recordsTotal"] = $totaldata;

            $result["recordsFiltered"] = $datas->count();

            $records = $datas->limit($request->length)->offset($request->start)->get();

            // return $$records;

            # fetch table records 

            $result['data'] = [];

            foreach($records as $data){

                $result['data'][] = ['txn_id'=>++$request->start, 'from_user'=>@$data->from_user_id > 0 ? @$data->fromUserDetail->refer_code.' - '.@$data->fromUserDetail->first_name: 'Admin', 'to_user'=>$data->to_user_id > 0 ? @$data->toUserDetail->refer_code.' - '.@$data->toUserDetail->first_name : 'Admin', 'date'=>$data->created_at->toDateTimeString(), 'amount'=>round($data->amount, 2), 'tds_charge'=>$data->tds_charge, 'admin_charge'=>$data->admin_charge, 'total'=>$data->amount+$data->tds_charge+$data->admin_charge, 'transaction_type'=>$data->transactionType->name, 'type'=>$data->type, 'remark'=>@$data->remark, 'edit'=>'Edit'];  
            }

            return $result;

        }

        return view('admin.all-transaction.list');

    }
}
