<?php

namespace App\Http\Controllers\Web\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Model\UserDetail;
use App\Helpers\pay2AllApi;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController_old extends Controller
{
	use AuthenticatesUsers;
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

				$this->logout($request);
				return back()->withErrors(["user_name"=>"Your account is Inactive"])->withInput();
			}

			return redirect()->route('user.dashboard');
		}

		return back()->withErrors(["user_name"=>"Invalid Refer / Mobile Or Password"])->withInput();
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
			return back()->withErrors(["user_name"=>$response->message])->withInput();
		}else{
			$api_data = $response->data;
			// dd($api_data->name);
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
				return back()->withErrors(["user_name"=>"Your account is Inactive"])->withInput();
			}

	        $user_detail = UserDetail::firstOrNew(['user_id'=>$user->id]);
            $user_detail->plain_password = encrypt($request->password);
            $user_detail->pin_code = $api_data->profile_detail->pin_code;
            $user_detail->address = $api_data->profile_detail->address;
            $user_detail->state_name = $api_data->profile_detail->state_detail->name;
            $user_detail->city_name = $api_data->profile_detail->city_detail->name;
            $user_detail->save();

            Auth::login($user);
            return redirect()->route('user.dashboard');
		}
	}


	public function loginType($mobile)
	{
		if(preg_match('/^[0-9]{10}+$/', $mobile)){
			return 'mobile' ;
		}else{
			return 'refer_code';
		}
	}

	public function logout(Request $request){
        $this->guard()->logout();
        return redirect('/login');
    }
}
