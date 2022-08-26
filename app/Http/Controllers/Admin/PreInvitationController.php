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
use App\Model\Location;
use App\Events\SmsEvent;
use App\Events\WhatsappEvent;
use Illuminate\Support\Facades\Redirect;
use Mail;
use App\Events\OtpEvent;
use App\Model\Department;
use App\Model\Setting;

class PreInvitationController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     *
     */

   public function index(Request $request)
    {	  
        // 1,4 admin and developer
        // 5,6 recepton, officer
         $user_id =  auth('admin')->user()->id;
         $user_role =  auth('admin')->user()->role_id;

        if($request->ajax()){

            if($user_role ==1){
                $datas = User::orderBy('id','desc')->select('id','name','mobile','pre_visit_date_time','email', 'status', 'image',  'services', 'refer_code','added_by','created_at','officer_id','app_status')->with(['parentDetail'=>function($query){
                    $query->select('id','name');
                }])->whereIn('status',[1,2,3,4,5])->whereNotNull('pre_visit_date_time');
            }
            if($user_role ==4){
                $datas = User::orderBy('id','desc')->select('id','name','mobile','pre_visit_date_time','email', 'status', 'image',  'services',  'refer_code','added_by','created_at','officer_id','app_status')->with(['parentDetail'=>function($query){
                    $query->select('id','name');
                }])->whereIn('status',[1,2,3,4,5])->whereNotNull('pre_visit_date_time');
            }

            if($user_role == 5){
                $datas = User::orderBy('id','desc')->select('id','name','mobile','pre_visit_date_time','email', 'status', 'image',  'services',  'refer_code','added_by','created_at','officer_id','app_status')->with(['parentDetail'=>function($query){
                    $query->select('id','name');
                }])->where('added_by',$user_id)->whereIn('status',[1,2,3,4,5])->whereNotNull('pre_visit_date_time');
            }

            if($user_role == 6){
                $datas = User::orderBy('id','desc')->select('id','name','mobile','pre_visit_date_time','email', 'status', 'image',  'services',  'refer_code','added_by','created_at','officer_id','app_status')->with(['parentDetail'=>function($query){
                    $query->select('id','name');
                }])->where('officer_id',$user_id)->whereIn('status',[1,2,3,4,5])->whereNotNull('pre_visit_date_time');
            }

            if($request->visit_type){
                if($request->visit_type ==1){
                    $datas->where('pre_visit_date_time','<',Carbon::now());
                }
                if($request->visit_type ==2){
                    $datas->where('pre_visit_date_time','>',Carbon::now());
                }
                
            }

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


            $cidata=$request->session()->get('CIDATA');
            $cidata_ob=json_decode($cidata);
            $datas->where($cidata_ob->where,$cidata_ob->cid);//data get by company_id
            // $datas->orderBy('id','desc');

            $records = $datas->limit($request->length)->offset($request->start)->get();
			
            # fetch table records

            $result['data'] = [];
		
            foreach ($records as $data) {

             $result['data'][] =['sn'=>$data->id,'name'=>@$data->name, 'mobile'=>@$data->mobile,'id'=>@$data->id, 'refer_code'=>'#'.@$data->refer_code, 'edit'=>'Edit', 'status'=>@$data->status=='1'?'<span style="color:green;">Active</span>':'<span style="color:red;">In-Active</span>', 'pre_visit_date_time'=>@$data->pre_visit_date_time];

            }

            return $result;
        }
        return view('admin.pre-invitation.list');
    }




    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create(Request $request)
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
		
        return view('admin.pre-invitation.create',compact('user_details','locations'));
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
		 $cidata=$request->session()->get('CIDATA');
            $cidata_ob=json_decode($cidata);
		
        $user_id =  auth('admin')->user()->id;
        $this->validate($request, [
            'name'=>'required',
            'mobile'=>'required|numeric|digits:10',
            'pre_visit_date_time'=>'required',
			'email'=>'required|email'
        ]);
        
        if($request->image){
            $imagePath = $request['image'];
            $milliseconds = round(microtime(true) * 1000);
            $file_name = $milliseconds . $imagePath->getClientOriginalName();
            $path = $request['image']->storeAs('uploads', $file_name, 'public');
            $request->request->add(['image_f' => $path]);
            $file_name='uploads/'.$file_name;
            $image = base64_encode(file_get_contents($request->file('image')));
        }else{
            $file_name ='';
            $image ='';
        }
        
        

        $pin =rand(1000,9999);
        $store_user = new User;
        $store_user->name = $request->name;
        $store_user->mobile = $request->mobile;
        $store_user->email = $request->email;
		$store_user->pre_visit_date_time = $request->pre_visit_date_time;
        $store_user->officer_id = @$request->officer_id?@$request->officer_id:$user_id;
        $store_user->added_by = $user_id;
		$store_user->company_id = $cidata_ob->cid;
		$store_user->image = @$file_name;
		$store_user->image_base = @$image;
        $store_user->status = 2;
        $store_user->location_id = @$request->location_id;
        $store_user->building_id = @$request->building_id;
        $store_user->department_id = @$request->department_id;
		$store_user->app_status = 'Approve';
		$store_user->pre_invite_pin = $pin;
        $settings = Setting::where(['company_id' => $store_user->company_id, 'name' => 'ams_send'])->first();
            
        if($store_user->save())
        {               
            $store_user->refer_code = "VS00".$store_user->id;
            $store_user->save();
            $add_visit = new AllVisit;
            $add_visit->user_id = $store_user->id;
            $add_visit->date_time =  $store_user->pre_visit_date_time;
            $add_visit->officer_id =  $store_user->officer_id;
            $add_visit->added_by =  $store_user->added_by;
            $add_visit->save();
            if(!empty($store_user->image_base)){
                if (@$settings->status == "Active") {
                    $add_status = $this->sendFaceCheck($store_user->id);
                    
                    $res=$this->sendFaceCheckAlotte($store_user->refer_code); 
                    
                    if ($add_status['status_code'] == '201' || $add_status['status_code'] !=null) {
                        $this->emailSendUser($store_user->id,$store_user->pre_invite_pin);
                        $store_user->employee_unique_id = $add_status['status_code'];
                        $store_user->save();
                        return back()->with(['message'=>'Pre Invitation Successfully', 'class'=>'success']);
                    }else{
                        $visitor_delete = User::where('id',$store_user->id)->delete();
                        return back()->with(['message' => $add_status['message'].' on AMS', 'class' => 'error']);
                    }                                
                }
            }

            $this->emailSendUser($store_user->id,$store_user->pre_invite_pin);
            return back()->with(['message'=>'Pre Invitation Successfully', 'class'=>'success']);
            
        }else{
                return back()->with(['message'=>'Oops! Something went wrong', 'class'=>'error']);
        }



    }

	public function emailSendUser($user_id, $pin)
    {
        $user_details=User::where('id',$user_id)->with(['location'=>function($q){
			$q->select('id','name');
		},'building'=>function($q){
			$q->select('id','name');
		}])->first();

		$office_details=Admin::where('id',$user_details->officer_id)->first();

        $reception_name = 'Self Registration';
		$user_email = $user_details->email;
        $sub = $user_details->pre_visit_date_time;
        $appoint_date = date('d/m/Y', strtotime($user_details->pre_visit_date_time));
        $appoint_time = date('h:i:sa', strtotime($user_details->pre_visit_date_time));
		$url=url("/pre-invitations/join/".$user_details->email."/".$user_details->id);
        $res = json_decode($this->createShortLink($url));
      
		$officer_name = ucfirst($office_details->name);
      
        event(new SmsEvent($user_details->mobile, 'You are invited to visit '.$user_details->building->name.'('.$user_details->location->name.') on '.$appoint_date.' at '.$appoint_time.'. Pin '.$pin.' click here '.@$res->link.'. VMS Team'));
	
        // send mail to User  // comment by suresh
        $data=['visitor_name'=>$officer_name,'app_date'=>$appoint_date,'appoint_time'=>$appoint_time,'url'=> $url,'officer_id'=>$user_id,'visitor_id'=>$user_details->refer_id,'duration'=>$user_details->visit_duration,'mobile'=>$office_details->mobile, 'pin'=>$pin,'building'=>$user_details->building->name,'location'=>$user_details->location->name];
        $res=Mail::send('mails.pre-invitation', $data, function($message) use ($user_email){
            $sub = Carbon::now()->toDateTimeString();;
            $message->subject('Appointment Invitation ('.$sub.')');
            $message->from('noreply@vztor.in','Appointment Invitation ('.$sub.')');
            $message->to($user_email, 'New Appointment Alert ('.$sub.')');
        }); 

        // Send mail to User
    }

	public function invitationJoin ($email){
		$datas=User::where('email',$email)->first();
		$symptoms = Symptom::where('status',1)->get();
        $get_officers = Admin::where(['role_id'=>6,'status_id'=>1])->get();
        $get_depart = Department::where(['status'=>1])->get();
		if(empty($datas)){
			return Redirect::route('web.home')->with(['message'=>'You are not registred please register first.', 'class'=>'error']);;

		}else{
			return view('web.per-invitationjoin',compact('datas','symptoms','get_officers','get_depart'));
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
           return Redirect::route('web.pre-invitation.dashboard');
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
        return view('admin.pre-invitation.view',compact('user','order','address'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\Response
     */

    public function edit($user)
    {
		 $user_id =  auth('admin')->user()->id;
		$user_details = User::where('id',$user)->with(['location'=>function($q){
			$q->select('id','name');
		},'building'=>function($q){
			$q->select('id','name');
		},'OfficerDepartment'=>function($q){
			$q->select('id','name');
		},'OfficerDetail'=>function($q){
			$q->select('id','name');
		}])->first();
        
        $menus = User::where('id', $user)->first();
        return view('admin.pre-invitation.edit',compact('user','menus','user_details'));
    }

    public function reInvite($user)
    {
        $company_data = $this->company_data;    
        $locations = Location::where($company_data->where, $company_data->id)->get();
		 $user_id =  auth('admin')->user()->id;
		$user_details = User::where('id',$user)->with(['location'=>function($q){
			$q->select('id','name');
		},'building'=>function($q){
			$q->select('id','name');
		},'OfficerDepartment'=>function($q){
			$q->select('id','name');
		},'OfficerDetail'=>function($q){
			$q->select('id','name');
		}])->first();
        
        $menus = User::where('id', $user)->first();
        return view('admin.pre-invitation.reinvite',compact('user','menus','user_details','locations'));
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
                'mobile'=>'required|numeric|digits:10',
                'pre_visit_date_time'=>'required'
            ]);

            $user_id =  auth('admin')->user()->id;
            $store_user = User::find($user);
            $store_user->name = $request->name;
            $store_user->mobile = $request->mobile;
            $store_user->pre_visit_date_time = $request->pre_visit_date_time;
			$store_user->location_id = @$request->location_id;
			$store_user->building_id = @$request->building_id;
			$store_user->department_id = @$request->department_id;
            $store_user->officer_id = @$request->officer_id?@$request->officer_id:$user_id;

            if($store_user->save()){      
              return back()->with(['message'=>'Pre-invitation Update Successfully', 'class'=>'success']);
            }else{
                  return back()->with(['message'=>'Oops! Something went wrong', 'class'=>'error']);
            }
        }

        if($request->type == 'reinvite'){
         
            $this->validate($request, [
                'name'=>'required',
                'mobile'=>'required|numeric|digits:10',
                'pre_visit_date_time'=>'required',
                'email'=>'required|email'
            ]); 
           
            $cidata=$request->session()->get('CIDATA');
            $cidata_ob=json_decode($cidata);
            $user_id =  auth('admin')->user()->id;
            $pin =rand(1000,9999);
            $store_user = new User;
            $store_user->name = $request->name;
            $store_user->mobile = $request->mobile;
            $store_user->email = $request->email;
            $store_user->pre_visit_date_time = $request->pre_visit_date_time;
            $store_user->officer_id = @$user_id;
            $store_user->added_by = @$user_id;
            $store_user->company_id = $cidata_ob->cid;
            $store_user->image = $request->image;
            $store_user->image_base = $request->image_base;
            $store_user->status = 2;
            $store_user->location_id = @$request->location_id;
            $store_user->building_id = @$request->building_id;
            $store_user->department_id = @$request->department_id;
            $store_user->app_status = 'Approve';
            $store_user->pre_invite_pin = $pin;

            $settings = Setting::where(['company_id' => $store_user->company_id, 'name' => 'ams_send'])->first();
            
            if($store_user->save())
            {               
                $store_user->refer_code = "VS00".$store_user->id;
                $store_user->save();
                $add_visit = new AllVisit;
                $add_visit->user_id = $store_user->id;
                $add_visit->date_time =  $store_user->pre_visit_date_time;
                $add_visit->officer_id =  $store_user->officer_id;
                $add_visit->added_by =  $store_user->added_by;
                $add_visit->save();
                if(!empty($store_user->image_base)){
                    if (@$settings->status == "Active") {
                        $add_status = $this->sendFaceCheck($store_user->id);
                        
                        $res=$this->sendFaceCheckAlotte($store_user->refer_code); 
                        
                        if ($add_status['status_code'] == '201') {
                            $this->emailSendUser($store_user->id,$store_user->pre_invite_pin);
                            $store_user->employee_unique_id = $add_status['status_code'];
                            $store_user->save();
                            return back()->with(['message'=>'Pre Invitation Successfully', 'class'=>'success']);
                        }else{
                            $visitor_delete = User::where('id',$store_user->id)->delete();
                            return back()->with(['message' => $add_status['message'].'on AMS', 'class' => 'error']);
                        }                                
                    }
                }
               

                $this->emailSendUser($store_user->id,$store_user->pre_invite_pin);
                return back()->with(['message'=>'Pre Invitation Successfully', 'class'=>'success']);
              
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
            return response()->json(['message'=>'User deleted successfully ...', 'class'=>'success']);
        }
        return response()->json(['message'=>'Whoops, looks like something went wrong ! Try again ...', 'class'=>'error']);
    }



    public function sendEmail($user_id){
        $new_user_id = $user_id;

        $user_details = User::Where(['id'=>$new_user_id])->first();
        $reception_details = Admin::Where(['id'=>$user_details->added_by])->first();
        $officer_details = Admin::Where(['id'=>$user_details->officer_id])->first();

        $user_email = $officer_details->email;
        $reception_name = $reception_details->name;
        $sub = $user_details->visite_time;
        $appoint_date = date('d-m-Y', strtotime($user_details->visite_time));
        $appoint_time = date('h:i:sa', strtotime($user_details->visite_time));

         event(new SmsEvent($officer_details->mobile, 'Dear Sir, '.$reception_details->name.' has scheduled a new visitor meeting on '.$appoint_date.' at '.$appoint_time.'.Kindly login to see the details.'));
        // send mail to patient
         $data=['visitor_name'=>$reception_name,'app_date'=>$appoint_date,'appoint_time'=>$appoint_time];
              Mail::send('mails.appointment-invoice', $data, function($message) use ($user_email){
                $sub = Carbon::now()->toDateTimeString();;

                $message->subject('New Appointment Alert ('.$sub.')');
                $message->from('noreply@sspl20.com','New Appointment Alert  ('.$sub.')');
                $message->to($user_email, 'New Appointment Alert ('.$sub.')');
            });

        // Send mail to admin

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



      function sendFaceCheck($user_id)
      {
        $user_data = User::where('id', $user_id)->first();
        $gender = $user_data->gender?strtoupper($user_data->gender):'MALE';
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
          CURLOPT_POSTFIELDS => '{
              "office_name": "H1",
              "department_name": "Visitor",
              "shift_name": "Morning",
              "employee_name": "' . $user_data->name . '",
              "employee_id": "' . $user_data->refer_code . '",
              "employee_gender": "' . $gender. '",
              "employee_image": "' . $user_data->image_base . '",
              "employee_email": "' . $user_data->email . '",
              "employee_contact_number": "' . $user_data->mobile . '",
              "contract_type": "PERMANENT",
              "overtime": "30",
              "status": "ACTIVE",
              "date": "' . $user_data->visite_time . '"
            }',
          CURLOPT_HTTPHEADER => array(
            'Authorization:  bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyX25hbWUiOiJzYW10ZWNoLmFkbWluIiwidHlwZV9vZl91c2VyIjoiQURNSU4iLCJ0b2tlbiI6IiQyYSQwOCQ5SUY1UFV6cGh0bWVRTzJtVWtOdU8ucnU5VUhFaGc4OEtxM3QzMVVxR0VvR2NZR3BnU0VDVyIsImlhdCI6MTY0NTE2MDIzMX0.KGAyVEivIQ6Fncg8JPmlwNZfkBwNcPaTJCNj5wKruR8',
            'Content-Type: text/plain'
          ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return json_decode($response, true);
    }

   function sendFaceCheckAlotte($employee_id)
   {
    $devices=$this->getDeviceAllocateUser($employee_id);
        
    $devices_name=json_encode($devices);

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
      CURLOPT_POSTFIELDS =>'{
        "employee_id": "'.$employee_id.'",
        "allotments": '.$devices_name.'
      }',


       CURLOPT_HTTPHEADER => array(
         //'Authorization: bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyX25hbWUiOiJzYW10ZWNoLmFkbWluIiwidHlwZV9vZl91c2VyIjoiQURNSU4iLCJ0b2tlbiI6IiQyYSQwOCQ5SUY1UFV6cGh0bWVRTzJtVWtOdU8ucnU5VUhFaGc4OEtxM3QzMVVxR0VvR2NZR3BnU0VDVyIsImlhdCI6MTYxODQ3NTI3NX0.mXvnJi_I-8j2eNJJBkHzLBzVp766BdS-Rpk95BHz7RI',
         'Authorization: Authorization": "bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyX25hbWUiOiJzYW10ZWNoLmFkbWluIiwidHlwZV9vZl91c2VyIjoiQURNSU4iLCJ0b2tlbiI6IiQyYSQwOCQ5SUY1UFV6cGh0bWVRTzJtVWtOdU8ucnU5VUhFaGc4OEtxM3QzMVVxR0VvR2NZR3BnU0VDVyIsImlhdCI6MTY0NTE2MDIzMX0.KGAyVEivIQ6Fncg8JPmlwNZfkBwNcPaTJCNj5wKruR8',
         'Content-Type: text/plain'
       ),
     ));
    $response = curl_exec($curl);
    curl_close($curl);
    return json_decode($response, true);
  }
  public function getDeviceAllocateUser($user_id){
    $users=User::where('refer_code',$user_id)->first();
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
    return $device_details;
  }

}
