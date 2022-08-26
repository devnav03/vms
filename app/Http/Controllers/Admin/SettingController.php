<?php
namespace App\Http\Controllers\Admin;
use DB;
use App\User;
use App\Admin;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Model\Setting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Events\SmsEvent;

use App\Events\OtpEvent;use App\Model\VisitorGroup;

class SettingController extends Controller
{

  public function index(Request $request){
    $cidata=$request->session()->get('CIDATA');
    $cidata_ob=json_decode($cidata);
    $settings=Setting::where(['name'=>'ams_send',$cidata_ob->where=>$cidata_ob->cid])->first();
    // dd($settings);
    return view('/admin.setting.setting',compact('settings'));
  }

  public function amsSettingUpdate(Request $request){
    $cidata=$request->session()->get('CIDATA');
    $cidata_ob=json_decode($cidata);
    Setting::where([$cidata_ob->where=>$cidata_ob->cid,'name'=>'ams_send'])->update(['status'=>$request['ams_send']]);
    return back()->with(['message'=>'Setting Successfully Updated', 'class'=>'success']);
  }




}
