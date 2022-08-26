<?php

namespace App\Http\Controllers\Admin;



use App\Model\Department;

use App\Model\Menu;

use App\Model\Permission;

use App\Model\PermissionRole;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Model\Location;
use App\Model\Building;
use URL;
class BuildingController extends Controller

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
      $cidata=$request->session()->get('CIDATA');
      $cidata_ob=json_decode($cidata);
      $buildings= Building::with(['getLocation'=>function($query){
			     $query->select('id','name');
		  }])->where($cidata_ob->where,$cidata_ob->cid)->get();
		
		return view('admin.building.list',compact('buildings'));

    }



    /**

     * Show the form for creating a new resource.

     *

     * @return \Illuminate\Http\Response

     */

    public function create(Request $request)

    {
        $cidata=$request->session()->get('CIDATA');
        $cidata_ob=json_decode($cidata);
		$locations=Location::where(['company_id'=>$cidata_ob->cid])->get();

        return view('admin.building.create',compact('locations'));

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
		// $count=Building::where('company_id',$cidata_ob->cid)->count();
		// $res = json_decode($this->controller->packageCheck($url,'building',$count),true);
		// if($res['status']=="failed"){
		// 	return back()->with(['message'=>$res['msg'], 'class'=>'error']);
		// }
        $this->validate($request,[

            'name' => 'required',
      			'location_id' => 'required',
      			'status_id' => 'required',

        ]);



        $buildings = new Building;

        $buildings->name = $request->name;
		$buildings->location_id = $request->location_id;
        $buildings->status = $request->status_id;
        $cidata=$request->session()->get('CIDATA');
        $cidata_ob=json_decode($cidata);
        $column=$cidata_ob->where;
        $buildings->$column = $cidata_ob->cid;
        $buildings->save();



        return redirect()->back()->with(['class'=>'success','message'=>'Building added successfully']);



    }





    /**

     * Show the form for editing the specified resource.

     *

     * @param  int  $id

     * @return \Illuminate\Http\Response

     */

    public function edit($id)

    {

        $building = Building::where('id', $id)->first();
		$locations=Location::all();
        return view('admin.building.edit',compact('building','id','locations'));

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
        			'status_id'=>'required'

        ]);

        $building_update = Building::where('id', $table)->first();

        $building_update->name = $request->name;
    		$building_update->location_id = $request->location_id;
    		$building_update->status = $request->status_id;


        if($building_update->save()){

            return redirect()->back()->with(['class'=>'success','message'=>'Building Update successfully']);

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



        $menu = Building::where('id',$table);

        if ($menu->count()) {

            $menu->delete();

        }

        return redirect()->back()->with(['class'=>'success','message'=>'Department deleted successfully']);



    }

	public function getBuilding(Request $request){
     $cidata=$request->session()->get('CIDATA');
     $cidata_ob=json_decode($cidata);
		 $building = Building::where(['location_id'=>$request->location_id,$cidata_ob->where=>$cidata_ob->cid])->get();
		 return response()->json($building);
	}



}
