<?php
namespace App\Http\Controllers\Admin;

use Excel;
use DB;
use App\User;
use App\Admin;
use Carbon\Carbon;
use App\Http\Requests\Admin\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Model\UserDetail;
use App\Model\Transaction;
use App\Model\Symptom;
use App\Model\AllVisit;
use App\Model\PanicAlert;
use App\Model\Panic;
use App\Events\SmsEvent;
use Illuminate\Support\Facades\Redirect;
use Mail;
use App\Events\OtpEvent;
use App\Model\VisitorGroup;
use App\Model\Department;
use App\Model\Country;
use App\Model\Location;
use Illuminate\Support\Facades\Crypt;
use App\Model\Building;
use App\Model\Device;
use App\Model\Setting;
use URL;
use Symfony\Component\Process\Process;
class UserController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     *
     */
    public function __construct()
    {
        set_time_limit(8000000);
    }
    
   public function index(Request $request)
    {
        // 1,4 admin and developer
        // 5,6 recepton, officer
         $user_id =  auth('admin')->user()->id;
         $user_role =  auth('admin')->user()->role_id;
        
        if($request->ajax()){

            if($user_role ==1){
                $datas = User::orderBy('id','desc')->select('id','name','mobile','email', 'status', 'image',  'services', 'refer_code','added_by','created_at','officer_id','app_status','attachmant')->with(['parentDetail'=>function($query){
                    $query->select('id','name','role_id');
                }])->where(['type'=>'Visitor']);
            }
            if($user_role ==4){
                $datas = User::orderBy('id','desc')->select('id','name','mobile','email', 'status', 'image',  'services',  'refer_code','added_by','created_at','officer_id','app_status')->with(['parentDetail'=>function($query){
                    $query->select('id','name', 'role_id');
                }])->where(['type'=>'Visitor']);
            }

            if($user_role == 5){
                $datas = User::orderBy('id','desc')->select('id','name','mobile','email', 'status', 'image',  'services',  'refer_code','added_by','created_at','officer_id','app_status','attachmant')->with(['parentDetail'=>function($query){
                    $query->select('id','name', 'role_id');
                }])->where(['added_by'=>$user_id,'type'=>'Visitor']);
            }

            if($user_role == 6){
                $datas = User::orderBy('id','desc')->select('id','name','mobile','email', 'status', 'image',  'services',  'refer_code','added_by','created_at','officer_id','app_status','attachmant')->with(['parentDetail'=>function($query){
                    $query->select('id','name', 'role_id');
                }])->where(['officer_id'=>$user_id,'type'=>'Visitor']);
            }

            if($request->dateFrom && $request->dateTo){
                $datas->whereBetween('created_at', [$request->dateFrom.' 00:00:00', $request->dateTo.' 23:59:59']);
            }
            $cidata=$request->session()->get('CIDATA');
        		$cidata_ob=json_decode($cidata);
        		$datas->where($cidata_ob->where,$cidata_ob->cid);
            $totaldata = $datas->count();
            $search = $request->search['value'];
            if($search) {
                $datas->where('id', 'like', '%'.$search.'%')
                ->orWhere('name', 'like', '%'.$search.'%')
                ->orWhere('mobile', 'like', '%'.$search.'%')
                ->orWhere('refer_code', 'like', '%'.$search.'%');
            }

            # set datatable parameter

            $result["length"]= $request->length;

            $result["recordsTotal"]= $totaldata;

            $result["recordsFiltered"]= $datas->count();

            // $datas->orderBy('id','desc');

            $records = $datas->limit($request->length)->offset($request->start)->get();

            # fetch table records

            $result['data'] = [];

            foreach ($records as $data) {
                if($data->status=='0'){
                  $stat = '<span style="color:red;">Pending</span>';
                }
                if($data->status=='1'){
                  $stat = '<span style="color:green;">Approve</span>';
                }
                if($data->status=='2'){
                  $stat = '<span style="color:red;">Preinvite</span>';
                }
                if($data->status=='3'){
                  $stat = '<span style="color:red;">Block</span>';
                }
                
                if($data->status=='5'){
                  $stat = '<span style="color:red;">Rejected</span>';
                }
        				if($data->added_by==''){
                    $added_by= 'Self';
                  }else{
        					  $added_by=@$data->parentDetail->name;
        				}
                $use_slip = Crypt::encryptString($data->id);
                $user_id_slip = url("generate-slip/" .$use_slip);
                // echo str_replace("/public","",URL::to("/")).'/storage/app/public/'.@$data->image;
              $result['data'][] =['sn'=>@$data->id,'name'=>@$data->name, 'image'=>str_replace("","",URL::to("/")).'/uploads/img/'.str_replace(" ","%20",$data->image), 'attachmant'=>str_replace("/public","",URL::to("/")).'/storage/app/public/'.@$data->attachmant, 'email'=>@$data->email, 'mobile'=>@$data->mobile,'id'=>@$data->id, 'refer_code'=>'#'.@$data->refer_code, 'edit'=>'Edit', 'status'=>$stat, 'Appoint_status'=>@$data->app_status, 'parent_detail'=>$added_by, 'services'=>@$data->services,'appo_status'=>@$data->status,'slip_id'=>$user_id_slip];
            }
			
            return $result;
        }
        return view('admin.user.list');
    }



    public function userLogin($id){
        $user = User::find($id);
        if($user)
        {
            Auth::login($user);
            return response()->json(['error'=>false, 'msg'=>'User Logged In']);
        }
        return response()->json(['error'=>true, 'msg'=>'Oops! Something went wrong']);
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
        $symptoms = Symptom::where('status',1)->get();
        $get_officers = Admin::where(['role_id'=>6,'status_id'=>1])->get();
		$get_country = Country::all();
		$locations=Location::where('company_id',$cidata_ob->cid)->get();
        return view('admin.user.create',compact('get_officers','symptoms','get_country','locations'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response
     *
     */



    public function store(Request $request)
    {
       //dd($request->all());

		  $this->validate($request, [
            'name'=>'required',
            'mobile'=>'required',
            'email'=>'required|email',
            'visite_time'=>'required',
            'services'=>'required',
            'gender'=>'required',
            'image'=>'required',
            'officer'=>'required',
            'location_id'=>'required',
            'building_id'=>'required',
            'department_id'=>'required',
            'document_type'=>'required',
            'organization_name'=>'required',	
		]);
		if($request->image_document_mode=="camera"){
			$attachmant_base = $request->attachmant;
			$file_name_doc = 'attach_' . time() . '.png'; //generating unique file name;
			@list($type, $attachmant_base) = explode(';', $attachmant_base);
			@list(, $attachmant_base) = explode(',', $attachmant_base);
			if ($attachmant_base != "") {
				$attachmant_base=$attachmant_base;
			   $attachmant_base = $attachmant_base;
			  \Storage::disk('public')->put($file_name_doc, base64_decode($attachmant_base));
			  $attachments=$file_name_doc;
			}
    }else{
			$imagePath = $request['attachmant'];
			$milliseconds = round(microtime(true) * 1000);
			$file_name_doc = $milliseconds.$imagePath->getClientOriginalName();

			$attachments = $request['attachmant']->storeAs('uploads', $file_name_doc, 'public');
			$request->request->add(['attchment_'.$request->mobile => $attachments]);
		}
        if($request->visit_type =='group'){

          foreach($request->group_attchment as $key => $data_gruop_attachment){
            $imagePath = $data_gruop_attachment['attchment'];
            $milliseconds = round(microtime(true) * 1000);
            $imageName = $milliseconds.$imagePath->getClientOriginalName();
            $path = $data_gruop_attachment['attchment']->storeAs('uploads', $imageName, 'public');
            $request->request->add(['attchment_'.$request->group_mobile[$key]['group_mobile'] => $path]);
          }
		  foreach ($request->group_image_mode as $key => $data_group_image_mode) {
			  $newkey = $key - 1;
			  if ($data_group_image_mode[0] == "folder") {
				if (!empty($request->group_image[$newkey])) {
				  $imagePath = $request->group_image[$newkey];
				  $milliseconds = round(microtime(true) * 1000);
				  $imageName = $milliseconds . $imagePath->getClientOriginalName();
				  $path = $request->group_image[$newkey]->storeAs('uploads', $imageName, 'public');
				  $request->request->add(['group_image_folder_' . $newkey => $path]);
				}
			  }
          }
        }
		if ($request->image_mode == "folder") {
			$imagePath = $request['image'];
			$milliseconds = round(microtime(true) * 1000);
			$file_name = $milliseconds . $imagePath->getClientOriginalName();
			$path = $request['image']->storeAs('uploads', $file_name, 'public');
			$request->request->add(['image_f' => $path]);
			$file_name='uploads/'.$file_name;
		}else{
			$img = $request->image;
			$file_data = $request->image;
			$file_name = 'image_'.time().'.png'; //generating unique file name;
			@list($type, $file_data) = explode(';', $file_data);
			@list(, $file_data) = explode(',', $file_data);
			if($file_data!="")
			{
				// storing image in storage/app/public Folder
				$image_data = $file_data;
				  \Storage::disk('public')->put($file_name,base64_decode($file_data));
			}
		}
        
        // echo "Yes";die;
        $user_id =  auth('admin')->user()->id;
        $store_user = new User;
        $store_user->name = @$request->name;
        $store_user->email = @$request->email;
        $store_user->mobile = @$request->mobile;
        $store_user->adhar_no = @$request->adhar_no;
        $store_user->services = @$request->services;
        $store_user->gender = @$request->gender?$request->gender:'Male';
        $store_user->visite_time = @$request->visite_time;
        $store_user->status = @$request->status;
        $store_user->app_status ='Pending';
        $store_user->image = @$file_name;
        $store_user->officer_id = @$request->officer;
		$store_user->document_type = @$request->document_type;
		$store_user->location_id = @$request->location_id;
		$store_user->building_id = @$request->building_id;
		$store_user->department_id = @$request->department_id;
        $store_user->image_base = @$image_data;
        $store_user->added_by = @$user_id;
        $store_user->vaccine = @$request->vaccine;
        $store_user->vaccine_name = @$request->vaccine_name;
        $store_user->vaccine_count = @$request->vaccine_count;
			$store_user->country_id = @$request->country_id;
			$store_user->state_id = @$request->state_id;
			$store_user->city_id = @$request->city_id;
			$store_user->pincode = @$request->pincode;
			$store_user->address_1 = @$request->address_1;
			$store_user->address_2 = @$request->address_2;
			$store_user->country_id = @$request->country_id;
			$store_user->organization_name = @$request->organization_name;
        $store_user->orga_country_id = @$request->orga_country_id;
    		$store_user->orga_state_id = @$request->orga_state_id;
    		$store_user->orga_city_id = @$request->orga_city_id;
    		$store_user->orga_pincode = @$request->orga_pincode;
        $store_user->symptoms = @$request->symptoms;
        $store_user->travelled_states = @$request->states;
        $store_user->patient = @$request->patient;
        $store_user->temprature = @$request->temprature;
        $store_user->carrying_device = @$request->carrying_device;
        $store_user->pan_drive = @$request->pan_drive;
        $store_user->hard_disk = @$request->hard_disk;
		$store_user->attachmant = @$attachments; 
      	$store_user->attachmant_base = @$attachmant_base;
        $cidata=$request->session()->get('CIDATA');
    		$cidata_ob=json_decode($cidata);
        $column=$cidata_ob->where;
    		$store_user->$column=$cidata_ob->cid;
        $store_user->save();
        if(count($request->assets_name)>0){
            $store_user->assets_name = implode(",",$request->assets_name);
            $store_user->assets_number = implode(",",$request->assets_number);
            $store_user->assets_brand = implode(",",$request->assets_brand);
        }

        $store_user->visit_type = @$request->visit_type;
        if($request->visit_type =='group'){
            foreach($request->group_name as $key => $data_gruop_name){
                $img = $request->group_image[$key-1];
                $file_data_group = $request->group_image[$key-1];
                $file_names = 'group_image_'.time().'.png'; //generating unique file name;
                @list($type, $file_data_group) = explode(';', $file_data_group);
                @list(, $file_data_group) = explode(',', $file_data_group);
                if($file_data_group!="")
                {
                    $image_datas = $file_data_group;
                    \Storage::disk('public')->put($file_names,base64_decode($file_data_group));
                }

                $attachments="attchment_".$request->mobile;
                // echo $key;
                // dd($request);
                $group_add = new VisitorGroup;
                $group_add->user_id = $store_user->id;
                $group_add->group_name = $request->group_name[$key]['group_name'];
                $group_add->group_mobile = $request->group_mobile[$key]['group_mobile'];
                $group_add->group_id_proof = $request->group_id_proof[$key]['group_id_proof'];
                $group_add->group_gender = $request->group_gender[$key]['group_gender'];
                $group_add->group_image = $file_names;
                $group_add->image_base = $image_datas;
                $group_add->group_attchment =$request->$attachments;
                $group_add->save();
                $group_add->visitor_id = "VG00".$group_add->id;
                $group_add->save();
            }
        }
        $store_user->save();
        $add_visit = new AllVisit;
        $add_visit->user_id = $store_user->id;
        $add_visit->date_time =  $store_user->visite_time;
        $add_visit->officer_id =  $store_user->officer_id;
        $add_visit->added_by =  $store_user->added_by;
        $add_visit->save();
        if($store_user->save())
        {
            $store_user->refer_code = "VS00".$store_user->id;
            $response=$store_user->save();
            $all_data = $request->all();
            if($response==1){
                $add_status['status_code']=201;
                $add_status['message']="Visitor Successfully Registered";
            }else{
                $add_status['status_code']=400;
                $add_status['message']="Visitor Registration Failed";
            }
            $cidata=$request->session()->get('CIDATA');
        		$cidata_ob=json_decode($cidata);
            $settings=Setting::where([$cidata_ob->where=>$cidata_ob->cid,'name'=>'ams_send'])->first();
            if(@$settings->status=="Active"){
                $add_status = $this->sendFaceCheck($all_data,$store_user->refer_code,$image_data);
            }

            // dd($add_status);
            if($add_status['status_code'] == '201')
            {
                $this->sendEmail($store_user->id);
                $store_user->employee_unique_id = $add_status['status_code'] ;
                $store_user->save();
                $encrypted = Crypt::encryptString($store_user->id);
				//echo $encrypted;die;
                return redirect()->route('generate.slip',$encrypted);
                //return back()->with(['message'=>'Visitor Registered Successfully', 'class'=>'success']);
            }
            else
            {
                $store_user->status= 0;
                $store_user->save();
                return back()->with(['message'=>$add_status['message'], 'class'=>'error'])->withInput($request->all());
            }
        }
        else
        {
          // echo "Error";die;
              return back()->with(['message'=>'Oops! Something went wrong', 'class'=>'error']);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\Response
     *
     */



    public function show(Request $request,User $user)
    {
        if ($request->type=='login'){
           auth('user')->login($user);
           return Redirect::route('web.user.dashboard');
        }
        if($request->order_id){
            $order_id= $request->order_id;
            $cart= CartData::where('order_id',$order_id)->get();
            return response()->json($cart);
        }
        $cartorder= Order::where('user_id',$user->id)->whereHas('cartData',function($query){
          $query->has('product');
        })->first();
        $order= Order::where('user_id',$user->id)->get();
        $address= ShippingAddress::where('user_id',$user->id)->get();
        return view('admin.user.view',compact('user','order','address'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\Response
     */

    public function edit(Request $request, User $user)
    {
       ini_set('max_execution_time', '8000');
        $cidata=$request->session()->get('CIDATA');
        $cidata_ob=json_decode($cidata);
        // $symptoms = Symptom::where('status',1)->get();
        $get_officers = Admin::where(['role_id'=>6,'status_id'=>1,$cidata_ob->where=>$cidata_ob->cid])->get();
        // $visiter_list=VisitorGroup::where(['user_id'=>$user->id])->get();
        $get_depart = Department::where(['status'=>1,$cidata_ob->where=>$cidata_ob->cid])->get()->toarray();	
		//dd($user);
        return view('admin.user.edit',compact('user','get_officers','get_depart'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\Response
     */




    public function update(Request $request,$user){
       
        
      $data = User::where('id', $user)->first();
  		if($request->types=="only_status"){
  			User::find($request->id)->update(['status'=>$request->status,'update_by'=>auth('admin')->user()->id]);
  			return response()->json(['message'=>'Status changed successfully ...', 'class'=>'success']);
  		}

        if($request->types=='status') {
            $data =User::find($user);
                Auth::logout($user);
            if ($data->status==1) {
                $data->status = 0;
            }else{
                $data->status = 1;
            }
            if($data->save()) {
                return response()->json(['message'=>'Status changed successfully ...', 'class'=>'success']);
            }
        }

        if($request->type == 'update_user'){

            $this->validate($request, [
                'name'=>'required',
                'adhar_no'=>'required',
                'mobile'=>'required',
                'email'=>'required|email',
                'services'=>'required',
                'gender'=>'required',
                'officer'=>'required',
            ]);

            $user_id =  auth('admin')->user()->id;

         
            $store_user = User::find($user);
            $store_user->name = $request->name;
            $store_user->email = $request->email;
            $store_user->mobile = $request->mobile;
            $store_user->visite_time = $request->visite_time;
            $store_user->adhar_no = $request->adhar_no;
            $store_user->services = $request->services;
			$store_user->update_by=auth('admin')->user()->id;
          
            // if(auth('admin')->user()->role_id ==6){

                $store_user->app_status = $request->app_status;

                if($request->status == 1){
                  $cidata=$request->session()->get('CIDATA');
                  $cidata_ob=json_decode($cidata);
                  $settings=Setting::where([$cidata_ob->where=>$cidata_ob->cid,'name'=>'ams_send'])->first();
                    if($data->visit_type=="group"){
            					$visiter_list=VisitorGroup::where('user_id',$user)->get();
            					foreach($visiter_list as $visiter){
            							// 26-07-2021 Reason Ams NOt Required this Time
                          if(@$settings->status=="Active"){
                              $res=$this->sendFaceCheckAlotte($visiter->visitor_id);
                          }
            					}
            				}
                    // 26-07-2021 Reason Ams NOt Required this Time
					       // $res=$this->sendFaceCheckAlotte($store_user->refer_code);
				// 		 if(@$settings->status=="Active"){
							 $res=$this->sendFaceCheckAlotte($store_user->refer_code);
							// dd($res);
				// 		 }
					   }
            // }
            $store_user->gender = $request->gender;
            $store_user->status = $request->status;
            $store_user->added_by = $user_id ;
            $store_user->officer_id = $request->officer;
            $store_user->vaccine = @$request->vaccine;
            $store_user->vaccine_name = @$request->vaccine_name;
            $store_user->vaccine_count = @$request->vaccine_count;
            $store_user->symptoms = @$request->symptoms;
            $store_user->travelled_states = @$request->travelled_states;
            $store_user->patient = @$request->patient;
            $store_user->temprature = @$request->temprature;

            if($store_user->save()){
                  $this->sendApproveEmail($store_user->id);
				
					return back()->with(['message'=>'Visitor Update Successfully', 'class'=>'success']);
				
              
            }else{
                  return back()->with(['message'=>'Oops! Something went wrong', 'class'=>'error']);
            }
        }
    }


   /**
        * Remove the specified resource from storage.
        *
        * @param  int  $id
        *
        * @return \Illuminate\Http\Response
        *
    */

    public function destroy(Request $request,User $user)
    {
        $id = $user->id;
        if($user->delete()){
          $this->deleteUser(@$user->refer_code);
            return response()->json(['message'=>'User deleted successfully ...', 'class'=>'success']);
        }
        return response()->json(['message'=>'Whoops, looks like something went wrong ! Try again ...', 'class'=>'error']);
    }

    function deleteUser($employee_id){
      $company_data = $this->company_data;
      $settings=Setting::where([$cidata_ob->where=>$company_data['id'],'name'=>'ams_send'])->first();
      if(@$settings->status!="Active"){
        return;
      }
  		$post_data=array('employee_id'=>$employee_id);
          $curl = curl_init();
          curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://ams.facer.in/api/public/employee/delete',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>json_encode($post_data),
            CURLOPT_HTTPHEADER => array(
              'Authorization: bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyX25hbWUiOiJzYW10ZWNoLmFkbWluIiwidHlwZV9vZl91c2VyIjoiQURNSU4iLCJ0b2tlbiI6IiQyYSQwOCQ5SUY1UFV6cGh0bWVRTzJtVWtOdU8ucnU5VUhFaGc4OEtxM3QzMVVxR0VvR2NZR3BnU0VDVyIsImlhdCI6MTY0NTE2MDIzMX0.KGAyVEivIQ6Fncg8JPmlwNZfkBwNcPaTJCNj5wKruR8',
              'Content-Type: text/plain'
            ),
          ));
          $response = curl_exec($curl);
          curl_close($curl);
          return json_decode($response,true);
      }

    public function panicAleart($id){
        $user_details = User::Where(['id'=>$id])->first();
		    $admin_detail = Admin::Where('id',1)->first();
        $officer_details = Admin::Where(['id'=>$user_details->officer_id])->with(['getDepart'=>function($query){
                 $query->select('id','name');
            }])->first();
    		$all_emails=[];
    		$all_mobiles=[];
        if(!empty($user_details->added_by)){
      			$reception_details = Admin::Where(['id'=>$user_details->added_by])->first();

      			$all_emails =[$admin_detail->email,$reception_details->email];
      			$all_mobiles = [$admin_detail->mobile,$reception_details->mobile];
    		}


       $panic_data = new Panic;
       $panic_data->name = $officer_details->name;
       $panic_data->officer_id = $officer_details->id;
       $panic_data->visitor_id = $id;
       $panic_data->save();

        $all_emergency_lists = PanicAlert::where('officer_id',$user_details->officer_id)->get();
        if(count($all_emergency_lists)>0){
            foreach($all_emergency_lists as $emr){
                array_push($all_emails,$emr->email);
                array_push($all_mobiles,$emr->mobile);
            }
        }
		foreach($all_mobiles as $mobile){
			//Sms Off By Suresh Sir For Testin Purpose
            // event(new OtpEvent($mobile, 'Panic Alert : '.$officer_details->name.' ( '.$officer_details->mobile.') from ( '.$officer_details->getDepart->name.' )has initiated a panic alert. Please respond quickly.'));
        }

    //   return $all_emails;
        foreach($all_emails as $email){
            $name = $officer_details->name;
            $offi_mobile = $officer_details->mobile;
            $depart = $officer_details->getDepart->name;
            // send mail to patient
             $data=['officer_name'=>$name,'officer_mobile'=>$offi_mobile,'officer_dep'=>$depart];
          //Mail::send('mails.panic-alert', $data, function($message) use ($email){
					  // $sub = Carbon::now()->toDateTimeString();
					  // $message->subject('Panic Alert ('.$sub.')');
					  // $message->from('noreply@sspl20.com','Panic Alert  ('.$sub.')');
					  // $message->to($email, 'Panic Alert ('.$sub.')');
               //});
        }

         return response()->json(['message'=>'Your message send successfully ...', 'class'=>'success']);
    }

    public function sendEmail($user_id){
        $new_user_id = $user_id;

        $user_details = User::Where(['id'=>$new_user_id])->first();
        $reception_details = Admin::Where(['id'=>$user_details->added_by])->first();
        $officer_details = Admin::Where(['id'=>$user_details->officer_id])->first();

        $user_email = $officer_details->email;
        $reception_name = $reception_details->name;
        $sub = $user_details->visite_time;
        $appoint_date = date('d/m/Y', strtotime($user_details->visite_time));
        $appoint_time = date('h:i:s a', strtotime($user_details->visite_time));
        if($user_details->app_status=="Pending"){
          $app_status='Scheduled';
        }else{
          $app_status=$user_details->app_status;
        }
        $encrypted = Crypt::encryptString($new_user_id);
        
        $url=route('visitor.approve').'/'.$encrypted.'/'.@$officer_details->id;
        $res = json_decode($this->createShortLink($url));
      
        //  event(new SmsEvent($officer_details->mobile, 'Dear Sir/Madam,A new meeting has been scheduled on '.$appoint_date.' & '.$appoint_time.' for '.$user_details->visite_duration.' Minutes. Please check and approve the same. THANKS'));
        event(new SmsEvent($officer_details->mobile, 'Hi, You have a new meeting with '.@$user_details->name.' (Mob:'.@$user_details->mobile.') on '.$appoint_date.' at '.$appoint_time.'. Click here to Approve:- ('.$res->link.'). Thanks VMS Team'));
         // event(new SmsEvent($user_details->mobile, 'Dear visitor, '.$reception_details->name.' has '.$app_status.' a new visitor meeting on '.$appoint_date.' at '.$appoint_time));
        // send mail to patient
        $user_name = $user_details->name;
        $user_mobile = $user_details->mobile;
        
        $data=['vis_name' =>$user_name,'user_mobile' =>$user_mobile,'visitor_name'=>$reception_name,'app_date'=>$appoint_date,'appoint_time'=>$appoint_time,
			   'encryptString'=>$encrypted,'status'=>$app_status,'visitor_id'=>$user_details->refer_code,'duration'=>$user_details->visite_duration,'officer_id'=>$officer_details->id];
              Mail::send('mails.appointment-invoice', $data, function($message) use ($user_email){
                $sub = Carbon::now()->toDateTimeString();;

                $message->subject('New Appointment Alert ('.$sub.')');
                $message->from('noreply@vztor.in','New Appointment Alert  ('.$sub.')');
                $message->to($user_email, 'New Appointment Alert ('.$sub.')');
            });

        // Send mail to admin

    }

     public function sendApproveEmail($user_id){
        $new_user_id = $user_id;

        $user_details = User::Where(['id'=>$new_user_id])->first();
        $reception_details = Admin::Where(['id'=>$user_details->added_by])->first();
        $officer_details = Admin::Where(['id'=>$user_details->officer_id])->first();

        $user_email = $user_details->email;
        $reception_name = $reception_details->name;
        $sub = $user_details->visite_time;
        $appoint_date = date('d/m/Y', strtotime($user_details->visite_time));
        $appoint_time = date('h:i:s a', strtotime($user_details->visite_time));
		$m_time = $user_details->visite_time;
        if($user_details->app_status=="Pending"){
          $app_status='RESCHEDULED';
        }else{
          $app_status=$user_details->app_status;
        }
         //event(new SmsEvent($officer_details->mobile, 'Dear Sir, '.$reception_details->name.' has '.$app_status.' a new visitor meeting on '.$appoint_date.' at '.$appoint_time.'.Kindly login to see the details.'));
         event(new SmsEvent($user_details->mobile, 'Dear Visitor,
         Your meeting (#'.$user_details->refer_code.') with '.$officer_details->name.' has been '.$app_status.' for '.$m_time.'.
         Thanks
          VMS Team
         '));

        //  event(new SmsEvent($user_details->mobile, 'Dear Visitor, our meeting ('.$user_details->refer_code.') schedule has been approved for '.$appoint_date.' & '.$appoint_time.' for '.$user_details->visite_duration.' Minutes. Please visit timely Thanks'));

        $user_name = ucfirst($user_details->name);
      $user_mobile = $user_details->mobile;
        $encrypted = Crypt::encryptString($new_user_id);
         $data=['vis_name' =>$user_name,'user_mobile' =>$user_mobile,'visitor_name'=>$reception_name, 'm_time' =>$m_time, 'app_date'=>$appoint_date, 'appoint_time'=>$appoint_time,
				'encryptString'=>$encrypted,'status'=>$app_status,
				'duration'=>$user_details->visite_duration,'visitor_id'=>$user_details->refer_code];
              Mail::send('mails.appointment-approved-invoice', $data, function($message) use ($user_email){
                $sub = Carbon::now()->toDateTimeString();;

                $message->subject('Appointment Approval ('.$sub.')');
                $message->from('noreply@vztor.in','Appointment Approval  ('.$sub.')');
                $message->to($user_email, 'Appointment Approval ('.$sub.')');
            });

        // Send mail to admin

    }


    function sendFaceCheck($all_data,$visitor_id,$image_data){

        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://ams.facer.in/api/public/employee/add',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>'{
          "office_name": "H1",
          "department_name": "Visitor",
          "shift_name": "Morning",
          "employee_name": "'.$all_data['name'].'",
          "employee_id": "'.$visitor_id.'",
          "employee_gender": "Male",
          "employee_image": "'.$image_data.'",
          "employee_email": "'.$all_data['email'].'",
          "employee_contact_number": "'.$all_data['mobile'].'",
          "contract_type": "PERMANENT",
          "overtime": "30",
          "status": "ACTIVE",
          "date": "'.$all_data['visite_time'].'"
        }',
          CURLOPT_HTTPHEADER => array(
            'Authorization: bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyX25hbWUiOiJzaGFpbGVycy5hZG1pbiIsInR5cGVfb2ZfdXNlciI6IkFETUlOIiwidG9rZW4iOiIkMmEkMDgkcUpCY3ROT1hyNnBzbFlMOUxWaDR6T3NQUi8xdGVDSWhrR1NNdmFjMUtvNTFvcHdYU0JqTEMiLCJpYXQiOjE2NjE0MzE4NDZ9.7y_5HfIPOpEHxrgJHhF0FH9Yj0NJW_-aKB6gnMvokSU',
            'Content-Type: text/plain'
          ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);


        return json_decode($response,true);
    }


    function sendFaceCheckAlotte($employee_id){
         $devices=$this->getDeviceAllocateUser($employee_id);
        
        $devices_name=json_encode($devices);
        
        // $employee_id = 'vs002';
 
		//echo $devices_name;die;
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://ams.facer.in/api/public/employee/allot',
           CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
        //  CURLOPT_POSTFIELDS =>'{
        //     "employee_id": "'.$employee_id.'",
        //     "allotments": [{
        //             "device_name": "Facechk 8inch BE:90",
        //             "office_name": "Head Office"
        //           },{
        //             "device_name": "Infinix",
        //             "office_name": "Head Office"
        //           }]
        //   }',
           CURLOPT_POSTFIELDS =>'{
           "employee_id": "'.$employee_id.'",
           "allotments": '.$devices_name.'
         }',
         
        
         

          CURLOPT_HTTPHEADER => array(
            //'Authorization: bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyX25hbWUiOiJzYW10ZWNoLmFkbWluIiwidHlwZV9vZl91c2VyIjoiQURNSU4iLCJ0b2tlbiI6IiQyYSQwOCQ5SUY1UFV6cGh0bWVRTzJtVWtOdU8ucnU5VUhFaGc4OEtxM3QzMVVxR0VvR2NZR3BnU0VDVyIsImlhdCI6MTYxODQ3NTI3NX0.mXvnJi_I-8j2eNJJBkHzLBzVp766BdS-Rpk95BHz7RI',
            'Authorization: bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyX25hbWUiOiJzaGFpbGVycy5hZG1pbiIsInR5cGVfb2ZfdXNlciI6IkFETUlOIiwidG9rZW4iOiIkMmEkMDgkcUpCY3ROT1hyNnBzbFlMOUxWaDR6T3NQUi8xdGVDSWhrR1NNdmFjMUtvNTFvcHdYU0JqTEMiLCJpYXQiOjE2NjE0OTYwMjF9.bPkdDzSkoQ0CwmHPUXrJlSSPVrNv3QEh4BO1O1vmGVI',
            'Content-Type: text/plain'
          ),
        ));
        
       // dd($curl);
       
       // dd($devices_name);
        
        $response = curl_exec($curl);
        
       // dd($response);
        curl_close($curl);


        return json_decode($response,true);
    }
    public function getDeviceAllocateUser($user_id){
      $users=User::where('refer_code',$user_id)->first();
      // $officer_details=Admin::where('id',$users->officer_id)->first();
    //  $buildings=Building::where('id',$users->building_id)->first();
     $department_data=Department::where('id',$users->department_id)->with(['getDeviceDepartment'=>function($q){
        $q->select('id','department_id','device_id')->with(['device'=>function($q){
          $q->select('id','name','office_name');
        }]);        
     }])->first();

     $device_details=[];
     if(!empty($department_data->getDeviceDepartment)){
       foreach($department_data->getDeviceDepartment as $key => $getdeviceinfo){
        $device_details[$key]['device_name'] = $getdeviceinfo->device->name;
        $device_details[$key]['office_name'] = $getdeviceinfo->device->office_name;
       }
     }

    //  dd( $device_details);
      // $devices=Device::where('id',$department_data->getSingleDeviceDepartment->device_id)->first();
      // $device_details[]=array('device_name'=>@$devices->name,'office_name'=>@$devices->office_name);
      
      return $device_details;
    }


	
    public function createShortLink($url){		
      $curl = curl_init();
      curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api-ssl.bitly.com/v4/shorten',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'{
          "group_guid": "",
          "domain": "bit.ly",
          "long_url": "'.$url.'"
        }',
        CURLOPT_HTTPHEADER => array(
          'Content-Type: application/json',
          'Authorization: Bearer 468b36085fa039c29b1398247acf97ea0f62d559'
        ),
      ));
  
      $response = curl_exec($curl);
      curl_close($curl);
      return $response;
    }



}
