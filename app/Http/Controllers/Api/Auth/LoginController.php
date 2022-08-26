<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\User;
use App\Model\UserDetail;
use App\Model\DeviceToken;
use App\Helpers\pay2AllApi;
use DB;


class LoginController extends Controller
{
	public function userLogin(Request $request){
		$this->validate($request, [
			'user_name'=>'required',
			'password'=>'required'
		]);

		$user_type = substr($request->user_name, 0, 3);
		if(strcasecmp($user_type, 'KDL') == 'kdl'){
			return $this->kdlLogin($request);
		}

		$login_type = $this->loginType($request->user_name);

		if(Auth::attempt([$login_type=>$request->user_name, 'password'=>$request->password])){
			if(Auth::user()->status == 0){
				$this->userLogout($request);
				return response()->json(['error'=>true,'message'=>"Your account is inactive"]);
			}
			$user = auth()->user();
			if($request->firebase_token){
                // if($agent = (new Agent())->isAndroidOS() == 1){
                //     $type = 'android';
                // }elseif ($agent = (new Agent())->isIphone() ==1 ) {
                //     $type = 'iphone';
                // }else {
                //     $type = 'other';
                // }
                $dvcTok = DeviceToken::firstOrNew(['user_id'=>$user->id, 'firebase_token'=>$request->firebase_token]);
                $dvcTok->ip = $request->is();
                $dvcTok->status = 1;
                // $dvcTok->type = $request->header('device');
                $dvcTok->save();
            }
			DB::table('oauth_access_tokens')->where('user_id', '=', $user->id)->update(['revoked' => 1]);
			return response()->json(array('error'=>false,'message'=>'Login successful','data'=>$user->only(['first_name', 'last_name', 'mobile', 'email', 'refer_code']),'token'=>$user->createToken($request->header('device'))->accessToken));  
		}

		return response()->json(['error'=>true,'message'=>"Invalid username or password", "errors"=>["Invalid Refer/Mobile Or Password"]]);
	}

	public function kdlLogin($request){

		$url = 'https://www.kdluniverse.com/t20/api/user-authentication';
		$credential = [
			'user_name'=>trim($request->user_name),
			'password'=>trim($request->password),
		];

		$data = pay2AllApi::callApi('POST', $url, $credential, $header=true);

		$response = (object)$data;

		if(isset($response->errors) || (isset($response->error) && $response->error == true)){
			return response()->json(['error'=>true,'message'=>$response->message, "errors"=>[$response->message]]);
		}else{
			$api_data = (object)$response->data;
			$user_name = explode(' ', $api_data->name, 2);
			$first_name = $user_name[0];
			$last_name = 'KDL';
			if(count($user_name) > 1){
				$last_name = $user_name[1];
			}

			$user = User::firstOrNew(['refer_code'=>$api_data->refer_code]);
            $user->group_id = 2;
            $user->first_name = $first_name;
	        $user->last_name = $last_name;
	        $user->email = $api_data->email;
	        $user->mobile = $api_data->mobile;
	        $user->password = bcrypt($request->password);
	        $user->save();

	        if($user->status == 0){
				return response()->json(['error'=>true,'message'=>"Your account is inactive"]);
			}

			$profile_detail = (object) $api_data->profile_detail;

			$state_detail = (object) $profile_detail->state_detail;
			$city_detail = (object) $profile_detail->city_detail;

	        $user_detail = UserDetail::firstOrNew(['user_id'=>$user->id]);
            $user_detail->plain_password = encrypt($request->password);
            $user_detail->pin_code = $profile_detail->pin_code;
            $user_detail->address = $profile_detail->address;
            $user_detail->state_name =  $state_detail->name;
            $user_detail->city_name = $city_detail->name;
            $user_detail->save();

           return response()->json(array('error'=>false,'message'=>'Login successful','data'=>$user->only(['first_name', 'last_name', 'mobile', 'email', 'refer_code']),'token'=>$user->createToken($request->header('device'))->accessToken));  
		}

		return response()->json(['error'=>true,'message'=>"Something went wrong!"]);
	}

	public function loginType($mobile)
	{
		if(preg_match('/^[0-9]{10}+$/', $mobile)){
			return 'mobile' ;
		}else{
			return 'refer_code';
		}
	}

	public function userLogout(Request $request){

		DB::table('oauth_access_tokens')->where('user_id', '=', $request->user()->id)->update(['revoked' => 1]);

		return response()->json(['error'=>false,'message'=>"Logout Successfully"]);
	}

}