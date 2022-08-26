<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Events\OtpEvent;
use App\Events\SmsEvent;
use Carbon\Carbon;
use App\Model\UserOtp;
use App\User;
use App\Model\DeviceToken;
use App\Model\UserDetail;
use DB;

class RegisterController_Old extends Controller
{
    public function registerUser(Request $request){

        $this->validate($request, [
            'first_name'=>'required|regex:/^[a-zA-Z]+$/u|max:100',
            'last_name'=>'required|regex:/^[a-zA-Z]+$/u|max:100',
            'email'=>'nullable|max:50|email',
            'mobile_no'=>'required|numeric|digits:10|unique:users,mobile',
            'password'=>'required|min:4|max:14',
            'referal_code'=>'required|exists:users,refer_code',
            'pin_code'=>'required|digits:6|max:6',
            'state'=>'required|max:100',
            'city'=>'required|max:100',
            'address'=>'nullable|max:150',
        ]);

        if($request->has('otp')){
            return $this->registerVerify($request);
        }

        event(new OtpEvent($request->mobile_no, 'Your OTP for registration at T20CS is:'));

        return response()->json(['error'=>false, 'message'=>'Otp has been sent to your mobile number', 'otp'=>true]);
    }

    public function registerVerify($request){
        $this->validate($request, [
         'otp'=>[
             'required',
             'numeric',
             Rule::exists('user_otps')->where(function($query) use($request){
                 $query->where('mobile', $request->mobile_no);
             }),
         ],
        ]);

        UserOtp::where(['mobile'=>$request->mobile_no, 'otp'=>$request->otp])->delete();

        if($request->referal_code){
            $sponsored_by_id = User::select('id')->where('refer_code', $request->referal_code)->value('id');
        }else{
            $sponsored_by_id = 1;
        }

        if($user = $this->createUser($request)){
            $user->refer_code = $this->generateReferCode($user->id);
            $user->sponsored_by_id = $sponsored_by_id;
            $user->save();
             event(new SmsEvent($user->mobile, 'Your registraion on T20CS has been done successfully. Now you can login with your Refer code: '.$user->refer_code.'. and password: '.$request->password.''));

             if($request->firbase_token){
                $dvcTok = new DeviceToken();
                $dvcTok->user_id = $user->id;
                $dvcTok->firbase_token = $request->firbase_token;
                $dvcTok->ip = $request->is();
                $dvcTok->status = 1;
                $dvcTok->type = $request->header('device');
                $dvcTok->save();
            }

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
            $address->mobile = $request->mobile_no;
            $address->address = $request->address??'Address';
            $address->state_name = $request->state;
            $address->city_name = $request->city;
            $address->pin_code = $request->pin_code;
            $address->save();

             return response()->json(['error'=>false, 'message'=>'Registration Successfull', 'data'=>['token'=>$user->createToken($request->header('device'))->accessToken, 'refer_code'=>$user->refer_code, 'password'=>$request->password]]);
        }else{
             return response()->json(['error'=>true, 'message'=>'Oops! Something went wrong!']);
        }
    }

    protected function createUser($request){
        $user = new User();
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->mobile = $request->mobile_no;
        $user->password = bcrypt($request->password);
        $user->save();

        return $user;
    }

    protected function generateReferCode($user_id){
        if($user_id < 10){
            $refer_code = 'T20'.rand(1000,9999).$user_id;
        }elseif($user_id > 9 && $user_id < 100) {
            $refer_code = 'T20'.rand(100,999).$user_id;
        }elseif($user_id > 99 && $user_id < 1000){
            $refer_code = 'T20'.rand(10,99).$user_id;
        }elseif ($user_id > 999 && $user_id < 10000) {
            $refer_code = 'T20'.rand(1,9).$user_id;
        }elseif($user_id > 9999){
            $refer_code = 'T20'.$user_id;
        }

        if(User::select('refer_code')->where('refer_code', $refer_code)->exists()){

           return $this->generateReferCode($user_id);
           
        }else{

            return $refer_code;
        }
    }
}
