<?php

namespace App\Http\Controllers\Web;

use DB;
use App\User;
use App\Admin;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Model\Department;
use App\Model\Device;
use App\Model\AllVisit;
use App\Model\Meeting;
use App\Model\MeetingsInvite;
use App\Model\SortLink;
use Intervention\Image\ImageManagerStatic as Image;
use App\Model\DesignationDepartment;
use App\Model\ConferenceMeeting;
use App\Events\SmsEvent;
use Illuminate\Support\Facades\Redirect;
use App\Model\ConferenceRoom;
use App\Model\Reminder;
use Mail;
use Session;
use App\Model\Setting;
use URl;
use File;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
class MeetingController extends Controller
{
    use AuthenticatesUsers;
    public function __construct()
    {
        parent::__construct();
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    // private $cid='CID01';
	
	
	public function meetingReminder(){
	    $today = date('Y-m-d'); 
        $end = strtotime(date("Y-m-d H:i:s"));		
		$meetings = Meeting::whereRaw('date_format(from_date,"%Y-%m-%d")'."='".$today . "'")->select('id', 'from_date', 'meeting_title')->get();
		if($meetings){
			foreach($meetings as $meeting){	
				$start = strtotime($meeting->from_date);	
				$minutes  = ($start - $end)/60;
				if($minutes < 70){
					$reminder = Reminder::where('meeting_id', $meeting->id)->first();
					if(empty($reminder)){
				      $users = ConferenceMeeting::where('meeting_id', $meeting->id)->select('name', 'email', 'mobile')->get();
					  if($users){
						  foreach($users as $user){
							   $mobile = $user->mobile;
							   $useremail = $user->email;
							   $message = 'Hi '.$user->name.', You have a '.$meeting->meeting_title.' conference meeting at '.date('h:i A', strtotime($meeting->from_date)).'. Please join on time. Thanks ITDA';
							 // dd($message);
							   $URL = 'http://my.logicboxitsolutions.com/api/send_gsm?api_key=476076e5e398b99&text='.$message.'&mobile='.$mobile.'';
							 // dd($URL);
							  $res = $this->sendSMSPhone1($URL);
							  
						      Mail::send('mail_reminder',['name' => 'ITDA','meeting_title'=>$meeting->meeting_title,'from_date'=>$meeting->from_date, 'emp'=>$user->name], function ($message) use ($useremail, $meeting){
                $message->from('vztor.in@gmail.com', 'ITDA');
                $message->to( $useremail);
                $message->subject("Join your meeting at ~".date('h:i A', strtotime($meeting->from_date))." • ".$meeting->meeting_title."");
            });
							  
							  
						  }
					  }
						
						$Reminder = new Reminder();
						$Reminder->meeting_id = $meeting->id;
						$Reminder->save();
					}
				}
			}
		}
	}
	
	public function vms_management_type(Request $request, $id){
		try{
			
		$cidata=$request->session()->get('CIDATA');
        $cidata_ob=json_decode($cidata);
        $company_id=$cidata_ob->cid;
        if(isset($request->date_range) && empty($request->date_range)){
            $from_date=Carbon::now();
            $end_date=Carbon::today();
        }else{
            if($request->date_range=="custom"){
                $from_date=$request->from_date;
                $end_date=$request->till_date;
            }elseif($request->date_range=="Today"){
                $from_date=Carbon::today();
                $end_date=Carbon::now();
            }elseif($request->date_range=="1 Week"){
                $from_date=Carbon::now()->subDays(7);
                $end_date=Carbon::now();
            }elseif($request->date_range=="1 Month"){
                $from_date=Carbon::now()->subDays(30);
                $end_date=Carbon::now();
            }elseif($request->date_range=="2 Month"){
                $from_date=Carbon::now()->subDays(60);
                $end_date=Carbon::now();
            }elseif($request->date_range=="3 Month"){
                $from_date=Carbon::now()->subDays(90);
                $end_date=Carbon::now();
            }else{
				$from_date=Carbon::today();
            	$end_date=Carbon::now();
			}
            
        }
        $user_id = auth('admin')->user()->role_id;
        $user_ids = auth('admin')->user()->id;
   
        if($user_id == '5')
        {
            $appointments = User::where(['added_by'=>$user_ids,'company_id'=>$company_id])->with(['parentDetail'=>function($query){
                        $query->select('id','name', 'role_id');
                    },'OfficerDetail'=>function($query){
                        $query->select('id','name', 'role_id');
                    }])->whereBetween('created_at',[$from_date,$end_date])->orderBy('id', 'DESC')->limit(10)->get();
        }
        elseif($user_id == '6')
        {
            $appointments = User::where(['officer_id'=>$user_ids,'company_id'=>$company_id])->with(['parentDetail'=>function($query){
                        $query->select('id','name', 'role_id');
                    },'OfficerDetail'=>function($query){
                        $query->select('id','name', 'role_id');
                    }])->whereBetween('created_at',[$from_date,$end_date])->limit(10)->orderBy('id', 'DESC')->get();
        }
        elseif($user_id == '1')
        {
			
            $appointments = User::where(['status'=>1,'company_id'=>$company_id])->limit(10)->with(['parentDetail'=>function($query){
                        $query->select('id','name', 'role_id');
                    },'OfficerDetail'=>function($query){
                        $query->select('id','name', 'role_id');
                    }])->whereBetween('created_at',[$from_date,$end_date])->orderBy('id', 'DESC')->get();
         }
         elseif($user_id == '4')
         {
            $appointments = User::where(['status'=>1,'company_id'=>$company_id])->limit(10)->with(['parentDetail'=>function($query){
                        $query->select('id','name', 'role_id');
                    },'OfficerDetail'=>function($query){
                        $query->select('id','name', 'role_id');
                    }])->whereBetween('created_at',[$from_date,$end_date])->orderBy('id', 'DESC')->get();
        } else {

            $appointments = User::where(['status'=>1,'company_id'=>$company_id])->limit(10)->with(['parentDetail'=>function($query){
                        $query->select('id','name', 'role_id');
                    },'OfficerDetail'=>function($query){
                        $query->select('id','name', 'role_id');
                    }])->whereBetween('created_at',[$from_date,$end_date])->orderBy('id', 'DESC')->get();
        }
            $reports=[];
            $reports['total']=$this->getTotalVisitorData($from_date,$end_date,$company_id);
            $reports['today_in']=$this->getTodatInVisitorData($from_date,$end_date,$company_id);
            $reports['today_out']=$this->getTodayOutVisitorData($from_date,$end_date,$company_id);

            $today_appointments=$this->getTodayAppoinments($company_id); //not in use
            $total_appointments=$this->getTotalAppoinments($company_id); //not in use
            $total_visitor=$this->getTotalVisitor($company_id); //not in use
            $new_appointments=$this->getnewAppoinments($company_id); //not in use


            $all_checkin_visitor= \DB::table('all_visits')->join('users', 'users.id', '=', 'all_visits.user_id')
		    ->where('all_visits.in_status', 'Yes')
		    ->where('all_visits.out_status', 'No')
			->where('users.company_id', $company_id)
			->count(); 
         $date = date('d/m/Y h:i:s');
		   
 //  dd(Carbon::now()->format('d/m/Y H:i:s'));
            $all_upcoming_visitor= \DB::table('users')
			->join('all_visits', 'users.id', '=', 'all_visits.user_id')
		   ->where('all_visits.date_time','>',Carbon::now()->format('d/m/Y H:i:s'))
		   ->where('all_visits.in_status', 'No')
		   ->where('all_visits.out_status', 'No')
		   ->where('users.company_id', $company_id)
			->count();
		 //  ->whereHas('getVisitor_check', function($query) use($company_id){
                  //  $query->where('company_id',$company_id);
               // })
		
         // dd(Carbon::now());
            $all_overstaying_visitor = 0;
            $all_visitors= AllVisit::where(['in_status'=>'Yes','out_status'=>'Yes'])->with(['getVisitor'=>function($q){
                $q->select('id','visite_duration','company_id');
            }])->whereHas('getVisitor', function($query) use($company_id){
                $query->where('company_id',$company_id);
            })->get(); 
            foreach($all_visitors as $all){
                $d1 = strtotime($all->in_time);
                $d2 = strtotime($all->out_time);
                $totalSecondsDiff = abs($d1-$d2); 
                $totalMinutesDiff = $totalSecondsDiff/60; 
                if($totalMinutesDiff > $all->getVisitor->visite_duration){
                    $all_overstaying_visitor++;
                }
            }           

            $all_checkout_visitor= AllVisit::where(['in_status'=>'Yes','out_status'=>'Yes'])->whereHas('getVisitor', function($query) use($company_id){
				$query->where('company_id',$company_id);
			})->count(); 


            $last_input = $request->date_range;
		//dd($all_upcoming_visitor);
		$type = "";	
		if($id == 1){
			$type = "Visitor";
		}
		if($id == 2){
			$type = "Conference";
		}
		if($id == 3){
			$type = "Master";
		}
        \Session::start();
        \Session::put('user_work_on', $type);
			
            return view('admin.dashboard',compact('appointments','reports','total_appointments','today_appointments','total_visitor','new_appointments',
												  'last_input','all_checkin_visitor','all_upcoming_visitor','all_overstaying_visitor','all_checkout_visitor'));
			
			
	} catch(\Exception $exception){
			//dd($exception);
          return back();
       }
	}
	
	 public function getTodayAppoinments($company_id){
        $total=AllVisit::whereDate('created_at',Carbon::today())->where(['in_status'=>'No','company_id'=>$company_id,'out_status'=>'No'])->count();
        return $total;
    }
    public function getnewAppoinments($company_id){
        $total=AllVisit::where('created_at','>',Carbon::now()->subHour(4))->where(['in_status'=>'No','out_status'=>'No','company_id'=>$company_id])->count();
        return $total;
    }
    public function getTotalAppoinments($company_id){
        $total=AllVisit::where(['in_status'=>'No','out_status'=>'No','company_id'=>$company_id])->count();
        return $total;
    }
    public function getTotalVisitor($company_id){
        $total=User::where(['company_id'=>$company_id])->count();
        return $total;
    }
    public function getTotalVisitorData($from_date,$end_date,$company_id){
        $total=User::whereBetween('created_at',[$from_date,$end_date])->where(['company_id'=>$company_id])->count();
        return $total;
    }

    public function getTodatInVisitorData($from_date,$end_date,$company_id){
        $total=AllVisit::where(['in_status'=>'Yes','out_status'=>'No','company_id'=>$company_id])->whereBetween('created_at',[$from_date,$end_date])->count();
        return $total;
    }
    
    public function reg_link($id = null){
        $select_link = SortLink::where('code', $id)->select('link')->orderBy('id', 'DESC')->first();
        $url = url($select_link->link);
        return Redirect::to($url);
        
    }

    public function getTodayOutVisitorData($from_date,$end_date,$company_id){
        $total=AllVisit::where(['out_status'=>'Yes','company_id'=>$company_id])->whereBetween('created_at',[$from_date,$end_date])->count();
        return $total;
    }
	
    public function create(Request $request)
    {
        try {
        $emails = base64_decode($request->with);
        $meeting_id = $request->meeting_id;
        $userdata = ConferenceMeeting::where('email',$emails)->where('meeting_id', $request->meeting_id)->first();
		//dd($userdata);
        if(!empty($userdata)){
            $user_data=$userdata;
        } else{
            $user_data=$emails;
        }
			
		$meeting = Meeting::where('id', $request->meeting_id)->select('to_date')->first();
		$current_date = date('Y-m-d H:i:s');	
		if($meeting->to_date > $current_date){
			$meeting_status ='start'; 
		} else {
			$meeting_status ='expired';
		}
			

        $room = ConferenceRoom::where(['id'=>$request->room])->first();
        $MeetingsInvite = MeetingsInvite::where('meeting_id', $request->meeting_id)->where('meeting_members',$emails)->select('employee_id', 'member_type', 'mobile', 'company_id')->first();
        if($MeetingsInvite->member_type == 2){
            $type = 'Guest';
            return view('web.conference-meeting',compact('user_data','room', 'type', 'meeting_id', 'MeetingsInvite', 'meeting_status'));
        } else {
            $employeedata = ConferenceMeeting::where('employee_id', $MeetingsInvite->employee_id)->select('avatar', 'image', 'id')->first();
			//dd($employeedata);
            $type = 'Employee';
            $user = Admin::where('id', $MeetingsInvite->employee_id)->select('name', 'email', 'mobile', 'gender', 'address', 'department_id', 'designation_id', 'id')->with(['getDepartment'=>function($q){
            $q->select('id', 'name');
            }])->first();
            
            $child_designation = DesignationDepartment::where('parent_id', $user->designation_id)->select('designation_id')->first();
            if($child_designation){
            $jr_staff = Admin::where('designation_id', $child_designation->designation_id)->select('name', 'email', 'id')->get();
			} else {
				$jr_staff = [];
			}

            return view('web.conference-meeting', compact('user_data','room', 'employeedata', 'type', 'user', 'jr_staff', 'meeting_id', 'meeting_status'));
        }
     
       } catch(\Exception $exception){
			//dd($exception);
          return back();
       }
    }
    
	
	public function manage_meetings_filter(Request $request) {
     
        $cidata=$request->session()->get('CIDATA');
        $user_id =  auth('admin')->user()->id;
        $cidata_ob=json_decode($cidata);
       // dd($request->from_date);
        if(isset($request->from_date) && isset($request->to_date)){
          $result = Meeting::where(['company_id'=>$cidata_ob->cid])->with(['confrence'=>function($q){
            $q->select('id','room');
          }])->orderBy('id', 'DESC')->get();
        } else {
          if(isset($request->room_id)){
            $result = Meeting::where(['company_id'=>$cidata_ob->cid, 'room_id'=>$request->room_id])->with(['confrence'=>function($q){
            $q->select('id','room');
          }])->orderBy('id', 'DESC')->get();
          } else {
            $result = Meeting::where(['company_id'=>$cidata_ob->cid])->with(['confrence'=>function($q){
            $q->select('id','room');
          }])->orderBy('id', 'DESC')->get();
          }
        }
        
        return view('admin.meeting.list',compact('result'));
    }

    public function conferenceMeetingStore(Request $request){
       //dd($request);
		if($request->image_img){
			$request->image = $request->image_img;
		} else {
			if(isset($request->image)){
			$this->validate($request, [
			'image' => 'required',
		   ]);
			} else {
			return back()->with(['message' => 'Kindly Upload your profile image', 'class' => 'error']);
			}
	
		}
        $slect = ConferenceMeeting::where('email', $request->email)->where('meeting_id', $request->meeting_id)->first();
        //dd($request->image_base64);
        if($request->member_type == 'Guest'){
		
			if($request->guest_email){
            $Meeting = Meeting::where('id', $request->meeting_id)->select('room_id', 'meeting_title', 'to_date', 'from_date', 'descriptions')->first(); 
            $var=$request->guest_email;
            $useremail=$this->extract_emails_from($var);
            $find_mail=implode("\n", $useremail);
            $encrypt_email=base64_encode($find_mail);
        
            $Meeting = new MeetingsInvite;
            $Meeting->meeting_members=$var;
            $Meeting->member_type=2;
            $Meeting->meeting_id=$request->meeting_id;
            $Meeting->company_id='CID33';
            $Meeting->added_by=$request->emp_id;
            Mail::send('mail',['name' => 'VMS','meeting_title'=>$Meeting->meeting_title,'from_date'=>$Meeting->from_date,'to_date'=>$Meeting->to_date,'descriptions'=>$Meeting->descriptions,'assigned_by'=>$request->name,'link'=>url('registration-conference-meeting?meeting_id='.$request->meeting_id.'&with='.$encrypt_email.'&room='.$request->room_id.'')], function ($message) use ($request, $useremail, $Meeting){
                $message->from('vztor.in@gmail.com', 'VMS');
                $message->to( $useremail);
                $message->subject("Conference Meeting Notification : @ ".date('d-M-Y H:i:s', strtotime($Meeting->from_date))." To ".date('d-M-Y H:i:s', strtotime($Meeting->to_date))." ( ".$request->name." )");
            });

            $Meeting->save();
            } else {
			
        if(!empty($slect)){
            $damin = new ConferenceMeeting();
            $damin->company_id=$request->company_id;
            $damin->location_id=$request->location_id;
            $damin->building_id=$request->building_id;
            $damin->department_id=$request->department_id;
			$damin->device_id=$request->device_id;
            $damin->room_id=$request->room_id;
            $damin->name=$request->name;
            $damin->email=$request->email;
            $damin->meeting_id=$request->meeting_id;
            $damin->mobile=$request->mobile;
            $damin->gender=$request->gender;
			$damin->office=$request->office; 
            $damin->department=$request->department; 
            $damin->address=$request->address;
				
		    if($request->image_img){
		   $attachmant_base = $request->image_img;
           $file_name_doc = 'image_img_' . time() . '.png'; //generating unique file name;
           @list($type, $attachmant_base) = explode(';', $attachmant_base);
           @list(, $attachmant_base) = explode(',', $attachmant_base);
           if ($attachmant_base != "") {
			  $attachmant_base =   $attachmant_base;
              $attachmant_base =   $attachmant_base;
			  file_put_contents(public_path().'/uploads/img/'.$file_name_doc, base64_decode($attachmant_base));
             // $attachments=$file_name_doc;
			   $damin->image = $attachmant_base;
               $damin->avatar = $file_name_doc;
          }
       
         } else {
				if ($request->hasfile('image')) {
					    $image = $request->file('image');
					    $originalExtension = $image->getClientOriginalExtension();
					    $image_s = Image::make($image)->orientate();
					    $image_s->resize(230, null, function ($constraint) {
							$constraint->aspectRatio();
						});
					   $filename = random_int(9, 999999999) + time() . '.' . $originalExtension;
					   $image_s->save(public_path('/uploads/img/'.$filename));
                        $damin->image = $image;
                        $damin->avatar=$filename;
                }
			}
			
            $damin->save();
			$last_id=$damin->id;
			if($request->image_img){
			$path = public_path('/uploads/img/' .$file_name_doc);
			$data = file_get_contents($path);
			$base64 = base64_encode($data);
			} else {
			if ($request->hasfile('image')) {
			$path = public_path('/uploads/img/' .$filename);
			$data = file_get_contents($path);
			$base64 = base64_encode($data);
			} else {
				$base64 = $slect->image;
			}
			}
			
			
			$conferen=ConferenceMeeting::where('id', $last_id)->first();
            $conferen->refer_code= $slect->refer_code; 
           // $conferen->refer_code= 'VMS00'.$last_id;
			$conferen->image=$base64;	
            $conferen->save();
            $var=$this->sendFaceCheck($last_id);
			//dd($var);
            $var2=$this->sendFaceCheckAlotte($slect->refer_code);
			
			ConferenceMeeting::where('id', $slect->id)->delete();
		} else{
	$old_refer_code = ConferenceMeeting::where('email', $request->email)->where('mobile', $request->mobile)->select('refer_code')->first();
            $damin = new ConferenceMeeting();
            $damin->company_id=$request->company_id;
            $damin->location_id=$request->location_id;
            $damin->building_id=$request->building_id;
            $damin->department_id=$request->department_id;
			$damin->device_id=$request->device_id;
            $damin->room_id=$request->room_id;
            $damin->name=$request->name;
            $damin->email=$request->email;
            $damin->meeting_id=$request->meeting_id;
            $damin->mobile=$request->mobile;
            $damin->gender=$request->gender;
			$damin->office=$request->office; 
            $damin->department=$request->department; 
            $damin->address=$request->address;
            if($request->image_img){		
				
				//if ($request->hasfile('image')) {
			   // $image = $request->file('image');
			   // $originalExtension = $image->getClientOriginalExtension();
			   // $image_s = Image::make($image)->orientate();
				//$image_s->resize(230, null, function ($constraint) {
				//$constraint->aspectRatio();
				//});
			    //$filename = random_int(9, 999999999) + time() . '.' . $originalExtension;
				//$image_s->save(public_path('/uploads/img/'.$filename));
                //$damin->image = $image;
               // $damin->avatar=$filename;
               // }
			
		   $attachmant_base = $request->image_img;
           $file_name_doc = 'image_img_' . time() . '.png'; //generating unique file name;
           @list($type, $attachmant_base) = explode(';', $attachmant_base);
           @list(, $attachmant_base) = explode(',', $attachmant_base);
           if ($attachmant_base != "") {
			  $attachmant_base =   $attachmant_base;
              $attachmant_base =   $attachmant_base;
			  file_put_contents(public_path().'/uploads/img/'.$file_name_doc, base64_decode($attachmant_base));
             // $attachments=$file_name_doc;
			   $damin->image = $attachmant_base;
               $damin->avatar = $file_name_doc;
			   $filename = $file_name_doc;
          }
				
            } else{
                   if ($request->hasfile('image')) {
					    $image = $request->file('image');
					    $originalExtension = $image->getClientOriginalExtension();
					    $image_s = Image::make($image)->orientate();
					    $image_s->resize(230, null, function ($constraint) {
							$constraint->aspectRatio();
						});
					   $filename = random_int(9, 999999999) + time() . '.' . $originalExtension;
					   $image_s->save(public_path('/uploads/img/'.$filename));
                        $damin->image = $image;
                        $damin->avatar=$filename;
                }
            }
            $damin->save();
			$last_id=$damin->id;
			$path = public_path('/uploads/img/' .$filename);
			$data = file_get_contents($path);
			$base64 = base64_encode($data);
			if($old_refer_code){
                $refer_code = $old_refer_code->refer_code;
            } else {
                $refer_code = 'VMS00'.$last_id;
            }
			$conferen=ConferenceMeeting::where('id', $last_id)->first();
            $conferen->refer_code=$refer_code;
			$conferen->image=$base64;	
            $conferen->save();
            $var=$this->sendFaceCheck($last_id);
            $var2=$this->sendFaceCheckAlotte($refer_code);
        }
			
		}	

      } else {

        $cidata=$request->session()->get('CIDATA');
        $cidata_ob=json_decode($cidata);

        if(!empty($slect)){
            if($request->assign_staff){

            $employee = Admin::where('id', $request->assign_staff)->select('email', 'mobile', 'id')->first();
            $Meeting = Meeting::where('id', $request->meeting_id)->select('room_id', 'meeting_title', 'to_date', 'from_date', 'descriptions')->first();  
            $var=$employee->email;
            $useremail=$this->extract_emails_from($var);
            $find_mail=implode("\n", $useremail);
            $encrypt_email=base64_encode($find_mail);
            
            $mobile_sms = $employee->mobile;
            if($mobile_sms){
              $url_link = url('registration-conference-meeting?meeting_id='.$request->meeting_id.'&with='.$encrypt_email.'&room='.$Meeting->room_id.'');
              $res = $this->createShortLink($url_link);
    $message = urlencode(''.$Meeting->meeting_title.', when '.date("d M, Y H:i", strtotime($Meeting->from_date)).', Register with VMS '.$res.', Assigned By '.$request->name.'');
            //  dd($message);
              $URL = 'http://my.logicboxitsolutions.com/api/send_gsm?api_key=476076e5e398b99&text='.$message.'&mobile='.$mobile_sms.'';
              $this->sendSMSPhone($URL);
            }
            $Meeting = new MeetingsInvite;
            $Meeting->meeting_members=$var;
            $Meeting->member_type=1;
            $Meeting->meeting_id=$request->meeting_id;
            $Meeting->mobile=$employee->mobile;
            $Meeting->employee_id=$employee->id;
            $Meeting->company_id=$cidata_ob->cid;
            $Meeting->added_by=$request->emp_id;
            Mail::send('mail',['name' => 'VMS','meeting_title'=>$Meeting->meeting_title,'from_date'=>$Meeting->from_date,'to_date'=>$Meeting->to_date,'descriptions'=>$Meeting->descriptions,'assigned_by'=>$request->name,'link'=>url('registration-conference-meeting?meeting_id='.$request->meeting_id.'&with='.$encrypt_email.'&room='.$request->room_id.'')], function ($message) use ($request, $useremail, $Meeting){
                $message->from('vztor.in@gmail.com', 'VMS');
                $message->to( $useremail);
                $message->subject("Conference Meeting Notification : @ ".date('d-M-Y H:i:s', strtotime($Meeting->from_date))." To ".date('d-M-Y H:i:s', strtotime($Meeting->to_date))." ( ".$request->name." )");
            });

            $Meeting->save();
        

            } else {

            if($request->guest_email){
            $Meeting = Meeting::where('id', $request->meeting_id)->select('room_id', 'meeting_title', 'to_date', 'from_date', 'descriptions')->first(); 
            $var=$request->guest_email;
            $useremail=$this->extract_emails_from($var);
            $find_mail=implode("\n", $useremail);
            $encrypt_email=base64_encode($find_mail);
        
            $Meeting = new MeetingsInvite;
            $Meeting->meeting_members=$var;
            $Meeting->member_type=2;
            $Meeting->meeting_id=$request->meeting_id;
            $Meeting->company_id=$cidata_ob->cid;
            $Meeting->added_by=$request->emp_id;
            Mail::send('mail',['name' => 'VMS','meeting_title'=>$Meeting->meeting_title,'from_date'=>$Meeting->from_date,'to_date'=>$Meeting->to_date,'descriptions'=>$Meeting->descriptions,'assigned_by'=>$request->name,'link'=>url('registration-conference-meeting?meeting_id='.$request->meeting_id.'&with='.$encrypt_email.'&room='.$request->room_id.'')], function ($message) use ($request, $useremail, $Meeting){
                $message->from('vztor.in@gmail.com', 'VMS');
                $message->to( $useremail);
                $message->subject("Conference Meeting Notification : @ ".date('d-M-Y H:i:s', strtotime($Meeting->from_date))." To ".date('d-M-Y H:i:s', strtotime($Meeting->to_date))." ( ".$request->name." )");
            });

            $Meeting->save();
            } else {
				
				
				

            }
        }


        } else{

        if($request->assign_staff){

            $employee = Admin::where('id', $request->assign_staff)->select('email', 'mobile', 'id', 'company_id')->first();
            $Meeting = Meeting::where('id', $request->meeting_id)->select('room_id', 'meeting_title', 'to_date', 'from_date', 'descriptions')->first();  
            $var=$employee->email;
            $useremail=$this->extract_emails_from($var);
            $find_mail=implode("\n", $useremail);
            $encrypt_email=base64_encode($find_mail);
            
            $mobile_sms = $employee->mobile;
            if($mobile_sms){
              $url_link = url('registration-conference-meeting?meeting_id='.$request->meeting_id.'&with='.$encrypt_email.'&room='.$Meeting->room_id.'');
              $res = $this->createShortLink($url_link);
$message = urlencode(''.$Meeting->meeting_title.', when '.date("d M, Y H:i", strtotime($Meeting->from_date)).', Register with VMS '.$res->link.', Assigned By '.$request->name.'');
            //  dd($message);
              $URL = 'http://my.logicboxitsolutions.com/api/send_gsm?api_key=476076e5e398b99&text='.$message.'&mobile='.$mobile_sms.'';
              $this->sendSMSPhone($URL);
            }
            $Meeting = new MeetingsInvite;
            $Meeting->meeting_members=$var;
            $Meeting->member_type=1;
            $Meeting->meeting_id=$request->meeting_id;
            $Meeting->mobile=$employee->mobile;
            $Meeting->employee_id=$employee->id;
            $Meeting->company_id=$employee->company_id;
            $Meeting->added_by=$request->emp_id;
            Mail::send('mail',['name' => 'VMS','meeting_title'=>$request->meeting_title,'from_date'=>$Meeting->from_date,'to_date'=>$Meeting->to_date,'descriptions'=>$Meeting->descriptions,'assigned_by'=>$request->name,'link'=>url('registration-conference-meeting?meeting_id='.$request->meeting_id.'&with='.$encrypt_email.'&room='.$request->room_id.'')], function ($message) use ($request, $useremail, $Meeting){
                $message->from('vztor.in@gmail.com', 'VMS');
                $message->to( $useremail);
                $message->subject("Conference Meeting Notification : @ ".date('d-M-Y H:i:s', strtotime($Meeting->from_date))." To ".date('d-M-Y H:i:s', strtotime($Meeting->to_date))." ( ".$request->name." )");
            });

            $Meeting->save();
        

            } else {

            if($request->guest_email){
            $Meeting = Meeting::where('id', $request->meeting_id)->select('room_id', 'meeting_title', 'to_date', 'from_date', 'descriptions', 'company_id')->first(); 
            $var=$request->guest_email;
            $useremail=$this->extract_emails_from($var);
            $find_mail=implode("\n", $useremail);
            $encrypt_email=base64_encode($find_mail);
            
            $Meeting = new MeetingsInvite;
            $Meeting->meeting_members=$var;
            $Meeting->member_type=2;
            $Meeting->meeting_id=$request->meeting_id;
            $Meeting->company_id=$Meeting->company_id;
            $Meeting->added_by=$request->emp_id;
            Mail::send('mail',['name' => 'VMS','meeting_title'=>$request->meeting_title,'from_date'=>$Meeting->from_date,'to_date'=>$Meeting->to_date,'descriptions'=>$Meeting->descriptions,'assigned_by'=>$request->name,'link'=>url('registration-conference-meeting?meeting_id='.$request->meeting_id.'&with='.$encrypt_email.'&room='.$request->room_id.'')], function ($message) use ($request, $useremail, $Meeting){
                $message->from('vztor.in@gmail.com', 'VMS');
                $message->to( $useremail);
                $message->subject("Conference Meeting Notification : @ ".date('d-M-Y H:i:s', strtotime($Meeting->from_date))." To ".date('d-M-Y H:i:s', strtotime($Meeting->to_date))." ( ".$request->name." )");
            });

            $Meeting->save();


            } else {
            $old_refer_code = ConferenceMeeting::where('employee_id', $request->emp_id)->select('refer_code')->first();
            $damin = new ConferenceMeeting();
            $damin->company_id=$request->company_id;
            $damin->location_id=$request->location_id;
            $damin->building_id=$request->building_id;
            $damin->department_id=$request->department_id;
            $damin->device_id=$request->device_id;
            $damin->room_id=$request->room_id;
            $damin->name=$request->name;
            $damin->meeting_id=$request->meeting_id;
            $damin->employee_id=$request->emp_id;
            $damin->email=$request->email;
            $damin->mobile=$request->mobile;
            $damin->gender=$request->gender;
            $damin->office=$request->office; 
            $damin->department=$request->department; 
            $damin->address=$request->address;
            
            if(isset($request->avatar)){
                $damin->image=$request->image;
                $damin->avatar=$request->avatar;

            } else {

                if($request->image_img){
					
				   $attachmant_base = $request->image_img;
				   $file_name_doc = 'image_img_' . time() . '.png'; //generating unique file name;
				   @list($type, $attachmant_base) = explode(';', $attachmant_base);
				   @list(, $attachmant_base) = explode(',', $attachmant_base);
				   if ($attachmant_base != "") {
					  $attachmant_base =   $attachmant_base;
					  $attachmant_base =   $attachmant_base;
					  file_put_contents(public_path().'/uploads/img/'.$file_name_doc, base64_decode($attachmant_base));
					  $damin->image = $attachmant_base;
					  $damin->avatar = $file_name_doc;
					  $filename = $file_name_doc;
				  }
					
                } else{
					if ($request->hasfile('image')) {
						$image = $request->file('image');
						$originalExtension = $image->getClientOriginalExtension();
						$image_s = Image::make($image)->orientate();
						$image_s->resize(230, null, function ($constraint) {
							$constraint->aspectRatio();
						});
						$filename = random_int(9, 999999999) + time() . '.' . $originalExtension;
						$image_s->save(public_path('/uploads/img/'.$filename));
						$damin->image = $image;
						$damin->avatar=$filename;
					}
                }

            }

            $damin->save();
			$last_id=$damin->id;
			if($request->image_img){
				$path = public_path('/uploads/img/' .$filename);
				$data = file_get_contents($path);
				$base64 = base64_encode($data);
			} else {
			if ($request->hasfile('image')) {	
				$path = public_path('/uploads/img/' .$filename);
				$data = file_get_contents($path);
				$base64 = base64_encode($data);
				} else {
					$base64 = $request->image;
				}
			}
				
			if($old_refer_code){
                $refer_code = $old_refer_code->refer_code;
            } else {
                $refer_code = 'VMS00'.$last_id;
            }
			$conferen=ConferenceMeeting::where('id', $last_id)->first();
            $conferen->refer_code=$refer_code;
			$conferen->image=$base64;	
            $conferen->save();
            $var=$this->sendFaceCheck($last_id);
          //  dd($var);
            $var2=$this->sendFaceCheckAlotte($refer_code);
            // dd($var2);

        }
    }


        }

    }

         
		return redirect()->route('conferenceMeetingSuccessfully')->with(['class'=>'success','message'=>'Registered successfully']);	
       // return redirect('')->with(['class'=>'success','message'=>'Registered successfully']);
    }
	
	public function conferenceMeetingSuccessfully(){
	    return view('web.conference-successfully');
	}
	
    public function extract_emails_from($string){
        preg_match_all("/[\._a-zA-Z0-9-]+@[\._a-zA-Z0-9-]+/i", $string, $matches);
        return $matches[0];
    }
    

	
function sendSMSPhone1($url)
{
	$url = str_replace(" ", '%20', $url);
$ch = curl_init();
$timeout = 5;

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);

$data = curl_exec($ch);

curl_close($ch);

return $data;
}
	
    function sendSMSPhone($URL){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $URL);
    $data = curl_exec($ch);
	//	dd($data);
    curl_close($ch);

    return $data;
}

 function createShortLink($url){
        $base_url = route('web.home');
        $link = str_replace($base_url, '', $url);
        $code = random_int(000000, 999999);
        
        $SortLink = new SortLink();
        $SortLink->code = $code;
        $SortLink->link = $link;
        $SortLink->save();
        
        return url('reg/'.$code);
        
        
    }
    
// public function createShortLink($url){      
//         $curl = curl_init();
//         curl_setopt_array($curl, array(
//           CURLOPT_URL => 'https://api-ssl.bitly.com/v4/shorten',
//           CURLOPT_RETURNTRANSFER => true,
//           CURLOPT_ENCODING => '',
//           CURLOPT_MAXREDIRS => 10,
//           CURLOPT_TIMEOUT => 0,
//           CURLOPT_FOLLOWLOCATION => true,
//           CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//           CURLOPT_CUSTOMREQUEST => 'POST',
//           CURLOPT_POSTFIELDS =>'{
//             "group_guid": "",
//             "domain": "bit.ly",
//             "long_url": "'.$url.'"
//           }',
//           CURLOPT_HTTPHEADER => array(
//             'Content-Type: application/json',
//             'Authorization: Bearer 468b36085fa039c29b1398247acf97ea0f62d559'
//           ),
//         ));
    
//         $response = curl_exec($curl);
//         curl_close($curl);
//         return $response;
//       }


    function sendFaceCheck($user_id)
    {
        $user_data = ConferenceMeeting::where('id', $user_id)->first();
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
            "employee_name": "'.$user_data->name.'",
            "employee_id": "'.$user_data->refer_code.'",
            "employee_gender": "'.$gender.'",
            "employee_image": "'.$user_data->image.'",
            "employee_email": "'.$user_data->email.'",
            "employee_contact_number": "'.$user_data->mobile.'",
            "contract_type": "PERMANENT",
            "overtime": "30",
            "status": "ACTIVE",
            "date": "NA"
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
        $devices[]=$this->getDeviceAllocateUser($employee_id);
        
        $devices_name=json_encode($devices);
      //  dd($devices_name);
		
		// $data=['device_name'=>'Facechk 8inch BE:90','office_name'=>'H1'];
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
            'Authorization: bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyX25hbWUiOiJzYW10ZWNoLmFkbWluIiwidHlwZV9vZl91c2VyIjoiQURNSU4iLCJ0b2tlbiI6IiQyYSQwOCQ5SUY1UFV6cGh0bWVRTzJtVWtOdU8ucnU5VUhFaGc4OEtxM3QzMVVxR0VvR2NZR3BnU0VDVyIsImlhdCI6MTY0NTE2MTM4OH0.CRMT7l4iA-Oi0CkxMeemnlsxQJjQI4PPqZDb1jpiKTE',
            'Content-Type: text/plain'
        ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return json_decode($response, true);
    }
    public function getDeviceAllocateUser($user_id){
		//dd($user_id);
        $users=ConferenceMeeting::where('refer_code',$user_id)->first();
        $department_data=Device::where('id',$users->device_id)->first(); 
		$data['device_name']=$department_data->name;
		$data['office_name']=$department_data->office_name;
        return $data;
    }
    function base64_to_jpeg( $base64_string, $output_file ) {
        $ifp = fopen( $output_file, "wb" ); 
        fwrite( $ifp, base64_decode( $base64_string) ); 
        fclose( $ifp ); 
        return( $output_file ); 
    }
}
