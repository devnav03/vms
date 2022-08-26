<?php

namespace App\Http\Controllers\Web;



use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Model\GrievanceAndQuery;

use App\User;

use App\Model\CompanyProfitClub;

use App\Model\Transaction;

use App\Model\DmtWallet;

use Mail;



class GrievanceQueryController extends Controller

{



     public function testingCompany(Request $request){

        $transactions = Transaction::where('transaction_type_id',23)->where('created_at','>','2020-04-01 00:00:00')->get();
        // dd($transactions);

        foreach ($transactions as $key => $transaction) {

            $users = CompanyProfitClub::where(['status'=>1, 'user_id'=>$transaction->to_user_id])->first();

             
            // $cut_amount = $transaction->amount + $transaction->tds + $transaction->admin_charge;

           //  $users->paid_amount += $cut_amount;

            // $users->save();

             $wallet = DmtWallet::firstOrNew(['user_id'=>$users->user_id]);
             $wallet->amount += $transaction->amount;
             $wallet->save();

              // $transaction->delete();

              
            }

        
            dd('done');
        return back()->with(['class'=>'success', 'message'=>'Income SuccessFully Distributed']);

    }

    public function postQuery(Request $request){



        $this->validate($request, [

            'fname'=>'required|max:100',

            'lname'=>'required|max:100',

            'phone'=>'required|numeric',

            'email'=>'nullable|max:50',

            'subject'=>'required|max:150',

            'message'=>'required|max:500',

        ]);

        $query = new GrievanceAndQuery();

        $query->type = 2;

        $query->name = $request->fname.' '.$request->lname;

        $query->email = $request->email;

        $query->number = $request->phone;

        $query->subject = $request->subject;

        $query->description = $request->message;

        $query->save();

// $mail = $request->email;
        // Mail::send(['text'=>'mail'],['name','suresh kumar'],function($message){
        //   $message->to($request->email,'Mask Export Enquiry')->subject('test email');
        //   $message->from('worldacpl@gmail.com','testing For mask export');
        // });

        $data = ['name'=>$request->fname.' '.$request->lname, 'email'=>$request->email, 'phone'=>$request->phone, 'mess'=>$request->message, 'sub'=>$request->subject];
        $mail =$request->email;
        $sub = $request->subject;
        Mail::send('mails.welcome', $data, function($message) use ($mail){
            $message->subject('Mask Export Enquiry');
            $message->from('worldacpl@gmail.com','Mask Export Enquiry');
            $message->to($mail, 'Mask Export Enquiry');
            $message->cc('suresh4ua@gmail.com', 'Mask Export Enquiry');              
        });


        return back()->with(['class'=>'success', 'message'=>'Query Submitted Successfully', 'modal'=>true])->withInput();
     

    }

}

