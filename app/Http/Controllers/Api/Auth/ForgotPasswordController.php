<?php

namespace App\Http\Controllers\Api\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use App\Events\OtpEvent;
use App\User;
use App\Model\UserOtp;

class ForgotPasswordController extends Controller
{

	public function forgotPassword(Request $request){

		$this->validate($request, [
			'mobile'=>'required'
		]);

		$user_detail = User::select('id', 'password', 'mobile', 'status')->where($this->userName($request->mobile), $request->mobile)->first();

		if($user_detail){

			if($user_detail->status == 0){
				return response()->json(['error'=>true,'message'=>"Your account is inactive"]);
			}
			
			if($request->has('otp')){
				return $this->changePassword($request, $user_detail);
			}
			event(new OtpEvent($user_detail->mobile, 'Your OTP for forgot password in T20CS is: '));
			return response()->json(['error'=>false, 'message'=>'Otp sent to your registered mobile number', 'otp'=>true]);
		}else{
			return response()->json(['error'=>true, 'message'=>'Mobile or Refer code does not match our record!']);
		}

	}

	public function changePassword($request, $user_detail){
		$this->validate($request, [
			'new_password'=>'required',
			'otp'=>[
				'required',
				'numeric',
				Rule::exists('user_otps')->where(function($query) use($user_detail){
					$query->where('mobile', $user_detail->mobile);
				})
			],
		]);	

		UserOtp::where(['mobile'=>$user_detail->mobile, 'otp'=>$request->otp])->delete();

		$user_detail->password = bcrypt($request->new_password);
		$user_detail->userDetail->plain_password = encrypt($request->new_password);
		if($user_detail->save()){
			return response()->json(['error'=>false, 'message'=>'Your password has been changed successfully, Thankyou!']);
		}
		return response()->json(['error'=>true, 'message'=>'Oops! something went wrong!']);
	}

	public function resendOtp(Request $request){
		$this->validate($request, [
			'mobile'=>'required',
			'type'=>'required',
		]);

		if($request->type == 'registration'){

			$otp_detail = UserOtp::select('mobile', 'resend_count')->where('mobile', $user_detail->mobile)->first();

			if(!$otp_detail){
				return response()->json(['error'=>true, 'message'=>'Your request is not valid']);
			}elseif($otp_detail->resend_count > 5){
				return response()->json(['error'=>true, 'message'=>'Your request is not valid']);
			}

			event(new OtpEvent($request->mobile, 'Your resend OTP request for '.$request->type.' in T20CS is: '));
			return response()->json(['error'=>false, 'message'=>'Otp sent to your registered mobile number', 'otp'=>true]);
		}

		$user_detail = User::select('id', 'password', 'plain_password', 'mobile')->where($this->userName($request->mobile), $request->mobile)->first();

		if(!$user_detail){
			return response()->json(['error'=>true, 'message'=>'Mobile or Refer code does not match our record!']);
		}

		if($user_detail->status == 0){
			return response()->json(['error'=>true,'message'=>"Your account is inactive"]);
		}

		$otp_detail = UserOtp::select('mobile', 'resend_count')->where('mobile', $user_detail->mobile)->first();

		if(!$otp_detail){
			return response()->json(['error'=>true, 'message'=>'Your request is not valid']);
		}elseif($otp_detail->resend_count > 3){
			return response()->json(['error'=>true, 'message'=>'Your request is not valid']);
		}

		event(new OtpEvent($user_detail->mobile, 'Your resend OTP request for '.$request->type.' in T20CS is: '));
		return response()->json(['error'=>false, 'message'=>'Otp sent to your registered mobile number', 'otp'=>true]);
	}


	public function userName($mobile)
	{
		if(preg_match('/^[0-9]{10}+$/', $mobile)){
			return 'mobile' ;
		}else{
			return 'refer_code';
		}
	}

}
