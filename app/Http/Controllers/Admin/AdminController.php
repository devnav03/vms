<?php

namespace App\Http\Controllers\Admin;

use App\Admin;
use App\Model\Role;
use App\Model\Department;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Request;
use Illuminate\Support\Facades\Storage;
use Image;
use App\User;
use App\Model\Location;
use URL;
class AdminController extends Controller
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
        $company_id=$cidata_ob->cid;
       

        $cidata=$request->session()->get('CIDATA');
        $cidata_ob=json_decode($cidata);
        $company_id=$cidata_ob->cid;
        if ($request->wantsJson()) {
            $datas = Admin::orderBy('created_at','desc')->select(['id','role_id','name','email','avatar','department_id','device_id','location_id'])
            ->where(['company_id'=>$company_id])
            ->with(['role'=>function($query){
                $query->select('id','name');
            },'getDepart'=>function($query){
                 $query->select('id','name');
            },'getDevice'=>function($query){
                 $query->select('id','name');
            },'getLocation'=>function($query){
                $query->select('id','name');
            }
        ]);
            $totaldata = $datas->count();
            $search = $request->search['value'];
            if ($search) {
                $datas->where('id', 'like', '%'.$search.'%')
                ->orWhere('name', 'like', '%'.$search.'%')
                ->orWhere('email', 'like', '%'.$search.'%');


            }
            # set datatable parameter
            $result["length"]= $request->length;
            $result["recordsTotal"]= $totaldata;
            $result["recordsFiltered"]= $datas->count();
            $records = $datas->limit($request->length)->offset($request->start)->get();
            # fetch table records
          
            $result['data'] = [];
                foreach ($records as $data) {
                $result['data'][] =[$data->id,'ST00'.$data->id,$data->name,$data->email,$data->role->name,@$data->getDepart->name?@$data->getDepart->name:'NA',@$data->getDevice->name?@$data->getDevice->name:'NA',@$data->getLocation->name?@$data->getLocation->name:'NA','action'];
            }
			$users=User::where('type',8)->where(['company_id'=>$company_id])->get();
			
			foreach($users as $user){
				$result['data'][] =[@$user['id'],@$user['refer_code'],@$user['name'],@$user['email'],@$user['role']['name'],'NA','NA',@$user['location']['name'],'action'];
			}
			$result["recordsTotal"]= $totaldata+count($users);
            $result["recordsFiltered"]= $totaldata+count($users);
            return $result;
        }
        return view('admin.admin.list');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
        public function create(Request $request )
        {
            $cidata=$request->session()->get('CIDATA');
            $cidata_ob=json_decode($cidata);
            $company_id=$cidata_ob->cid;
            $departments = Department::where(['company_id'=>$company_id])->get()->pluck('name','id')->toArray();
			$locations=Location::where(['company_id'=>$company_id])->get();
            $roles = Role::select(['id','name'])->get()->pluck('name','id')->toArray();
            return view('admin.admin.create',compact('roles','departments','locations'));
        }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
        public function store(Request $request) {
			$cidata=$request->session()->get('CIDATA');
			$cidata_ob=json_decode($cidata);
			$url=URL::to('/');
			$count=Admin::where('company_id',$cidata_ob->cid)->count();
			$countuser=User::where('company_id',$cidata_ob->cid)->where('type',8)->count();
			$count=$count+$countuser;
			// $res = json_decode($this->controller->packageCheck($url,'staff',$count),true);
			// if($res['status']=="failed"){
			// 	return back()->with(['message'=>$res['msg'], 'class'=>'error']);
			// }
			$this->validate($request,[
                'name'=>'required',
                //'image' => 'nullable|image|mimes:jpeg,png,jpg|max:1024',
                'role'=>'required',
                'email'=>'required|email' ,
                'location_id'=>'required' ,
                'building_id'=>'required' ,
                'password'=>'required|min:6',
                'employee_type'=>'required'
                // 'device_id'=>'required'
            ]);
            if($request->role==8){
				$user =new User();
				$user->name = @$request->name;
				$user->email = @$request->email;
				$user->mobile = @$request->mobile;
				$user->address_1 = @$request->address;
				$user->type = 8;
				$user->gender = @$request->gender;
				$user->password = bcrypt($request->password);
				$user->save();
				$cidata=$request->session()->get('CIDATA');
                $cidata_array=json_decode($cidata);
                $user->company_id = $cidata_array->cid;
				$user->refer_code = 'ST00'.$user->id;
                $user->department_id = @$request->department;
                $user->location_id = @$request->location_id;
                $user->building_id = @$request->building_id;
                $user->save();
                return redirect()->route('admin.'.request()->segment(2).'.index')->with(['class'=>'success','message'=>'Guard Created successfully.']);
			}
            $admin = new Admin();

            if ($request->file('image')) {
                $image= Storage::disk('local')->put('admin/images/profile',$request->file('image'));
                $admin->avatar = $request->image->hashName();
            }

            $admin->role_id = @$request->role;
            $admin->department_id = @$request->department;
            $admin->location_id = @$request->location_id;
      		$admin->building_id = @$request->building_id;
            $admin->device_id = @$request->device_id;
            $admin->name = @$request->name;
            $admin->email = @$request->email;
            $admin->mobile = @$request->mobile;
            $admin->address = @$request->address;
            $admin->gender = @$request->gender;
            $admin->password = bcrypt($request->password);
            $admin->ip = @$request->ip;
            $admin->allowed_ip = @$request->allowip;
            $admin->status_id = @$request->status_id;
            if($request->employee_type=="permanent"){
                $admin->employee_type = @$request->employee_type;
                $admin->from_date = '';
                $admin->till_date = '';
            }else{
              $admin->employee_type = @$request->employee_type;
              $admin->from_date = @$request->employee_from_date;
              $admin->till_date = @$request->employee_till_date;
            }

            if($admin->save()){
              if($request->session()->has('CIDATA')){
                $cidata=$request->session()->get('CIDATA');
                $cidata_array=json_decode($cidata);
                $admin->company_id = $cidata_array->cid;
                $admin->save();
                return redirect()->route('admin.'.request()->segment(2).'.index')->with(['class'=>'success','message'=>'Admin Created successfully.']);
              }else{
                return redirect()->back()->with(['class'=>'error','message'=>'Whoops, looks like something went wrong ! Try again ...']);
              }


            }

            return redirect()->back()->with(['class'=>'error','message'=>'Whoops, looks like something went wrong ! Try again ...']);
        }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $user_id = auth('admin')->user()->id;
         $admin = Admin::where('id',$user_id)->with(['role'=>function($query){
             $query->select('id','name');
         }])->first();
        return view('admin.admin.view',compact('admin'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request,$id)
    {
		
            $admin = Admin::where('id',$id)->first();
        
            if(empty($admin)){
                $admin = User::where('id',$id)->first();
            }
  
   
			$cidata=$request->session()->get('CIDATA');
			$cidata_ob=json_decode($cidata);
			$company_id=$cidata_ob->cid;
			$departments = Department::where(['company_id'=>$company_id])->get()->pluck('name','id')->toArray();
			$roles = Role::select('id','name')->get()->pluck('name','id')->toArray();
			$locations=Location::where(['company_id'=>$company_id])->get();
                
        
        return view('admin.admin.edit', compact('admin','roles','departments','locations'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
   
        
      if($request->role ==8){
            $user = User::where('id',$id)->first();
            $user->name = @$request->name;
            $user->email = @$request->email;
            $user->mobile = @$request->mobile;
            $user->address_1 = @$request->address;
            $user->type = 8;
            $user->gender = @$request->gender;
            if($request->password)
            {
                $user->password = bcrypt($request->password);;
            }            
            $user->department_id = @$request->department;
            $user->location_id = @$request->location_id;
            $user->building_id = @$request->building_id;
            $user->save();
            if($user->save()){
           
                return redirect()->route('admin.'.request()->segment(2).'.index')->with(['class'=>'success','message'=>'Guard Updated successfully.']);
            }
      
      }else{
        $admin = Admin::where('id',$id)->first();
        if ($request->file('image')) {
            $image= Storage::disk('local')->put('admin/images/profile',$request->file('image'));
            $admin->avatar = $request->image->hashName();
        }
     
        if($request->password)
        {
            $admin->password = bcrypt($request->password);
        }
        $admin->role_id = @$request->role;
    		$admin->location_id = @$request->location_id;
    		$admin->building_id = @$request->building_id;
        $admin->device_id = @$request->device_id;
        $admin->name = @$request->name;
        $admin->email = @$request->email;
        $admin->mobile = @$request->mobile;
        $admin->address = @$request->address;
        $admin->gender = @$request->gender;
        $admin->allowed_ip = @$request->allowip;
        $admin->department_id = @$request->department;
        $admin->ip =@ $request->ip;
        $admin->status_id = @$request->status_id;
        if($request->employee_type=="permanent"){
            $admin->employee_type = @$request->employee_type;
            $admin->from_date = '';
            $admin->till_date = '';
        }else{
          $admin->employee_type = @$request->employee_type;
          $admin->from_date = @$request->employee_from_date;
          $admin->till_date = @$request->employee_till_date;
        }
        if($admin->save()){
           
            return redirect()->route('admin.'.request()->segment(2).'.index')->with(['class'=>'success','message'=>'Updated successfully.']);
        }
      }     
        
        
        return redirect()->back()->with(['class'=>'error','message'=>'Whoops, looks like something went wrong ! Try again ...']);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Admin $admin)
    {
          if($admin->delete())
          {
            return response()->json(['message'=>'Admin deleted successfully ...', 'class'=>'success']);
        }
        return response()->json(['message'=>'Whoops, looks like something went wrong ! Try again ...', 'class'=>'error']);
    }


}
