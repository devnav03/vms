<?php

namespace App\Http\Controllers\Api\Auth;

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
use App\User;
use App\Admin;
use Illuminate\Support\Facades\Hash;
use DB;

class StaffLoginController extends Controller
{


	public function loginStaff(Request $request){

		$this->validate($request, [
			'email'=>'required',
			'password'=>'required'
		]);
        $get_admin =Admin::select('id','role_id','password','company_id','device_id','location_id','building_id','department_id','two_factor','name','email','mobile','auto_approved','gender','address','avatar','status_id','employee_type')->where(['email'=>$request->email,'company_id'=>$request->company_id])->with(['getLocation'=>function($q){
			$q->select('id','name');
		},'getBuilding'=>function($q){
			$q->select('id','name');
		},'getDepartment'=>function($q){
			$q->select('id','name');
		},'role'=>function($q){
			$q->select('id','name');
		}])->first();
        if(!empty($get_admin)){
			if (Hash::check($request->password, $get_admin->password)) {
				return response()->json(['message' => 'Login sucessfully', 'msg' => 'success','data'=>$get_admin]);
			}else{
				return response()->json(['message' => 'Please check login Credentials', 'msg' => 'success','data'=>$get_admin]);
			}    
		}
		return response()->json(['message' => 'Please check login Credentials', 'msg' => 'success','data'=>$get_admin]);     
       
    }


	public function loginType($mobile)
	{

		if(preg_match('/^[0-9]{10}+$/', $mobile)){
    		return 'mobile' ;
    	}else{
			return 'refer_code';
		}
	}





}