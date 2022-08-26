<?php
namespace App\Http\Controllers\web;

use App\Model\Department;
use App\Model\Menu;
use App\Model\Permission;
use App\User;
use App\Model\PermissionRole;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Location;
use App\Admin;
class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // dd("vivek");
        $menus = Department::with(['getLocation'=>function($query){			$query->select('id','name');		}])->		with(['building'=>function($query){			$query->select('id','name');		}])->get();		return view('admin.department.list',compact('menus'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {		$locations=Location::all();
        return view('admin.department.create',compact('locations'));
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
        $this->validate($request,[
            'name' => 'required',			'location_id' => 'required',			'building_id' => 'required',
        ]);

        $panic = new Department;
        $panic->name = $request->name;				$panic->location_id = $request->location_id;				$panic->building_id = $request->building_id;
        $panic->status = 1;
        $panic->save();

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
    public function edit($table)
    {
        $menus = Department::where('id', $table)->first();		
        $locations=Location::all();
        return view('admin.department.edit',compact('menus','table','locations'));
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
            'name' => 'required',			'location_id' => 'required',			'building_id' => 'required',
        		]);
        $panic_update = Department::where('id', $table)->first();
        $panic_update->name = $request->name;		$panic_update->location_id = $request->location_id;		$panic_update->building_id = $request->building_id;

        if($panic_update->save()){
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

    }
    public function getDepartmentBybuildingId(Request $request){
      $departments=Department::where('building_id',$request->building_id)->get();
      return response()->json($departments);
    }
    public function deleteEmployee(Request $request,$id){
      Admin::where('id',$id)->delete();
      return redirect()->back()->with(['class'=>'success','message'=>'Employee successfully deleted']);
    }
	
	public function admin_guard_destroy(Request $request,$id){
      User::where('id',$id)->delete();
      return redirect()->back()->with(['class'=>'success','message'=>'Guard successfully deleted']);
    }

}
