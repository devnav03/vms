<?php

namespace App\Http\Controllers\web;



use App\Model\Department;

use App\Model\Menu;

use App\Model\Permission;
use App\Model\DesignationDepartment;
use App\Model\PermissionRole;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Model\Location;
use App\Model\Building;

class BuildingController extends Controller

{

    /**

     * Display a listing of the resource.

     *

     * @return \Illuminate\Http\Response

     */

    public function index()

    {

        $buildings= Building::with(['getLocation'=>function($query){
			$query->select('id','name');
		}])->get();//->toarray();
		//echo "<pre>";
		//print_r($buildings);die;
		return view('admin.building.list',compact('buildings'));

    }



    /**

     * Show the form for creating a new resource.

     *

     * @return \Illuminate\Http\Response

     */

    public function create()

    {
		$locations=Location::all();
		
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

        $this->validate($request,[

            'name' => 'required',
			'location_id' => 'required',
			'status_id' => 'required',

        ]);

        

        $buildings = new Building;

        $buildings->name = $request->name;
		$buildings->location_id = $request->location_id;
        $buildings->status = $request->status_id;

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

  

        $menu = Department::where('id',$table);

        if ($menu->count()) {

            $menu->delete();

        }

        return redirect()->back()->with(['class'=>'success','message'=>'Department deleted successfully']);

        

    }
	
	public function getBuilding(Request $request){
		$cidata=$request->session()->get('CIDATA');
        $cidata_ob=json_decode($cidata);
        $company_id=$cidata_ob->cid;
		
		 $building = Building::where('location_id', $request->location_id)->where('company_id', $company_id)->get();
		if(isset($request->designation_id)){
              $designation_id = $request->designation_id;
            } else {
              $designation_id = 0;
            }
		
		 $designations =DesignationDepartment::with(['designation'=>function($query){
              $query->select('id','name');
            }])->where('parent_id', NULL)->get();

            $designationList='';
            $designationList .= '<select name="designation_id[]" id="designation_id" multiple class="select2-example" style="display:block;"><option value="" >select Designation</option>';
            foreach($designations as $key => $designation){
                if($designation_id != 0){
                    if($designation_id == $designation->designation->id){
                        $designationList .= '<option value="'.$designation->designation->id.'" selected>'.$designation->designation->name.'</option>';
                    } else {
                        $designationList .= '<option value="'.$designation->designation->id.'" >'.$designation->designation->name.'</option>';
                    }
                } else {
                    $designationList .= '<option value="'.$designation->designation->id.'" >'.$designation->designation->name.'</option>';
                }
                
                $designations =DesignationDepartment::with(['designation'=>function($query){
                $query->select('id','name');
                }])->where('parent_id', $designation->designation->id)->get();
            
            foreach($designations as $key => $designation){
                if($designation_id != 0){
                    if($designation_id == $designation->designation->id){
                        $designationList .= '<option value="'.$designation->designation->id.'" selected>&nbsp;&nbsp;-'.$designation->designation->name.'</option>';
                    } else {
                        $designationList .= '<option value="'.$designation->designation->id.'" >&nbsp;&nbsp;-'.$designation->designation->name.'</option>';
                    }
                } else {
                    $designationList .= '<option value="'.$designation->designation->id.'" >&nbsp;&nbsp;-'.$designation->designation->name.'</option>';
                }

            $designations =DesignationDepartment::with(['designation'=>function($query){
                $query->select('id','name');
                }])->where('parent_id', $designation->designation->id)->get();
            foreach($designations as $key => $designation){
               if($designation_id != 0){
                    if($designation_id == $designation->designation->id){
                        $designationList .= '<option value="'.$designation->designation->id.'" selected>&nbsp;&nbsp;&nbsp;&nbsp;--'.$designation->designation->name.'</option>';
                    } else {
                        $designationList .= '<option value="'.$designation->designation->id.'" >&nbsp;&nbsp;&nbsp;&nbsp;--'.$designation->designation->name.'</option>';
                    }
                } else {
                    $designationList .= '<option value="'.$designation->designation->id.'" >&nbsp;&nbsp;&nbsp;&nbsp;--'.$designation->designation->name.'</option>';
                }

                $designations =DesignationDepartment::with(['designation'=>function($query){
                $query->select('id','name');
                }])->where('parent_id', $designation->designation->id)->get();
            foreach($designations as $key => $designation){
                if($designation_id != 0){
                    if($designation_id == $designation->designation->id){
                        $designationList .= '<option value="'.$designation->designation->id.'" selected>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;---'.$designation->designation->name.'</option>';
                    } else {
                        $designationList .= '<option value="'.$designation->designation->id.'" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;---'.$designation->designation->name.'</option>';
                    }
                } else {
                    $designationList .= '<option value="'.$designation->designation->id.'" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;---'.$designation->designation->name.'</option>';
                }

                $designations =DesignationDepartment::with(['designation'=>function($query){
                $query->select('id','name');
                }])->where('parent_id', $designation->designation->id)->get();
               foreach($designations as $key => $designation){
                if($designation_id != 0){
                    if($designation_id == $designation->designation->id){
                        $designationList .= '<option value="'.$designation->designation->id.'" selected>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;----'.$designation->designation->name.'</option>';
                    } else {
                        $designationList .= '<option value="'.$designation->designation->id.'" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;----'.$designation->designation->name.'</option>';
                    }
                } else {
                    $designationList .= '<option value="'.$designation->designation->id.'" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;----'.$designation->designation->name.'</option>';
                }
            }
            }
            }


            }

            }
            $designationList .= '</select> <script>
    $(".select2-example").select2({
   placeholder: "Select"
});

</script>';
		$building['des'] = $designationList;
		
		 return response()->json($building);
	}


	public function getBuilding_front(Request $request){
		//$cidata=$request->session()->get('CIDATA');
      //  $cidata_ob=json_decode($cidata);
        //$company_id=$cidata_ob->cid;
		
		 $building = Building::where('location_id', $request->location_id)->get();
		//dd($building);
		 return response()->json($building);
	}



    

}