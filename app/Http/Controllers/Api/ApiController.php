<?php
namespace App\Http\Controllers\Api;

use Excel;
use DB;
use App\User;
use App\Admin;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use App\Model\UserDetail;
use App\Model\Transaction;
use App\Model\Symptom;
use App\Model\AllVisit;
use App\Model\PanicAlert;
use App\Model\Panic;
use App\Model\UserOtp;
use App\Model\VisitorGroup;
use App\Model\Department;
use App\Events\SmsEvent;
use Illuminate\Support\Facades\Redirect;
use Mail;
use App\Model\Building;
use App\Events\OtpEvent;
use Illuminate\Support\Facades\Crypt;
use App\Model\Country;
use App\Model\State;
use App\Model\City;
use App\Model\Location;
use App\Model\Setting;
use URL;
use QrCode;
use App\Model\VisitorHistory;
use GuzzleHttp\Psr7\Request as Psr7Request;
use Illuminate\Support\Facades\Hash;
class ApiController extends BaseController
{

  public function getCompany(Request $request){
    if(!empty($request->full_url)){
		$arrContextOptions=array(
		"ssl"=>array(
				"verify_peer"=>false,
				"verify_peer_name"=>false,
			),
		); 
		$urls=$request->full_url;
		$response=file_get_contents("https://vztor.sspl22.in/superadmin/public/api/company?cid=".$urls,false, stream_context_create($arrContextOptions));
		$datas=json_decode($response);
		if(empty($datas)){
			$res=array('status'=>'failed','msg'=>'Company Not Found');
			return $res;
		}
		if($datas->status=="failed"){
			$res=array('status'=>'failed','msg'=>$datas->msg);
			return $res;
		}
		return response()->json($datas);
	}else{
		$datas=array('status'=>'failed','msg'=>'Please enter url');
		return json_encode($datas);
	}
  }


  public function getVisitorRecard(Request $request){
	if(!empty($request->company_id) && !empty($request->user_id)){
		$from_date=Carbon::now();
		$end_date=Carbon::today();
		$company_id=$request->company_id;
		
		$all_checkin_visitor= AllVisit::where(['in_status'=>'Yes','out_status'=>'No'])->whereHas('getVisitor', function($query) use($company_id){
			$query->where('company_id',$company_id);
		})->count();
		
		$all_upcoming_visitor= AllVisit::where('date_time','>',Carbon::now())->where(['in_status'=>'No','out_status'=>'No'])->whereHas('getVisitor', function($query) use($company_id){
				$query->where('company_id',$company_id);
			})->count(); 
			
		$all_overstaying_visitor = 0;
		$all_visitors= AllVisit::where(['in_status'=>'Yes'])->with(['getVisitor'=>function($q){
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
		
		
		
		$datss = User::where(['officer_id'=>$request->user_id,'company_id'=>$request->company_id ])->with(['parentDetail'=>function($query){
			$query->select('id','name', 'role_id');
		},'OfficerDetail'=>function($query){
			$query->select('id','name', 'role_id');
		},'getInOutStatus'=>function($q){
			$q->select('id','user_id','in_time','in_device','in_status','out_device','out_time','out_status');
		},'all_visit'=>function($q){
			$q->select('id','user_id','in_time','in_device','in_status','out_device','out_time','out_status');
		}])->where('visite_time','>',Carbon::now())->whereIn('status', [0,1])->orderBy('id', 'DESC');
		$appointments = $datss->get();
		
		$data = ['all_checkin_visitor'=>$all_checkin_visitor,'all_upcoming_visitor'=>$all_upcoming_visitor,'all_overstaying_visitor'=>$all_overstaying_visitor,'all_checkout_visitor'=>$all_checkout_visitor,'appointments'=>$appointments];
		$datas=array('status'=>'200','msg'=>'success','data'=>$data);
	}else{
		$datas=array('status'=>'404','msg'=>'failed','data'=>'');
	}
	return json_encode($datas);
  }

  public function OverStaiyng(Request $request){
	if(!empty($request->company_id) && !empty($request->officer_id)){
		$company_id=$request->company_id;
		$all_visitors= AllVisit::where(['in_status'=>'Yes','officer_id'=>$request->officer_id])->with(['getVisitor'=>function($q){
			$q->select('id','visite_duration','company_id','name','mobile','email', 'status', 'image');
		}])->whereHas('getVisitor', function($query) use($company_id){
			$query->where('company_id',$company_id);
		})->get(); 
		$datas =[];
		foreach($all_visitors as $all){
			$d1 = strtotime($all->in_time);
			$d2 = strtotime($all->out_time);
			$totalSecondsDiff = abs($d1-$d2); 
			$totalMinutesDiff = $totalSecondsDiff/60; 
			if($totalMinutesDiff > $all->getVisitor->visite_duration){
				$datas[]= $all;
			}
		}
		
		$datas=array('status'=>'200','msg'=>'success','data'=>$datas);
		return json_encode($datas);
	}
}

	public function getPreInviteVisitor(Request $request){
		$user_id = $request->user_id;
		$company_id = $request->company_id;

		$data = User::orderBy('id','asc')->select('id','name','mobile','pre_visit_date_time','email', 'status', 'image',  'services',  'refer_code','added_by','created_at','officer_id','app_status')->where(['officer_id'=>$user_id,'company_id'=>$company_id,'status'=>2])->get();
	
		$datas=array('status'=>'200','msg'=>'success','data'=>$data);
		return json_encode($datas);
	}


	public function preInvitationAdd(Request $request){
		$company_id = $request->company_id;	
		$user_id =  @$request->officer_id?@$request->officer_id:$request->user_id;

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
		$store_user->officer_id = $user_id;
		$store_user->added_by = $request->user_id;
		$store_user->company_id = $company_id;
		$store_user->image = @$file_name;
		$store_user->image_base = @$image;
		$store_user->status = 2;
		$store_user->location_id = @$request->location_id;
		$store_user->building_id = @$request->building_id;
		$store_user->department_id = @$request->department_id;
		$store_user->app_status = 'Pending';
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
                        $datas=array('status'=>'200','msg'=>'Pre Invitation successfully done');
                        return json_encode($datas);
                    }else{
                        $visitor_delete = User::where('id',$store_user->id)->delete();
                        $datas=array('status'=>'200','msg'=>$add_status['message'].' on AMS');
						return json_encode($datas);
                       
                    }                                
                }
            }

			$this->emailSendUser($store_user->id,$store_user->pre_invite_pin);
			$datas=array('status'=>'200','msg'=>'Pre Invitation successfully done');
			return json_encode($datas);
		}

		$datas=array('status'=>'200','msg'=>'Pre Invitation successfully done');
		return json_encode($datas);
	}


	public function visitorDetails(Request $request){
		$user_id = $request->user_id;
		$encrypted=Crypt::encryptString($request->user_id);
		$visitor_detail = User::where('id',$user_id)->with(['visitorGroup'=>function($query){
			$query->select('id','user_id','group_name','group_gender','group_mobile','group_id_proof','visitor_id','group_image');
		},'OfficerDetail'=>function($query){
			$query->select('id','name','email','mobile');
			},'OfficerDepartment'=>function($query){
				$query->select('id','name');
			},'Country'=>function($query){
				$query->select('id','name');
			},'State'=>function($query){
				$query->select('id','name');
			},'City'=>function($query){
				$query->select('id','name');
			},'location'=>function($query){
				$query->select('id','name');
			},'building'=>function($query){
				$query->select('id','name');
			},'OrgaCountry'=>function($query){
				$query->select('id','name');
			},'OrgaState'=>function($query){
				$query->select('id','name');
			},'OrgaCity'=>function($query){
				$query->select('id','name');
			}])->first();
			
			    
			$qr_url=url("genrate-slip/".$encrypted);
		
		$datas=array('status'=>'200','msg'=>'Visitor data', 'data'=>$visitor_detail,'qr_url'=>$qr_url);
		return json_encode($datas);

	}


	public function getVisitorReport(Request $request){
		if($request->start_date && $request->end_date){
			$from_date=$request->start_date;
			$end_date=$request->end_date;

			$data = AllVisit::select('id','user_id','in_time','in_device','in_status','out_device','out_time','out_status')->where(['in_status'=>'Yes','out_status'=>'No','officer_id'=>$request->user_id])->with(['getVisitor'=>function($q){
				$q->select('id','name','refer_code','location_id','building_id','department_id')->with(['building'=>function($q){
					$q->select('id','name');
				},'location'=>function($q){
					$q->select('id','name');
				},'OfficerDepartment'=>function($q){
					$q->select('id','name');
				}]);
			}])->whereBetween('created_at',[$from_date,$end_date])->get();

		}else{
			$data = AllVisit::select('id','user_id','in_time','in_device','in_status','out_device','out_time','out_status')->where(['in_status'=>'Yes','out_status'=>'No','officer_id'=>$request->user_id])->with(['getVisitor'=>function($q){
				$q->select('id','name','refer_code','location_id','building_id','department_id')->with(['building'=>function($q){
					$q->select('id','name');
				},'location'=>function($q){
					$q->select('id','name');
				},'OfficerDepartment'=>function($q){
					$q->select('id','name');
				}]);
			}])->get();
		}
		
		

		


		$datas=array('status'=>'200','msg'=>'Visitor data', 'data'=>$data);
		return json_encode($datas);
	}


	public function blockedVisitor(Request $request){
		$user_id = $request->visitor_id;
		$visitor_detail = User::where('id',$user_id)->first();
		$visitor_detail->status =3;
		$visitor_detail->save();
		$datas=array('status'=>'200','msg'=>'User Blocked Successfully');
		return json_encode($datas);
	}

	
	public function UnblockedVisitor(Request $request){
		$user_id = $request->visitor_id;
		$visitor_detail = User::where('id',$user_id)->first();
		$visitor_detail->status =0;
		$visitor_detail->save();
		$datas=array('status'=>'200','msg'=>'User Unblock Successfully');
		return json_encode($datas);
	}
	
	public function blockVisitorList(Request $request){
		$user_id = $request->user_id;
		$company_id = $request->company_id;
		$visitor_detail = User::where(['officer_id'=>$user_id,'company_id'=>$company_id])->with(['visitorGroup'=>function($query){
			$query->select('id','user_id','group_name','group_gender','group_mobile','group_id_proof','visitor_id','group_image');
		},'OfficerDetail'=>function($query){
			$query->select('id','name','email','mobile');
			},'OfficerDepartment'=>function($query){
				$query->select('id','name');
			},'Country'=>function($query){
				$query->select('id','name');
			},'State'=>function($query){
				$query->select('id','name');
			},'City'=>function($query){
				$query->select('id','name');
			},'location'=>function($query){
				$query->select('id','name');
			},'building'=>function($query){
				$query->select('id','name');
			},'OrgaCountry'=>function($query){
				$query->select('id','name');
			},'OrgaState'=>function($query){
				$query->select('id','name');
			},'OrgaCity'=>function($query){
				$query->select('id','name');
			}])->get();
			if($visitor_detail){
				$datas=array('status'=>'200','msg'=>'Visitor data', 'data'=>$visitor_detail);
			}else{
				$datas=array('status'=>'200','msg'=>'Not Found', 'data'=>'');
			}		

			return json_encode($datas);
	}


	public function allVisitorReports(Request $request){
		$user_id = $request->user_id;
		$company_id = $request->company_id;

		$visitor_detail = User::where(['officer_id'=>$user_id,'company_id'=>$company_id])->with(['visitorGroup'=>function($query){
			$query->select('id','user_id','group_name','group_gender','group_mobile','group_id_proof','visitor_id','group_image');
		},'OfficerDetail'=>function($query){
			$query->select('id','name','email','mobile');
			},'OfficerDepartment'=>function($query){
				$query->select('id','name');
			},'Country'=>function($query){
				$query->select('id','name');
			},'State'=>function($query){
				$query->select('id','name');
			},'City'=>function($query){
				$query->select('id','name');
			},'location'=>function($query){
				$query->select('id','name');
			},'building'=>function($query){
				$query->select('id','name');
			},'OrgaCountry'=>function($query){
				$query->select('id','name');
			},'OrgaState'=>function($query){
				$query->select('id','name');
			},'OrgaCity'=>function($query){
				$query->select('id','name');
			},'all_visit'=>function($q){
				$q->select('id','user_id','in_time','in_device','in_status','out_device','out_time','out_status');
			}]);
			
			if($request->status !=''){
				$visitor_detail->where('status', $request->status);
			}
			
			if(@$request->status_in){
				$search =  $request->status_in;
				$visitor_detail->whereHas('all_visit', function($query) use($search){
                    $query->where('in_status',$search);
                });
			}

			$data = $visitor_detail->get();


			if($visitor_detail){
				$datas=array('status'=>'200','msg'=>'all Visitor data', 'data'=>$data);
			}else{
				$datas=array('status'=>'200','msg'=>'Not Found', 'data'=>'');
			}		

			return json_encode($datas);
	}


	public function getAllInVisitor(Request $request){
		$user_id = $request->user_id;
		$company_id = $request->company_id;

		$visitor_detail = User::where(['officer_id'=>$user_id,'company_id'=>$company_id,'status'=>1])->with(['visitorGroup'=>function($query){
			$query->select('id','user_id','group_name','group_gender','group_mobile','group_id_proof','visitor_id','group_image');
		},'OfficerDetail'=>function($query){
			$query->select('id','name','email','mobile');
			},'OfficerDepartment'=>function($query){
				$query->select('id','name');
			},'Country'=>function($query){
				$query->select('id','name');
			},'State'=>function($query){
				$query->select('id','name');
			},'City'=>function($query){
				$query->select('id','name');
			},'location'=>function($query){
				$query->select('id','name');
			},'building'=>function($query){
				$query->select('id','name');
			},'OrgaCountry'=>function($query){
				$query->select('id','name');
			},'OrgaState'=>function($query){
				$query->select('id','name');
			},'OrgaCity'=>function($query){
				$query->select('id','name');
			},'all_visit'=>function($q){
				$q->select('id','user_id','in_time','in_device','in_status','out_device','out_time','out_status');
			}]);
			
				$search =  'Yes';
				$visitor_detail->whereHas('all_visit', function($query) use($search){
                    $query->where('in_status',$search);
                });

			$data = $visitor_detail->get();


			if($visitor_detail){
				$datas=array('status'=>'200','msg'=>'all Visitor data', 'data'=>$data);
			}else{
				$datas=array('status'=>'200','msg'=>'Not Found', 'data'=>'');
			}		

			return json_encode($datas);

	}
	
	public function sendPanicAlert(Request $request){
		$user_id = $request->user_id;
		$company_id = $request->company_id;
		$visitor_id = $request->visitor_id;

		$user_details = User::Where(['id'=>$visitor_id])->first();
		$admin_detail = Admin::Where(['role_id'=>1,'company_id'=>$company_id])->first();
		$officer_details = Admin::Where(['id'=>$user_id])->with(['getDepart'=>function($query){
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
		$panic_data->visitor_id = $visitor_id;
		$panic_data->save();
	
		$all_emergency_lists = PanicAlert::where('officer_id',$user_details->officer_id)->get();
		if(count($all_emergency_lists)>0){
		  foreach($all_emergency_lists as $emr){
			array_push($all_emails,$emr->email);
			array_push($all_mobiles,$emr->mobile);
		  }
		}
		foreach($all_mobiles as $mobile){
		   event(new SmsEvent($mobile, 'Panic Alert : '.$officer_details->name.' ( '.$officer_details->mobile.') from ( '.$officer_details->getDepart->name.' ) has initiated a panic alert. Please respond quickly.'));
		}
	
		foreach($all_emails as $email){
			$name = $officer_details->name;
			$offi_mobile = $officer_details->mobile;
			$depart = $officer_details->getDepart->name;
			// send mail to patient
			$data=['officer_name'=>$name,'officer_mobile'=>$offi_mobile,'officer_dep'=>$depart];
			Mail::send('mails.panic-alert', $data, function($message) use ($email){
			  $sub = Carbon::now()->toDateTimeString();
			  $message->subject('Panic Alert ('.$sub.')');
			  $message->from('vztor.in@gmail.com','Panic Alert  ('.$sub.')');
			  $message->to($email, 'Panic Alert ('.$sub.')');
			});
		}
		$datas=array('status'=>'200','msg'=>'Panic Alert send Successfully');
		return json_encode($datas);
	}

	public function panicAlerts(Request $request){
		$all_alert =Panic::where(['officer_id'=>$request->user_id])->with(['getVisitor'=>function($q){
			$q->select('id','name','mobile','pre_visit_date_time','email', 'status', 'image',  'services',  'refer_code','added_by','created_at','officer_id')->with(['visitorGroup'=>function($query){
				$query->select('id','user_id','group_name','group_gender','group_mobile','group_id_proof','visitor_id','group_image');
			},'OfficerDetail'=>function($query){
				$query->select('id','name','email','mobile');
				},'OfficerDepartment'=>function($query){
					$query->select('id','name');
				},'Country'=>function($query){
					$query->select('id','name');
				},'State'=>function($query){
					$query->select('id','name');
				},'City'=>function($query){
					$query->select('id','name');
				},'location'=>function($query){
					$query->select('id','name');
				},'building'=>function($query){
					$query->select('id','name');
				},'OrgaCountry'=>function($query){
					$query->select('id','name');
				},'OrgaState'=>function($query){
					$query->select('id','name');
				},'OrgaCity'=>function($query){
					$query->select('id','name');
				},'all_visit'=>function($q){
					$q->select('id','user_id','in_time','in_device','in_status','out_device','out_time','out_status');
				}]);
		}])->get();
		$datas=array('status'=>'200','msg'=>'Panic Alert lists','data'=>$all_alert);
		return json_encode($datas);
	}


	public function emergencyList(Request $request){
		$all_alert =PanicAlert::where(['officer_id'=>$request->user_id,'company_id'=>$request->company_id])->get();
		$datas=array('status'=>'200','msg'=>'emergency contact list','data'=>$all_alert);
		return json_encode($datas);
	}


	public function addEmergencyContact(Request $request)
    {        
        $panic = new PanicAlert;
        $panic->name = $request->name;
        $panic->mobile = $request->mobile;
        $panic->email = $request->email;
        $panic->officer_id = $request->user_id;
        $panic->status = 1;        
        $panic->company_id = $request->company_id;
        
		if($panic->save()){
			$datas=array('status'=>'200','msg'=>'emergency contact add successfully');
		}else{
			$datas=array('status'=>'200','msg'=>'Service in under maintenance');
		}        
		return json_encode($datas);

    }

	public function updateEmergencyContact(Request $request)
    {
        
        $panic_update = PanicAlert::where('id', $request->id)->first();
        $panic_update->name = $request->name;
        $panic_update->mobile = $request->mobile;
        $panic_update->email = $request->email;
		$panic_update->status =  $request->status; 
        if($panic_update->save()){
			$datas=array('status'=>'200','msg'=>'emergency contact update successfully');
		}else{
			$datas=array('status'=>'200','msg'=>'Service in under maintenance');
		}        
		return json_encode($datas);
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
      
		$officer_name = ucfirst(@$office_details->name);
      
        event(new SmsEvent($user_details->mobile, 'You are invited to visit '.$user_details->building->name.'('.$user_details->location->name.') on '.$appoint_date.' at '.$appoint_time.'. Pin '.$pin.' click here '.$res->link.'. VMS Team'));
	
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
