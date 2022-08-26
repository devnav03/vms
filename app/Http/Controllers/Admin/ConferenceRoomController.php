<?php
namespace App\Http\Controllers\Admin;

use App\Admin;
use App\Model\Building;
use App\Model\Department;
use App\Model\Location;
use App\Model\ConferenceRoom;
use App\Model\Menu;
use App\Model\Permission;
use App\Model\PermissionRole;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
class ConferenceRoomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // dd("vivek");/
     
        $cidata=$request->session()->get('CIDATA');
        $user_id =  auth('admin')->user()->id;
        $cidata_ob=json_decode($cidata);
        $result = ConferenceRoom::where(['added_by'=>$user_id,'company_id'=>$cidata_ob->cid])->with(['getLocation'=>function($q){
            $q->select('id','name');
        },'getBuilding'=>function($q){
			$q->select('id','name');
		},'getDepartment'=>function($q){
			$q->select('id','name');
		},'getDevice'=>function($q){
			$q->select('id','name');
		}])->get();
        return view('admin.conference.list',compact('result'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user_id =  auth('admin')->user()->id;
        $company_data = $this->company_data;    
        $locations = Location::where($company_data->where, $company_data->id)->get();
       
		$user_details = Admin::where('id',$user_id)->with(['getLocation'=>function($q){
			$q->select('id','name');
		},'getBuilding'=>function($q){
			$q->select('id','name');
		},'getDepartment'=>function($q){
			$q->select('id','name');
		}])->first();
		return view('admin.conference.create',compact('user_details','locations'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $cidata=$request->session()->get('CIDATA');
        $cidata_ob=json_decode($cidata);
        $user_id =  auth('admin')->user()->id;
        $this->validate($request,[
            'room' => 'required',
        ]);

        $panic = new ConferenceRoom;
        $panic->room = $request->room;
        $panic->location_id = $request->location_id;
        $panic->building_id = $request->building_id;
        $panic->department_id = $request->department_id;
        $panic->device_id = $request->device_id;
        $panic->company_id = $cidata_ob->cid;
        $panic->added_by = $user_id;
        $panic->save();
        return redirect()->back()->with(['class'=>'success','message'=>'Conference Room added successfully']);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($table)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function edit($table)
    {
        $user_id =  auth('admin')->user()->id;
        $company_data = $this->company_data;    
        $locations = Location::where($company_data->where, $company_data->id)->get();
       
		$user_details = Admin::where('id',$user_id)->with(['getLocation'=>function($q){
			$q->select('id','name');
		},'getBuilding'=>function($q){
			$q->select('id','name');
		},'getDepartment'=>function($q){
			$q->select('id','name');
		}])->first();
        
        $menus = ConferenceRoom::where(['id'=>$table,'added_by'=>$user_id])->with(['getLocation'=>function($q){
            $q->select('id','name');
        },'getBuilding'=>function($q){
			$q->select('id','name');
		},'getDepartment'=>function($q){
			$q->select('id','name');
		},'getDevice'=>function($q){
			$q->select('id','name');
		}])->first();
        return view('admin.conference.edit',compact('menus','locations','user_details'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $table)
    {
        $this->validate($request,[
            'room' => 'required'
        ]);
        $user_id =  auth('admin')->user()->id;
        $panic = ConferenceRoom::where('id', $table)->first();
        $panic->room = $request->room;
        $panic->location_id = $request->location_id;
        $panic->building_id = $request->building_id;
        $panic->department_id = $request->department_id;
        $panic->device_id = $request->device_id;
        $panic->added_by = $user_id;

        if($panic->save()){
            return redirect()->back()->with(['class'=>'success','message'=>'Conference Rooms Update successfully']);
        }else{
            return redirect()->back()->with(['class'=>'error','message'=>'Some error occur']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($table)
    {
        $menu = ConferenceRoom::where('id',$table);
        if ($menu->count()) {
            $menu->delete();
        }
        return redirect()->back()->with(['class'=>'success','message'=>'Conference Room deleted successfully']);

    }

}
