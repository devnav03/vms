<?php
namespace App\Http\Controllers\web;


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
use App\Events\OtpEvent;
use App\Model\VisitorGroup;
use App\Model\Location;
use App\Model\Country;

class LocationController extends Controller
{

	public function index(Request $request){
       $locations=Location::where('status',1)->get();
	   $get_country=Country::all();
	  return view('admin.location.list',compact('locations','get_country'));
	}


	public function create(Request $request){
		$get_country=Country::all();
	  return view('admin.location.create',compact('get_country'));
	}

	public function store(Request $request){
		$old_locations=Location::where(['name'=>$request->location])->first();
		if(!empty($old_locations)){
			return back()->with(['message'=>'Location already added', 'class'=>'error']);
		}
		$this->validate($request, [
            'location'=>'required',
			'country_id'=>'required',
			'state_id'=>'required',
			'longitude'=>'required',
			'latitude'=>'required',
        ]);
		$locations=new Location();
		$locations->name=$request->location;
		$locations->country_id=$request->country_id;
		$locations->state_id=$request->state_id;
		$locations->longitude=$request->longitude;
		$locations->latitude=$request->latitude;
		if($locations->save())
        {
			return back()->with(['message'=>'Location Addedd Successfully', 'class'=>'success']);
		}else{
			 return back()->with(['message'=>'Oops! Something went wrong', 'class'=>'error']);
		}
	}

	public function edit($id){

        $location = Location::where('id', $id)->first();
		$get_country=Country::all();
        return view('admin.location.edit',compact('location','id','get_country'));
	}

	public function update(Request $request, $table){

        $this->validate($request, [
            'location'=>'required',
			'country_id'=>'required',
			'state_id'=>'required',
			'longitude'=>'required',
			'latitude'=>'required',
        ]);

        $location_update = Location::where('id', $table)->first();

        $location_update->name=$request->location;
		$location_update->country_id=$request->country_id;
		$location_update->state_id=$request->state_id;
		$location_update->longitude=$request->longitude;
		$location_update->latitude=$request->latitude;
		if($location_update->save()){

            return redirect()->back()->with(['class'=>'success','message'=>'Location Update successfully']);

        }else{

            return redirect()->back()->with(['class'=>'error','message'=>'Some error occur']);

        }

    }
	



}
