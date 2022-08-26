<?php
namespace App\Http\Controllers\Web\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Model\UserAddress;
use App\Model\UserPinHistory;
use App\Model\UserDetail;
use App\Model\UserPlan;
use App\Model\Transaction;
use App\Model\UserPin;
use App\Model\PlanDetail;
use App\Model\ProductInventory;
use App\Model\Order;
use App\Model\UserCartData;
use App\Model\DmtWallet;
use App\Events\OtpEvent;
use App\Events\SmsEvent;
use App\Model\UserOtp;
use Carbon\Carbon;
use App\User;
use App\Helpers\Common;
use DB;


class RegisterController extends Controller
{
    public function searchForPlacement($refer_by_id, $position){
        $user = User::select('id')->where(['parent_id'=>$refer_by_id, 'team_group'=>$position])->first();
        if(!$user){
            return $refer_by_id;
        }
        $child_id = $user->id;
        while($child_id >= 1) {
            $user = User::select('id')->where(['parent_id'=>$child_id, 'team_group'=>$position])->first();
            if(!$user){
                return $child_id;
            }            
            $child_id = $user->id;
        }
    }
    public function acplRegistration($request){
        
        $this->validate($request, [
            // 'first_name'=>'required|regex:/^[a-zA-Z]+$/u|max:100',
            'first_name'=>'required|max:100|unique:users',
           // 'last_name'=>'required|max:100',
            // 'last_name'=>'required|regex:/^[a-zA-Z]+$/u|max:100',
            'email'=>'nullable|max:50|email|unique:users',
            'mobile'=>'required|numeric|digits:10|unique:users',
            // 'password'=>'required|min:4|max:14',
            'refer_by'=>'required',
            //'pin_code'=>'required',
            'team_group'=>'required',
            //'state'=>'required|max:100',
            //'city'=>'required|max:100',
           // 'address'=>'required|max:150',
           'pan_no'=>'required|max:150|unique:users',
            'inside_register'=>'required',
            'plan'=>'required',
        ]);   
        
        if($request->otp == null){
         
             event(new OtpEvent($request->mobile, 'Your OTP for registration at Kwedex is:'));
            return back()->with(['error'=>false, 'message'=>'An Otp has been sent on '.$request->mobile.'', 'otp_true'=>true])->withInput($request->all());
            
            //return $this->acplRegistration($request);
        }
        
        if($request->registration_type == 2){

            $group_id = 3;
            $sponsored_by_id = User::select('id')->where(['refer_code'=>$request->refer_by, 'group_id'=>$group_id, 'activation_status'=>1])->first();
            $company = 'Kwedex';
        }else{
             $this->validate($request, [
                'placement_to'=>'required',
            ]);  
            $group_id = 1;
            $company = 'T-20';
            if($request->plan > 1){
                return back()->withErrors(['plan'=>['Incorrect Plan']])->with(['class'=>'error', 'message'=>'you have choosen incorrect plan.'])->withInput($request->all());
            }
            $sponsored_by_id = User::select('id')->where(['refer_code'=>$request->refer_by, 'group_id'=>$group_id, 'activation_status'=>1])->first();
            $user_pin = UserPin::where(['user_id'=>$sponsored_by_id, 'plan_detail_id'=>$request->plan])->first();
            // if(!$user_pin || $user_pin->available_pins < 1){
            //     return back()->withErrors(['available_pins'=>['Insufficient Pins in your account']])->with(['class'=>'error', 'message'=>'Insufficient Fund in your account'])->withInput($request->all());
            // }
            $placement_to = User::select('id')->where(['refer_code'=>$sponsored_by_id, 'group_id'=>$group_id, 'activation_status'=>1])->first();
            if(!$placement_to){
                return back()->withErrors(['refer_by'=>'Only '.$company.' user can do registration in '.$company.''])->withInput($request->all());
            }   
        }

        $user_pin = UserPin::where(['user_id'=>$sponsored_by_id, 'plan_detail_id'=>$request->plan])->first();
        $refer_by = User::select('id')->where(['refer_code'=>$request->refer_by, 'group_id'=>$group_id, 'activation_status'=>1])->first();
        if(!$refer_by){
            return back()->withErrors(['refer_by'=>'Only '.$company.' user can do registration in '.$company.''])->withInput($request->all());
        }
        $plan_detail = PlanDetail::select('id', 'plan_type', 'amount', 'auto_placement', 'plan_name', 'direct_income')->where('id', $request->plan)->first();

        $sponsored_by_id = User::select('id')->where(['refer_code'=>$request->refer_by, 'group_id'=>$group_id, 'activation_status'=>1])->first();
        $password = substr(md5(rand()), 0,6);

        $request->request->add(['password'=>$password]);

        if($user = $this->createUser($request)){
            $user->refer_code = $this->generateReferCode($user->id);
            $user->sponsored_by_id = $sponsored_by_id->id;
            $user->refer_by_id = $refer_by->id;
            $user->group_id = $group_id;
            $user->team_group = $request->team_group;
            if($group_id == 1){ #if it is t20 id user gives placement
                $user->parent_id = $placement_to->id; #Only in T20
            }elseif($plan_detail->auto_placement == 0){ #when we don't ask for placement #ACPL old Plan
                $user->parent_id = $refer_by->id;  #Open Front for #ACPL old Plan
            }elseif($plan_detail->auto_placement == 1){ #place id according to team group
                $user->parent_id = $this->searchForPlacement($refer_by->id, $position=$request->team_group);  #Search For Placement
                #for bonus shopping
            }
            $user->activation_status = 0;
            $user->save();
            if($user_pin && $user_pin->available_pins >= 1){
                $transaction_id = $this->saveTransaction($type='cr', $amount=$plan_detail->amount*1, $transaction_type_id=24, $sponsored_by_id, $user->id, $status=1, $pay_mode=3, $remark='New Registration');
                $bp_history = new UserPinHistory();
                $bp_history->from_user_id = $sponsored_by_id;
                $bp_history->to_user_id = $user->id;
                $bp_history->transaction_id = $transaction_id;
                $bp_history->no_of_pins = 1;
                $bp_history->plan_detail_id = $plan_detail->id;
                $bp_history->mode = 2;
                $bp_history->remark = 'Registration';
                $bp_history->save();
                $user_pin->available_pins -= 1;
                $user_pin->save();
                $user->activation_status = 1;
                $user->activation_date = Carbon::now();
                $user->save();
                if($plan_detail->plan_type == 3){ #Bonus Related
                    $user_plan = new UserPlan();
                    $user_plan->user_id = $user->id;
                    // $user_plan->order_id = 111;
                    $user_plan->plan_detail_id = $plan_detail->id;
                    $user_plan->no_of_pins = 1;
                    $user_plan->oe = 1;
                    $user_plan->activation_date = Carbon::now();
                    $user_plan->activation_status = 1;
                    $user_plan->fresh_id = $plan_detail->id == 29 ? 0:1;
                    $user_plan->save();
                }
                if($plan_detail->direct_income > 0){ #Registration Direct Income 
                    $remark = 'Direct income on Id Activation '.$user->refer_code.'';
                    $amount = $plan_detail->direct_income;
                    $transaction_id = $this->saveTransaction($type='cr', $amount=$amount, $transaction_type_id=18, $user->id, $refer_by->id, $status=1, $pay_mode=1, $remark);
                }

                if($plan_detail->id != 29){
               
                    $invt_detail = ProductInventory::select('id', 'msp', 'product_id', 'mrp','bv', 'cgst', 'sgst', 'igst', 'rate', 'available_stock', 'activation_qty')->where('id', $request->inventory_id)->first();
                    $total = $invt_detail->mrp*$invt_detail->activation_qty;
                    $discount = ($invt_detail->mrp*$invt_detail->activation_qty - $invt_detail->msp*$invt_detail->activation_qty);
                    $tax = ($invt_detail->rate*$invt_detail->igst/100);
                    $total_bv = $invt_detail->bv*$invt_detail->activation_qty;
                    $sub_total = $total - $discount;
                    $transaction_type_id = 5; #Order Placement
                    $pay_mode=3; #By Pin
                    $pay_status = 1;
                    $transaction_id = $this->saveTransaction($type='dr', $amount=$sub_total, $transaction_type_id, $sponsored_by_id, $user->id, $status=1, $pay_mode, $remark='Id Activation');
                    $order = new Order();
                    $order->user_id = $user->id;
                    $order->order_type = 1; #firstPurchase
                    $order->device = 1; #web
                    $order->transaction_id = $transaction_id;
                    $order->order_status_id = 2;
                    $order->total = $total;
                    $order->sub_total = $sub_total;
                    $order->discount = $discount;
                    $order->tax = $tax;
                    $order->bv = $total_bv;
                    $order->delivery_option = 1;
                    $order->payment_status = $pay_status;
                    $order->payment_mode = $pay_mode;
                    $order->save();
                    $franchise_order = new FranchiseeOrder();
                    $franchise_order->order_id = $order->id;
                    $franchise_order->franchise_id = $request->franchisee;
                    $franchise_order->save();
                    $user_cart = new UserCartData();
                    $user_cart->order_id = $order->id;
                    $user_cart->product_id = $invt_detail->product_id;
                    $user_cart->inventory_id = $invt_detail->id;
                    $user_cart->qty = $invt_detail->activation_qty??1;
                    $user_cart->rate = $invt_detail->rate;
                    $user_cart->cgst = $invt_detail->cgst;
                    $user_cart->sgst = $invt_detail->sgst;
                    $user_cart->msp = $invt_detail->msp;
                    $user_cart->mrp = $invt_detail->mrp;
                    $user_cart->available_stock = $invt_detail->available_stock;
                    $user_cart->save();
                    $user->order_created = 1;
                }
                $user->save();
            }
            $user->plan_detail_id = $plan_detail->id;
            $user->plan_name = $plan_detail->plan_name;
            $user->save();
            event(new SmsEvent($user->mobile, 'Your registraion on '.$company.' has been done successfully. Now you can login with your User Id: '.$user->refer_code.'. and password: '.$request->password.' on www.kwedex.com'));
            $user_detail = new UserDetail();
            $user_detail->user_id = $user->id;
            $user_detail->plain_password = encrypt($request->password);
            //$user_detail->pin_code = $request->pin_code;
            //$user_detail->address = $request->address;
            //$user_detail->state_name = $request->state;
            //$user_detail->city_name = $request->city;
            $user_detail->save();
            $address = new UserAddress();
            $address->user_id = $user->id;
            $address->name = $request->first_name.' '.$request->last_name;
            $address->mobile = $request->mobile;
            //$address->address = $request->address??'Address';
            //$address->state_name = $request->state;
           // $address->city_name = $request->city;
            //$address->pin_code = $request->pin_code;
            $address->save();
            if($request->inside_register == 1){

       
             return redirect()->route('user.register')->with(['class'=>'success', 'message'=>'Registartion Successfull', 'user-refercode'=>$user->refer_code, 'activation_status'=>$user->activation_status == 0 ?'Not Activated':'Activated', 'placement_refer'=>@$user->parentDetail->refer_code.' - '.@$user->parentDetail->first_name, 'modal'=>true])->withInput(); 
            }
           
           Auth::login($user);
            return redirect()->route('user.dashboard')->with(['class'=>'success', 'message'=>'Registartion Successfull']);
        }
    }
    public function saveTransaction($type, $amount, $transaction_type_id, $from, $to, $status, $pay_mode, $remark){
        $admin = 0;
        $tds = 0;
        if($transaction_type_id == 18){
            $admin = $amount*10/100;
            $tds = $amount*5/100;
        }
        $amount = $amount - ($admin+$tds);
        $transaction = new Transaction();
        $transaction->type = $type;
        $transaction->transaction_type_id = $transaction_type_id;
        $transaction->pay_mode = $pay_mode;
        $transaction->from_user_id = $from;
        $transaction->to_user_id = $to;
        $transaction->tds_charge = $tds;
        $transaction->admin_charge = $admin;
        $transaction->amount = $amount;
        $transaction->status = $status;
        $transaction->remark = $remark;
        $transaction->save();
        if($transaction_type_id == 18){
            $wallet = DmtWallet::firstOrNew(['user_id'=>$to]);
            $wallet->amount += $amount;
            $wallet->save();
        }
        return $transaction->id;
    }
    public function registerUser(Request $request){
        // return back()->with(['class'=>'success',  'message'=>'Server is under maintanance. Please try after some time'])->withInput($request->all());
        
         //if($request->has('otp') && $request->otp != null){
            
            return $this->acplRegistration($request);
        //}
   
        //event(new OtpEvent($request->mobile, 'Your OTP for registration at Kwedex is:'));
        //return back()->with(['error'=>false, 'message'=>'An Otp has been sent on '.$request->mobile.'', 'otp_true'=>true])->withInput($request->all());
        
        if($request->registration_type == 2){
            return $this->acplRegistration($request);
        }
        $this->validate($request, [
            'first_name'=>'required|regex:/^[a-zA-Z]+$/u|max:100',
            'last_name'=>'required|regex:/^[a-zA-Z]+$/u|max:100',
            'email'=>'nullable|max:50|email',
            'mobile'=>'required|numeric|digits:10',
            'password'=>'required|min:4|max:14',
            'referal_code'=>'required|exists:users,refer_code',
            'pin_code'=>'required|digits:6|max:6',
            'state'=>'required|max:100',
            'placement_to'=>'required',
            'city'=>'required|max:100',
            'team_group'=>'required',
            'address'=>'required|max:150',
        ]);
        $referal_detail = User::select('id', 'activation_status')->where(['refer_code'=>$request->referal_code,'group_id'=>1, 'activation_status'=>1])->first();
        if(!$referal_detail){
            return back()->withErrors(['referal_code'=>'Your referal is not an active member!'])->withInput($request->all());
        }
        $check_pin = Common::checkPinCode($request->pin_code);
        // if($check_pin->Status == "Success"){
        //     return back()->withErrors(['pin_code'=>'Invalid Pincode'])->withInput();
        // }
        return $this->registerVerify($request); 
        if($request->has('otp') && $request->otp != null){
            return $this->registerVerify($request);
        }
        event(new OtpEvent($request->mobile, 'Your OTP for registration at T20CS is:'));
        return back()->with(['error'=>false, 'message'=>'An Otp has been sent on '.$request->mobile.'', 'otp_true'=>true])->withInput($request->all());
    }
    public function registerVerify($request){
        // $validator = Validator::make($request->all(), [
        //     'otp'=>[
        //         'required',
        //         'numeric',
        //         Rule::exists('user_otps')->where(function($query) use($request){
        //             $query->where('mobile', $request->mobile);
        //         }),
        //     ],
        // ]);
        // if($validator->fails()){
        //      return back()->with(['otp_true'=>true])->withErrors($validator)->withInput($request->all());
        // }
        // UserOtp::where(['mobile'=>$request->mobile, 'otp'=>$request->otp])->delete();
        if($request->referal_code){
            $parent_id = User::select('id')->where('refer_code', $request->referal_code)->value('id');
            $sponsored_by_id = $parent_id;
        }else{
            $parent_id = 1;
        }        
        // dd($request->all());
        // $parent_downlines = User::select('id')->where('parent_id', $parent_id)->get();
        // if(count($parent_downlines) >= 2){      
        //     $sponsored_by_id = $parent_id;
        //     $left_downline = $this->fetchParentId($parent_downlines[0]->id);
        //     if($left_downline['total_downline'] < 2){
        //         $parent_id = $left_downline['parent_id'];
        //     }else{
        //         $right_downline = $this->fetchParentId($parent_downlines[1]->id);
        //         if($left_downline['total_downline'] <= $right_downline['total_downline']){
        //             $parent_id = $left_downline['parent_id'];
        //         }else{
        //             $parent_id = $right_downline['parent_id'];
        //         }
        //     }
        // }
        $parent_id = null;
        if($user = $this->createUser($request, $parent_id)){
            $user->refer_code = $this->generateReferCode($user->id);
            $user->sponsored_by_id = $sponsored_by_id;
            $user->refer_by_id = $sponsored_by_id;
            $user->group_id = 1;
            $user->save();
             event(new SmsEvent($user->mobile, 'Your Bitcoin registraion has been done successfully. Now you can login with your User ID: '.$user->refer_code.'. and password: '.$request->password.''));
            $user_detail = new UserDetail();
            $user_detail->user_id = $user->id;
            $user_detail->plain_password = encrypt($request->password);
            $user_detail->pin_code = $request->pin_code;
            $user_detail->address = $request->address;
            $user_detail->state_name = $request->state;
            $user_detail->city_name = $request->city;
            $user_detail->save();
            $address = new UserAddress();
            $address->user_id = $user->id;
            $address->name = $request->first_name.' '.$request->last_name;
            $address->mobile = $request->mobile;
            $address->address = $request->address??'Address';
            $address->state_name = $request->state;
            $address->city_name = $request->city;
            $address->pin_code = $request->pin_code;
            $address->save();
            Auth::login($user);
            return redirect()->route('user.dashboard');
        }
    }
    public function fetchParentId($user_id){        
        $current_ids = [];
        $user_ids = '';
        $count_downline = 0;
        $downlines = User::where('parent_id', $user_id)->get();
        if(count($downlines) < 2){
            return ['parent_id'=>$user_id, 'total_downline'=>count($downlines)];
        }
        $current_ids = $downlines->pluck('id')->toArray();        
        if(count($current_ids)){
            $user_ids = (implode(',',$current_ids));            
            $count_downline += count($current_ids);
        }
        while(count($current_ids))
        {              
            foreach ($current_ids as $current_id) {
                $downlines = User::select('id')->where('parent_id', $current_id)->get();
                if(count($downlines) < 2){
                    return ['parent_id'=>$current_id, 'total_downline'=>$count_downline];
                }
            }     
            $data = DB::select('SELECT `id` FROM `users` WHERE `parent_id` in ('.$user_ids.')');
            $current_ids = array_pluck($data, 'id');
            if(count($current_ids)){
                $count_downline += count($current_ids);
                $user_ids = (implode(',',$current_ids));
            }
        }
        return ['parent_id'=>null, 'total_downline'=>$count_downline];
    }
    protected function createUser($request){
        // $parent_id = null;
        $user = new User();
		$user->status= 1;
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->mobile = $request->mobile;
       $user->pan_no = $request->pan_no;
        $user->password = bcrypt($request->password);
        $user->save();
        return $user;
    }

    protected function generateReferCode($user_id){
        $refer_code = 'KDX'.rand(100000, 999999); #implementation done on 3 feb 2020, Rohit said Rajesh team is saying that all the id is being generated with serial number and they know password of every user as they keep it same for everyone so the downline checks the upline detail by using his credentials as the password are same for everyone -- #foolish Statement

        // if($user_id < 10){
        //     $refer_code = 'ACPL'.rand(1000,9999).$user_id;
        // }elseif($user_id > 9 && $user_id < 100) {
        //     $refer_code = 'ACPL'.rand(100,999).$user_id;
        // }elseif($user_id > 99 && $user_id < 1000){
        //     $refer_code = 'ACPL'.rand(10,99).$user_id;
        // }elseif ($user_id > 999 && $user_id < 10000) {
        //     $refer_code = 'ACPL'.rand(1,9).$user_id;
        // }elseif($user_id > 9999){
        //     $refer_code = 'ACPL'.$user_id;
        // }
        if(User::select('refer_code')->where('refer_code', $refer_code)->exists()){
           return $this->generateReferCode($user_id);
        }else{
            return $refer_code;
        }
    }


    ##29 feb 2020 enter user pool

    public function createUserPool($user){

        $user = (object)$user;
        // dd($user);

        $search_parent = UserPool::select('id', 'user_id', 'no_of_person', 'level_from_first', 'created_at')->where('pool_club_id', 1)->where('no_of_person', 0)->first(); #club 1

        $fetch_parent = UserPool::orderBy('no_of_person', 'asc')->orderBy('created_at', 'asc')->select('id', 'user_id', 'no_of_person', 'level_from_first', 'created_at')->where('pool_club_id', 1)->where('level_from_first', $search_parent->level_from_first-1)->first(); #club 1

        if($fetch_parent->no_of_person >= 4){
            $fetch_parent = $search_parent;
        }

        $parent_id = $fetch_parent->user_id;

        $no_of_child = UserPool::where('parent_id', $parent_id)->count();      


        if($no_of_child < 4){
            $fetch_parent->no_of_person += 1;
            $fetch_parent->save();

            $user_pool = new UserPool();
            $user_pool->user_id = $user->id;
            $user_pool->parent_id = $parent_id;
            $user_pool->pool_club_id = 1;
            $user_pool->level_from_first += $fetch_parent->level_from_first+1;
            $user_pool->no_of_person = 0;
            $user_pool->save();
        }else{

            return $this->createUserPool($user);
        }

        $this->savePoolTransaction($user, $parent_id);   

        #check pending incomes of id who has refered this new user

        $refer_by_pending_incomes = PoolIncomeDetail::select('id', 'transaction_id', 'pay_status', 'pay_date')->with(['transactionDetail'=>function($query){
            $query->select('id', 'amount', 'status', 'remark');
        }])->where(['pay_status'=>0, 'pay_date'=>null, 'to_user_id'=>$user->refer_by_id])->get();

        if(count($refer_by_pending_incomes)){ #if pending incomes then count his directs

            $refer_by_child_count = User::select('id')->where('refer_by_id', $user->refer_by_id)->count();

            if($refer_by_child_count >= 4){
                foreach ($refer_by_pending_incomes as $key => $pending_income) {                 

                    $pending_income->pay_status = 0;
                    $pending_income->pay_date = Carbon::now();
                    $pending_income->save();
                    $pending_income->transactionDetail->status = 0;
                    $pending_income->transactionDetail->save();
                }
            }
        }

        return 'Income Distributed';
    }




    public function savePoolTransaction($from_user, $from_parent){

        $count_level = 1;
        
        $upline_detail = UserPool::select('id', 'user_id', 'parent_id')->where('user_id', $from_parent)->first();

        while($upline_detail && $upline_detail->id > 0) {

            $count_downline = User::select('id')->where('refer_by_id', $upline_detail->user_id)->count();

            
            $pay_status = 0;
            

            $remark = 'Club 1, Level '.$count_level.' Auto Pool Income from '.$from_user->refer_code.'';
            $admin = 0;
            $tds = 0;    
            $amount = 40;

            $transaction = new Transaction();
            $transaction->type = "cr" ;
            $transaction->transaction_type_id = 28;
            $transaction->user_type = 1;
            $transaction->pay_mode = 1;
            $transaction->from_user_id = $from_user->id;
            $transaction->to_user_id = $upline_detail->user_id;
            $transaction->tds_charge = $tds;
            $transaction->admin_charge = $admin;
            $transaction->amount = $amount;
            $transaction->status = $pay_status;
            $transaction->remark = $remark;
            $transaction->save();

            $pool_income = new PoolIncomeDetail();
            $pool_income->transaction_id = $transaction->id ;
            $pool_income->from_user_id = $from_user->id;
            $pool_income->to_user_id = $upline_detail->user_id;
            $pool_income->pool_club_id = 1;
            $pool_income->club_level_id = $count_level;
            $pool_income->total_income = $amount;
            $pool_income->pay_status = $pay_status;
            if($pay_status == 1){
                $pool_income->pay_date = Carbon::now();
            }
            $pool_income->remark = $remark;
            $pool_income->save();

            

            $count_level += 1;

            $upline_detail = UserPool::select('id', 'user_id', 'parent_id')->where('user_id', $upline_detail->parent_id)->first();

            if($count_level >= 8){
                $upline_detail = 0;                
                break;
            }          
        }
        return 'Transaction Updated!';
    }
    
    
    public function registerRefer(Request $request){
        
     $user = User::select('id','refer_code')->where('refer_code', base64_decode($request->refer_code))->first();    

      return view('web.auth.registration',compact('user'));   

   } 
   
   
    public function resendOTP(Request $request){
        
      event(new OtpEvent($request->mobile, 'Your OTP for registration at Kwedex is:'));

      $response = "done";
            return $response;     

   } 
}
