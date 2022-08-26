<?php

namespace App\Http\Controllers\Web\Auth;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;

use Socialite;

use App\User;

use App\Model\UserDetail;

use App\Helpers\pay2AllApi;

use Illuminate\Foundation\Auth\AuthenticatesUsers;



class LoginController extends Controller

{

	use AuthenticatesUsers;

	# start facebook & google signin or sighup

	public function redirectToProvider(Request $request, $provider){

		$prev_url =  url()->previous();

		$request->session()->put('prev_url', $prev_url);


        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback(Request $request, $provider){

        $prev_url = $request->session()->get('prev_url');
        $user = Socialite::driver($provider)->stateless()->user();

        // dd($user);
        $authUser = $this->findOrCreateUser($user, $provider);
        Auth::login($authUser, true);
        return redirect($prev_url)->withInput();
    }

    public function findOrCreateUser($user, $provider)
    {
    	$authUser = User::where('provider_id', $user->id)->first();    	
    	if($authUser){
    		return $authUser;	
       	}

       	$new_user = User::firstOrNew(['refer_code'=>$user->id]);
       	$new_user->group_id = 4; #social Login
       	$new_user->first_name = $user->given_name;
       	$new_user->last_name = $user->family_name;
       	$new_user->email = $user->email;
       	$new_user->plan_name = $provider;
       	$new_user->password = bcrypt($user->id); #prvider id will be password
       	$new_user->save();


       	$user_detail = UserDetail::firstOrNew(['user_id'=>$new_user->id]);
        $user_detail->image = $user->picture;
        $user_detail->remark = $provider;
        $user_detail->plain_password = encrypt($user->id);
        $user_detail->save();


       	return $new_user;
          
    }
    # End facebook & google signin or sighup



	public function userLogin(Request $request){

		$this->validate($request, [

			'user_name'=>'required',

			'password'=>'required'

		]);



		$user_type = substr($request->user_name, 0, 3);


		$login_type = $this->loginType($request->user_name);



		if(Auth::attempt([$login_type=>$request->user_name, 'password'=>$request->password])){



			if(Auth::user()->status == 0){



				$this->logout($request);



				return response()->json(['errors'=>["user_name"=>["Your account is Inactive"]]], 422);



			}


			// return redirect()->route('user.dashboard');

			return response()->json(['error'=>false, 'message'=>'Login Successful'], 200);




		}



		return response()->json(['errors'=>["user_name"=>["Invalid Refer / Mobile Or Password"]]], 422);

	}



	public function kdlLogin($request){



		$url = 'https://acplworld.co/walletservice.asmx/checklogin';

		$credential = [

			'userid'=>'ACPL514400',

			'password'=>'001589',

			'passwd'=>'#3999ACPL38921#'

		];



		$data = pay2AllApi::callApi('POST', $url, $credential, $header=false);



		return response()->json(['data'=>$data]);



		dd($data);



		$response = (object)$data;



		if(isset($response->errors) || (isset($response->error) && $response->error == true)){

			return response()->json(['errors'=>["user_name"=>["Invalid Refer / Mobile Or Password"]]], 422);

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

	        	return response()->json(['errors'=>["user_name"=>["Your account is Inactive"]]], 422);

			}



	        $user_detail = UserDetail::firstOrNew(['user_id'=>$user->id]);

            $user_detail->plain_password = encrypt($request->password);

            $user_detail->pin_code = $api_data->profile_detail->pin_code;

            $user_detail->address = $api_data->profile_detail->address;

            $user_detail->state_name = $api_data->profile_detail->state_detail->name;

            $user_detail->city_name = $api_data->profile_detail->city_detail->name;

            $user_detail->save();



            Auth::login($user);



            return response()->json(['error'=>false, 'message'=>'Login Successful'], 200);

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

        return redirect('/');

    }

}

