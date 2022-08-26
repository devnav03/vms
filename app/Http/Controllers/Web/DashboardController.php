<?php



namespace App\Http\Controllers\Web;


use DateTime;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\User;

use App\Model\Product;

use App\Model\UserWallet;

use App\Model\HoldWallet;

use App\Model\UserPool;

use App\Model\DmtWallet;

use App\Model\UserCart;
use App\Model\PlanDetail;

use App\Model\Transaction;

use Carbon\Carbon;

use DB;

use Illuminate\Support\Facades\Auth;


use Mail;



class DashboardController extends Controller

{

    public function dashboardData(Request $request){

    	$user_id = $request->user()->id;
    	
		$userbuz =  DashboardController::allBuzz($user_id);

        $total_income_summary =  DashboardController::userIncomeSummary($user_id);

    	$user_detail = User::select('id', 'group_id', 'first_name', 'refer_code', 'last_name', 'email', 'mobile', 'current_level','plan_name', 'plan_type', 'email_varify','refer_by_id', 'parent_id', 'activation_status', 'activation_date', 'plan_detail_id', 'created_at','direct_bonus_3','direct_bonus_date_3','direct_bonus_4','direct_bonus_date_4','direct_bonus_5','direct_bonus_date_5','direct_bonus_6','direct_bonus_date_6')->where('id', $user_id)->first();	
		
		
		$directChildData_3 = User::select('id','refer_by_id','plan_detail_id','activation_status','activation_date')->where([['refer_by_id', '=', $user_id],['activation_status', '=', 1],['plan_detail_id', '=', 3]])->count();	
		$directChildData_4 = User::select('id','refer_by_id','plan_detail_id','activation_status','activation_date')->where([['refer_by_id', '=', $user_id],['activation_status', '=', 1],['plan_detail_id', '=', 4]])->count();	
		$directChildData_5 = User::select('id','refer_by_id','plan_detail_id','activation_status','activation_date')->where([['refer_by_id', '=', $user_id],['activation_status', '=', 1],['plan_detail_id', '=', 5]])->count();	
		$directChildData_6 = User::select('id','refer_by_id','plan_detail_id','activation_status','activation_date')->where([['refer_by_id', '=', $user_id],['activation_status', '=', 1],['plan_detail_id', '=', 6]])->count();			

        $wallet_amount = UserWallet::select('amount')->where('user_id', $user_id)->value('amount')+DmtWallet::select('amount')->where('user_id', $user_id)->value('amount');       

        $hold_amount = HoldWallet::select('amount')->where(['user_id'=>$user_id,'transfer_status'=>'Pending'])->sum('amount');  
        
		$plan_detail = PlanDetail::select('id','amount')->where('id',$user_detail->plan_detail_id)->first();
		if($user_detail->activation_status == 1){
			if($user_detail->plan_type == 1){
				$plan_detail->amount = $plan_detail->amount*2;
			}
		}else{
			$plan_detail->amount = 0;
		}
		
        $plans = PlanDetail::select('id', 'plan_name')->where('status', 1)->whereNotIn('id', [2,1])->pluck('plan_name','id');
		//dd($plans);
		$from_date = $user_detail->activation_date;
		$to_date = Carbon::now();
		//dd($to_date);
		$datetime1 = new DateTime($from_date);
		$datetime2 = new DateTime($to_date);
		$interval = $datetime1->diff($datetime2);
		$days = $interval->format('%a');
		//dd($from_date,$days);
		
        return view('web.user.dashboard', compact('user_detail', 'wallet_amount', 'plan_detail', 'hold_amount', 'userbuz','directChildData_3','directChildData_4','directChildData_5','directChildData_6','days','plans','total_income_summary'));   
        
        
    }
        
    public function allBuzz($user_id){
    	$current_ids = [];
    	$user_ids = '';
    	$data = DB::select('SELECT `user_id`  FROM `user_pools` WHERE  `parent_id` = '.$user_id);
    
    	$total_user_income=0;
    	$total_order_bv=0;     
    	
    	$plans = PlanDetail::select('id', 'amount')->where('status', 1)->pluck('amount','id');
    
    	if(count($data)){
    		$current_ids = array_pluck($data, 'user_id');
    		if(count($current_ids)){
    			$user_ids = (implode(',',$current_ids));
    		}
    		while(count($current_ids)){
    			foreach ($current_ids as $user) {
    				//dd($user);
    				$total_plan_id = UserPool::select('user_id', 'plan_detail_id')->where('user_id', $user)->first();
    				//dd($user,$total_plan_id);	
    				$total_plan_amount = 	$plans[$total_plan_id->plan_detail_id];
    				$total_user_income += $total_plan_amount;
    			
    			}
    
    			$data = DB::select('SELECT `user_id` FROM `user_pools` WHERE `parent_id` in ('.$user_ids.')');
    
    			$current_ids = array_pluck($data, 'user_id');
    			if(count($current_ids)){
    				$user_ids = (implode(',',$current_ids));
    			}
    		}
    	}
    	return $total_user_income;
    }


    public function emailVarifySend(Request $request){
        $user_id = $request->user()->id;
        $user_detail = User::select('id','first_name', 'refer_code','email')->where('id', $user_id)->first();

        $data = ['refer_code'=>$user_detail->refer_code];
        $mail =$user_detail->email;
        Mail::send('mails.confirmation', $data, function($message) use ($mail){
          $message->subject('Confirm Your kwedex Account');
          $message->from('info@kwedex.com','Confirm Your kwedex Account');
          $message->to($mail, 'Confirm Your kwedex Account');             
        });

        return view('web.user.confirm-email-send', compact('user_detail'));  
    }

  public function emailVarify(Request $request){
     $user = User::select('id','activation_status','first_name','last_name','email','mobile','password')->where('refer_code', base64_decode($request->refer_code))->first();
     $user->email_varify = 1;
     $user->save();

     Auth::login($user);

      return view('web.user.confirm-email');   

   } 


   public function userIncomeSummary($userid){
        $incomes  = Transaction::select('id','transaction_type_id','amount','tds_charge','admin_charge','from_user_id','to_user_id')->whereIn('transaction_type_id', [7,26,29,30,31,32,33])->where('to_user_id',$userid)->get();
        $total_income =0;
        foreach ($incomes as $key => $income) {
           $total_income += $income->amount;
           $total_income += $income->tds_charge;
           $total_income += $income->admin_charge;
        }

        return $total_income;
   }
    
    
    public function upgradeData(Request $request){

    	$user_id = $request->user()->id;
		$plan_detail = PlanDetail::select('id', 'plan_name')->where('id', $request->plan)->first();
		
    	$user_detail = User::select('id', 'plan_detail_id','plan_type','plan_name')->where('id', $user_id)->first();
		$user_detail->plan_detail_id = $plan_detail->id;
		$user_detail->plan_type=  $request->plan_type;
		$user_detail->plan_name=  $plan_detail->plan_name;
		$user_detail->save();
         return redirect()->route('user.add-money-request.get')->with(['class'=>'success', 'message'=>'Your Accont Upgrade Request Submitted Successfully.', 'modal'=>true]); 
		 	
    }


    public function trading(Request $request){

      $user_id = $request->user()->id;

      $user_detail = User::select('id', 'group_id', 'first_name', 'refer_code', 'last_name', 'email', 'mobile', 'current_level','refer_by_id', 'parent_id', 'activation_status', 'activation_date', 'plan_detail_id', 'created_at')->where('id', $user_id)->first();


        $wallet_amount = UserWallet::select('amount')->where('user_id', $user_id)->value('amount')+DmtWallet::select('amount')->where('user_id', $user_id)->value('amount');       

        return view('web.user.trading', compact('user_detail', 'wallet_amount'));   
    }



	public function updateRepurchaseBusiness($user_id){

         $all_team_business=array();

      $team_business=array();

      $all_downline = User::select('id')->where('id', $user_id)->get();

         foreach ($all_downline as $key => $user) {

           $team_business=Controller::calculateTeamIncome($user->id);



         

            if (count($team_business)){

              $all_team_business[]=$team_business;

              $matching_count=0;



             if( $team_business['business']['team_a_bv'] >=0 && $team_business['business']['team_b_bv']>=0 ){



             if ($user['business']['team_a_bv'] <= $team_business['business']['team_b_bv']) {

                    $matching_count = $team_business['business']['team_a_bv'];

                } else {

                    $matching_count = $team_business['business']['team_b_bv'];

                }



      



             $user_repurchase_income = UserRepurchaseIncome::firstOrNew(['user_id'=>$user_id]);

           

            //if ($matching_count>=0 or ($team_business['business']['self_bv'] >= $user_repurchase_income->last_self_bv ))

			{

 



             $user_repurchase_income->team_a_bv         = $team_business['business']['team_a_bv'] ;

             $user_repurchase_income->team_b_bv         = $team_business['business']['team_b_bv'] ;

             $user_repurchase_income->self_bv           = $team_business['business']['self_bv'] ;

             $user_repurchase_income->current_matching  = $matching_count;

             $user_repurchase_income->status            = 0 ;

             $user_repurchase_income->save();

           } 

          }  

        } // if

     } //foreach

    }

}

