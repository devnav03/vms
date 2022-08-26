<?php
namespace App\Http\Controllers\Web\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Model\UserAddress;
use App\Model\UserDetail;
use App\Model\UserPlan;
use App\Model\PlanDetail;
// use App\Model\ProductInventory;
// use App\Model\Order;
// use App\Model\UserCartData;
use App\Events\SmsEvent;
use Carbon\Carbon;
use App\User;
use App\Helpers\Common;
use DB;
use Mail;



class RegisterController_new extends Controller
{
    public function registerUser(Request $request){        
       
        $this->validate($request, [
            'first_name'=>'required|regex:/^[a-zA-Z]+$/u|max:100',
            'last_name'=>'required|regex:/^[a-zA-Z]+$/u|max:100',
            'email'=>'nullable|max:50|email',
            'phone'=>'required|numeric|digits:10',
            'password'=>'required|min:4|max:14',       
            'pin_code'=>'required|digits:6|max:6',
            'state'=>'required|max:100',       
            'city'=>'required|max:100',       
            'address'=>'required|max:150',
        ]);

            $user = $this->createUser($request);
            $user->refer_code = $this->generateReferCode($user->id);
            $user->group_id = 3;
            $user->save();
            event(new SmsEvent($user->mobile, 'Your Bitcoin registraion has been done successfully. You can login with your user Id: '.$user->refer_code.'. and password: '.$request->password.''));
            $user_detail = new UserDetail();
            $user_detail->user_id = $user->id;
            $user_detail->plain_password = encrypt($request->password);
            $user_detail->pin_code = $request->pin_code;
            $user_detail->address = $request->address;
            $user_detail->state_name = $request->state;
            $user_detail->city_name = $request->city;
            $user_detail->country = $request->country;
            $user_detail->save();

            $address = new UserAddress();
            $address->user_id = $user->id;
            $address->name = $request->first_name.' '.$request->last_name;
            $address->mobile = $request->phone;
            $address->address = $request->address??'Address';
            $address->state_name = $request->state;
            $address->city_name = $request->city;
            $address->pin_code = $request->pin_code;
            $address->country = $request->country;
            $address->save();

            // Auth::login($user);
            // return redirect()->route('user.dashboard');

              $data = ['refer_code'=>$user->refer_code];
            $mail =$request->email;
            Mail::send('mails.confirmation', $data, function($message) use ($mail){
                $message->subject('Confirm Your Bitcoin Account');
                $message->from('info@bitcoin.com','Confirm Your Bitcoin Account');
                $message->to($mail, 'Confirm Your Bitcoin Account');             
            });

            return view('web.email-confirmation', compact('user'));   
      
    }

    protected function createUser($request){
        
        $user = new User();
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->mobile = $request->phone;
        $user->password = bcrypt($request->password);
        $user->activation_status = 0;
        $user->save();
        return $user;
    }

    protected function generateReferCode($user_id){
        $refer_code = 'Bitcoin'.rand(100000, 999999); #implementation done on 3 feb 2020, Rohit said Rajesh team is saying that all the id is being generated with serial number and they know password of every user as they keep it same for everyone so the downline checks the upline detail by using his credentials as the password are same for everyone -- #foolish Statement

        if(User::select('refer_code')->where('refer_code', $refer_code)->exists()){
           return $this->generateReferCode($user_id);
        }else{
            return $refer_code;
        }
    }


   public function emailVarify(Request $request){
     $user = User::select('id','activation_status','first_name','last_name','email','mobile','password')->where('refer_code', base64_decode($request->refer_code))->first();
     $user->activation_status = 1;
     $user->save();

     Auth::login($user);

      return view('web.kyc');   

   } 


}
