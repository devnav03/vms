<?php
namespace App\Http\Controllers\Admin;


use DB;
use App\User;
use App\Admin;
use Carbon\Carbon;
use App\Http\Requests\Admin\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Model\UserDetail;

use App\Events\SmsEvent;
use Illuminate\Support\Facades\Redirect;
use Mail;
use App\Events\OtpEvent;use App\Model\VisitorGroup;
use App\Model\Device;
class ManageReportController extends Controller
{

	public function index(Request $request){
			$cidata=$request->session()->get('CIDATA');
			$cidata_ob=json_decode($cidata);
			 $devices=Device::where($cidata_ob->where,$cidata_ob->cid)->get();
	     return view('admin.device.list',compact('devices'));
	}


	public function create(Request $request){
	  return view('admin.device.create');
	}

	public function store(Request $request){
		// $old_devices=Device::where(['name'=>$request->device_name])->first();
		// if(!empty($old_devices)){
		// 	return back()->with(['message'=>'device already added', 'class'=>'error']);
		// }
		$this->validate($request, [
            'device_name'=>'required',
            'status'=>'required',
			'office_name'=>'required'
      	]);
		$devices=new Device();
		$devices->name=$request->device_name;
    	$devices->status=$request->status;
		$devices->office_name=$request->office_name;
		$cidata=$request->session()->get('CIDATA');
		$cidata_ob=json_decode($cidata);
		$column=$cidata_ob->where;
		$devices->$column=$cidata_ob->cid;
		if($devices->save())
        {
			return back()->with(['message'=>'Device Addedd Successfully', 'class'=>'success']);
		}else{
			 return back()->with(['message'=>'Oops! Something went wrong', 'class'=>'error']);
		}
	}

	public function edit($id){

        $device = Device::where('id', $id)->first();
		return view('admin.device.edit',compact('device','id'));
	}

	public function update(Request $request, $table){

        $this->validate($request, [
            'device_name'=>'required',
            'status'=>'required',
			'office_name'=>'required'
        ]);

        $device_update = Device::where('id', $table)->first();

        $device_update->name=$request->device_name;
        $device_update->status=$request->status;
	      if($device_update->save()){

            return redirect()->back()->with(['class'=>'success','message'=>'Device Update successfully']);

        }else{

            return redirect()->back()->with(['class'=>'error','message'=>'Some error occur']);

        }

    }

    public function destroy ($id){

          $device = Device::where('id', $id)->delete();
          if($device==1){

              return redirect()->back()->with(['class'=>'success','message'=>'Device Delete successfully']);

          }else{

              return redirect()->back()->with(['class'=>'error','message'=>'Some error occur']);

          }
  	}
}
