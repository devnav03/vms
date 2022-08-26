<?php



namespace App\Http\Controllers\Web\Auth;



use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use Illuminate\Validation\Rule;

use App\Events\OtpEvent;

use App\User;

use App\Model\UserOtp;

use Auth;



class ResetPasswordController extends Controller

{

	public function forgotPassword(Request $request){

		$this->validate($request, [

			'old_password'=>'required'

		]);



		$user_detail = User::select('id', 'password', 'mobile', 'status')->with(['userDetail'=>function($query){
			$query->select('id', 'user_id', 'plain_password');
		}])->where('id', $request->user()->id)->first();

		if(decrypt($user_detail->userDetail->plain_password) != $request->old_password){

        	return back()->withErrors(['old_password'=>'Entered password is incorrect']);

       	}


		if($user_detail){

			if($user_detail->status == 0){

				Auth::logout($user_detail);

				return back()->withErrors(['user_name'=>'Your account is inactive']);
			}


			if($request->has('otp') && $request->otp != null){

				if(decrypt($user_detail->userDetail->plain_password) == $request->new_password){

					return back()->withErrors(['new_password'=>'New password can not be same as old password'])->with(['otp_true'=>true])->withInput();

		       	}

				return $this->changePassword($request, $user_detail);

			}

			if($user_detail->mobile == '9996922730' || $user_detail->mobile == '9996922731'){
				return back()->withErrors(['old_password'=>'Incorrect Mobile! Kindly ask admin to change your mobile number']);
			}

			event(new OtpEvent($user_detail->mobile, 'Your OTP for forgot password in Kwedex is: '));

			return back()->with(['class'=>'success', 'message'=>'Otp sent to your registered mobile number', 'otp_true'=>true])->withInput();

		}else{

			return back()->withErrors(['user_name'=>'Invalid Username or Password'])->withInput();

		}

	}



	public function changePassword($request, $user_detail){			



		$validator = \Validator::make($request->all(), [

			'new_password'=>'required',

            'otp'=>[

                'required',

                'numeric',

                Rule::exists('user_otps')->where(function($query) use($user_detail){

                    $query->where('mobile', $user_detail['mobile']);

                }),

            ],

        ]);        


        if($validator->fails()){

             return back()->withErrors($validator)->withInput($request->all())->with(['otp_true'=>true]);

        }

		$user_detail->password = bcrypt($request->new_password);

		$user_detail->userDetail->plain_password = encrypt($request->new_password);

		if($user_detail->save()){

			$user_detail->userDetail->save();		

			UserOtp::where(['mobile'=>$user_detail->mobile, 'otp'=>$request->otp])->delete();

			return back()->with(['class'=>'success', 'message'=>'Your password has been changed successfully, Thankyou!'])->withInput();

		}

		return response()->json(['class'=>'error', 'message'=>'Oops! something went wrong!']);

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

