<?php
namespace App\Http\Controllers\Api;
use DB;
use App\User;
use App\Model\Setting;
use App\Model\Permission;
use App\Model\PermissionRole;
use App\Model\Role;
use App\Admin;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Mail;
use App\Events\OtpEvent;
use Illuminate\Support\Facades\Crypt;
class AdminController extends BaseController
{

    public function superUserCreate(Request $request){
       
       
        $Admin =new Admin();
        $Admin->role_id=$request['role_id'];
        $Admin->company_id=$request['company_id'];
        $Admin->name =$request['name'];
        $Admin->email=$request['email'];
        $Admin->mobile=$request['mobile'];
        $Admin->gender='Male';
        $Admin->password=Hash::make($request['password']);
        $Admin->allowed_ip=1;
        $Admin->status_id=1;
        $Admin->save();
        $Setting=new Setting();
        $Setting->value=$request['token'];
        $Setting->name='token';
        $Setting->company_id=$request['company_id'];
        $Setting->status='Active';
        $Setting->save();
        $Setting2=new Setting();
        $Setting2->name='ams_send';
        $Setting2->status='Active';
        $Setting2->company_id=$request['company_id'];
        $Setting2->save();
        $role_array=[1,2,3,4,5];
        foreach($role_array as $permission){
            $PermissionRole=new PermissionRole();
            $PermissionRole->permission_id=$permission;
            $PermissionRole->role_id=$request['role_id'];
            $PermissionRole->company_id=$request['company_id'];
            $PermissionRole->save();
        }
        
    }
  
  



}
