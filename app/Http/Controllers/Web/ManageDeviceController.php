<?php
namespace App\Http\Controllers\Web;


use DB;
use App\User;
use App\Admin;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Model\UserDetail;
use App\Model\ConferenceRoom;
use App\Model\DesignationDepartment;

use App\Events\SmsEvent;
use Illuminate\Support\Facades\Redirect;
use Mail;
use App\Events\OtpEvent;
use App\Model\VisitorGroup;
use App\Model\Device;
use App\Model\DeviceDepartment;
class ManageDeviceController extends Controller
{

	public function index(Request $request){
       $devices=Device::get();
	     return view('admin.device.list',compact('devices'));
	}


	public function create(Request $request){
	  return view('admin.device.create');
	}

	public function store(Request $request){
		$old_devices=Device::where(['name'=>$request->device_name])->first();
		if(!empty($old_devices)){
			return back()->with(['message'=>'device already added', 'class'=>'error']);
		}
		$this->validate($request, [
            'device_name'=>'required',
            'status'=>'required'
      	]);
		$devices=new Device();
		$devices->name=$request->device_name;
    $devices->status=$request->status;

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
            'status'=>'required'
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
        public function getDeviceByDepartmentId_front(Request $request){
        //$cidata=$request->session()->get('CIDATA');
      //  $cidata_ob=json_decode($cidata);
        //$company_id=$cidata_ob->cid;

        $building =DeviceDepartment::with(['device'=>function($query){
              $query->select('id','name');
            }])->where('department_id',$request->department_id)->get();
        //dd($building);
         return response()->json($building);
    }

		public function getDeviceByDepartmentId(Request $request){
				$device_name['div']=DeviceDepartment::with(['device'=>function($query){
              $query->select('id','name');
            }])->where('department_id',$request->department_id)->get();
            
            if(isset($request->designation_id)){
              $designation_id = $request->designation_id;
            } else {
              $designation_id = 0;
            }

            $designations =DesignationDepartment::with(['designation'=>function($query){
              $query->select('id','name');
            }])->where('department_id',$request->department_id)->where('parent_id', NULL)->get();

            $designationList='';
            $designationList .= '<select name="designation_id" id="designation_id"  class="form-control nowarn" style="display:block;" required><option value="" >select Designation</option>'; 
			if($designation_id == 'Guard'){
				$designationList .='<option value="Guard" selected>Guard</option>';
			} else {
				$designationList .='<option value="Guard">Guard</option>';
			}
			
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
                }])->where('department_id',$request->department_id)->where('parent_id', $designation->designation->id)->get();
            
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
                }])->where('department_id',$request->department_id)->where('parent_id', $designation->designation->id)->get();
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
                }])->where('department_id',$request->department_id)->where('parent_id', $designation->designation->id)->get();
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
                }])->where('department_id',$request->department_id)->where('parent_id', $designation->designation->id)->get();
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
            $designationList .= '</select>';
            
                $device_name['des'] = $designationList;

				return response()->json($device_name);
		}

    public function getdesignationByRoom(Request $request){
            
            if(isset($request->designation_id)){
              $designation_id = $request->designation_id;
            } else {
              $designation_id = 0;
            }

            $room = ConferenceRoom::where('id', $request->room_id)->select('department_id')->first();

            $designations =DesignationDepartment::with(['designation'=>function($query){
              $query->select('id','name');
            }])->where('department_id',$room->department_id)->where('parent_id', NULL)->get();

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
                }])->where('department_id',$room->department_id)->where('parent_id', $designation->designation->id)->get();
            
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
                }])->where('department_id',$room->department_id)->where('parent_id', $designation->designation->id)->get();
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
                }])->where('department_id',$room->department_id)->where('parent_id', $designation->designation->id)->get();
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
                }])->where('department_id',$room->department_id)->where('parent_id', $designation->designation->id)->get();
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
            
                $device_name['des'] = $designationList;

                return response()->json($device_name);
        }

	
	public function getdesignationByRoom_checkbox(Request $request){
            
            if(isset($request->designation_id)){
              $designation_id = $request->designation_id;
            } else {
              $designation_id = 0;
            }

            $room = ConferenceRoom::where('id', $request->room_id)->select('department_id')->first();

            $designations =DesignationDepartment::with(['designation'=>function($query){
              $query->select('id','name');
            }])->where('department_id',$room->department_id)->where('parent_id', NULL)->get();

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
                }])->where('department_id',$room->department_id)->where('parent_id', $designation->designation->id)->get();
            
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
                }])->where('department_id',$room->department_id)->where('parent_id', $designation->designation->id)->get();
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
                }])->where('department_id',$room->department_id)->where('parent_id', $designation->designation->id)->get();
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
                }])->where('department_id',$room->department_id)->where('parent_id', $designation->designation->id)->get();
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
            
                $device_name['des'] = $designationList;

                return response()->json($device_name);
        }
	
}
