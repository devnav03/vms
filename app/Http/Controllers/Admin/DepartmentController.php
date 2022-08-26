<?php
namespace App\Http\Controllers\Admin;

use App\Model\Department;
use App\Model\Menu;
use App\Model\Permission;
use App\Model\PermissionRole;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Location;
use App\Model\Device;
use App\Model\DeviceDepartment;
use URL;
class DepartmentController extends Controller
{
	
	protected $controller;
	public function __construct( Controller $controller ){
		$this->controller = $controller;
	}
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // dd("vivek");
        $cidata=$request->session()->get('CIDATA');
        $cidata_ob=json_decode($cidata);
        $devices=Device::get()->toArray();
        $menus = Department::with(['getLocation'=>function($query){
          $query->select('id','name');
        },'building'=>function($query){
          $query->select('id','name');
        },'getDeviceDepartment'=>function($query){
            $query->select('id','department_id','device_id')->with(['device'=>function($query){
              $query->select('id','name');
            }]);
        }])->where($cidata_ob->where,$cidata_ob->cid)->get();

        // $devices = DeviceDepartment::with(['device'=>function($query){
        //   $query->select('id','name');
        // }])->with(['department'=>function($query){
        //   $query->select('id','name');
        // }])->get();

        // echo "<pre>";
        //
        // dd($menus);
        return view('admin.department.list',compact('menus'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {	$cidata=$request->session()->get('CIDATA');
        $cidata_ob=json_decode($cidata);
        $locations=Location::where(['company_id'=>$cidata_ob->cid])->get();
        $devices=Device::where(['company_id'=>$cidata_ob->cid])->get();
        return view('admin.department.create',compact('locations','devices'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $user_id =  auth('admin')->user()->id;
		$cidata=$request->session()->get('CIDATA');
		$cidata_ob=json_decode($cidata);
		$url=URL::to('/');
		// $count=Department::where('company_id',$cidata_ob->cid)->count();
		// $res = json_decode($this->controller->packageCheck($url,'department',$count),true);
		// if($res['status']=="failed"){
		// 	return back()->with(['message'=>$res['msg'], 'class'=>'error']);
		// }
        $this->validate($request,[
            'name' => 'required',
            'location_id' => 'required',
            'building_id' => 'required',
            'device_id'=>'required'
        ]);

        $panic = new Department;
        $panic->name = $request->name;
        $panic->location_id = $request->location_id;
        $panic->building_id = $request->building_id;
        $panic->status = 1;
        $cidata=$request->session()->get('CIDATA');
        $cidata_ob=json_decode($cidata);
        $column=$cidata_ob->where;
        $panic->$column = $cidata_ob->cid;
        $panic->save();
        foreach($request->device_id as $device){
          $device_depa=new DeviceDepartment();
          $device_depa->department_id=$panic->id;
          $device_depa->device_id=$device;
          $device_depa->save();

        }
        return redirect()->back()->with(['class'=>'success','message'=>'Department added successfully']);

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
    public function edit(Request $request,$table)
    {
		$cidata=$request->session()->get('CIDATA');
        $cidata_ob=json_decode($cidata);
		
        $menus = Department::where('id', $table)->with(['getDeviceDepartment'=>function($q){
			$q->select('id','department_id','device_id');
		}])->first();

        $locations=Location::where(['company_id'=>$cidata_ob->cid])->get();
        $devices=Device::where(['company_id'=>$cidata_ob->cid])->get();
        return view('admin.department.edit',compact('menus','table','locations','devices'));
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
					'name' => 'required',	
					'location_id' => 'required',		
					'building_id' => 'required',
        		]);
        $panic_update = Department::where('id', $table)->first();
        $panic_update->name = $request->name;	
		$panic_update->location_id = $request->location_id;	
		$panic_update->building_id = $request->building_id;
		
        if($panic_update->save()){
			$device_delete =DeviceDepartment::where('department_id',$panic_update->id);
			if ($device_delete->count()) {
				$device_delete->delete();
			}
			
			foreach($request->device_id as $device){
			  $device_depa=new DeviceDepartment();
			  $device_depa->department_id=$panic_update->id;
			  $device_depa->device_id=$device;
			  $device_depa->save();
			}
            return redirect()->back()->with(['class'=>'success','message'=>'Department Update successfully']);
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

        $menu = Department::where('id',$table);
        if ($menu->count()) {
            $menu->delete();
        }
        return redirect()->back()->with(['class'=>'success','message'=>'Department deleted successfully']);

    }		public function getDepartmentBybuildingId(Request $request){		$departments=Department::where('building_id',$request->building_id)->get();		return response()->json($departments);	}

}
