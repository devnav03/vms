<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;

use Illuminate\Foundation\Auth\AuthenticatesUsers;

use Illuminate\Http\Request;
use App\Model\Company;
use Illuminate\Support\Facades\Auth;
use App\Model\Setting;
use URL;
use App\Events\OtpEvent;
use App\Model\UserOtp;
use Illuminate\Support\Facades\Crypt;
class LoginController extends Controller
{

    /*

    |--------------------------------------------------------------------------

    | Login Controller

    |--------------------------------------------------------------------------

    |

    | This controller handles authenticating users for the application and

    | redirecting them to your home screen. The controller uses a trait

    | to conveniently provide its functionality to your applications.

    |

    */


    use AuthenticatesUsers;

    /**

     * Where to redirect users after login.

     *
     * @var string

     */

    protected $redirectTo = '/admin-panel/dashboard';

    /**

     * Create a new controller instance.

     *

     * @return void

     */

    public function __construct()

    {

        $this->middleware('admin.guest', ['except' => 'logout']);

    }



    // Shwoing the admin login form method

    public function showLoginForm()

    {

        return view('admin.auth.login');

    }



    // Logout method with guard logout for admin only

    public function logout()

    {

        $this->guard()->logout();

        return redirect()->route('admin.login.form');

    }
    
    public function twoFactorVerify(Request $request){
      // dd($request->all());
      $user_id = Crypt::decryptString($request->random_id);
      $otpres=UserOtp::where(['mobile'=>Crypt::decryptString($request->random_mobile),'otp'=>$request->otp,'company_id'=>Crypt::decryptString($request->cid),'status'=>0])->first();
        if(empty($otpres)){
          $otp_data=array('id'=>$request->random_id,'mobile'=>$request->random_mobile,'cid'=>$request->cid);
          return view('admin.auth.otp',compact('otp_data'))->with(['message' => 'Invalid Otp', 'class' => 'error']);
          

        }else{
          UserOtp::where(['mobile'=>Crypt::decryptString($request->random_mobile),'otp'=>$request->otp])->update(['status'=>1]);
          
          $this->guard()->loginUsingId($user_id);
          
          return redirect()->route('admin.dashboard.index');
        }
    }


    // defining auth  guard

    protected function guard()

    {

        return Auth::guard('admin');

    }

        protected function authenticated(Request $request, $user)
        {
            
            $url=URL::to('/');
            $arrContextOptions=array(
		"ssl"=>array(
			"verify_peer"=>false,
			"verify_peer_name"=>false,
		),
	); 
	  $response=file_get_contents("https://vztor.sspl22.in/superadmin/public/api/company?cid=".$url,false, stream_context_create($arrContextOptions));
            $datas=json_decode($response);
            
            $token=Setting::where(['company_id'=>$datas->data->id,'name'=>'token'])->first();

            $id=$token->company_id;
            $curl = curl_init();
            $response=file_get_contents("https://vztor.sspl22.in/superadmin/public/api/companyValidate?unique_id=".$token->company_id,false, stream_context_create($arrContextOptions));
   
    $data=json_decode($response);
            if(empty($data)){
              $this->guard()->logout();
                abort(403);
            }
            if($data->status=="failed"){
              $this->guard()->logout();
              abort(403, $data->msg);
            }else{
              $CIDATA=array('cid'=>$id,'name'=>$data->data->name,'logo'=>$data->data->logo,'address'=>$data->data->address,'where'=>$data->data->where);
              $request->session()->put('CIDATA', json_encode($CIDATA));
              if($user->two_factor==1){
                $gen_otp = rand(100000, 999999);
                $otp = new UserOtp;
                $otp->mobile = $user->mobile;
                $otp->otp = $gen_otp;
                $otp->company_id = $id;
                $otp->save();
                $this->guard()->logout();
                event(new OtpEvent($request->mobile, 'Your OTP for VMS Two Factor Authentication is:' . $otp->otp));
                $otp_data=array('id'=>Crypt::encryptString($user->id),'mobile'=>Crypt::encryptString($user->mobile),'cid'=>Crypt::encryptString($id));
                return view('admin.auth.otp',compact('otp_data'));
              }
            }

      }
}
