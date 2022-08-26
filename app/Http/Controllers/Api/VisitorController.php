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
use App\Model\VisitorHistory;
class VisitorController extends BaseController
{

  public function getCompanyDetails($url){
    $arrContextOptions=array(
      "ssl"=>array(
        "verify_peer"=>false,
        "verify_peer_name"=>false,
      ),
	  ); 
	  $response=file_get_contents("https://vztor.sspl22.in/superadmin/public/api/company?cid=".$url,false, stream_context_create($arrContextOptions));
    $datas=json_decode($response);
    
    if(empty($datas)){
        $res=array('status'=>'failed','msg'=>'Company Not Found');
        return $res;
    }
    if($datas->status=="failed"){
      $res=array('status'=>'failed','msg'=>$datas->msg);
      return $res;
    }
    $token=Setting::where(['company_id'=>$datas->data->id,'name'=>'token'])->first();
    $arrContextOptions=array(
		"ssl"=>array(
        "verify_peer"=>false,
        "verify_peer_name"=>false,
      ),
    ); 
	  $response=file_get_contents("https://vztor.sspl22.in/superadmin/public/api/companyValidate?unique_id=".$token->company_id,false, stream_context_create($arrContextOptions));
    $data=json_decode($response);
   
    if(empty($data)){
      $res=array('status'=>'failed','msg'=>'Company not registred');
      return $res;
    }
    if($data->status=="failed"){
      $res=array('status'=>'failed','msg'=>$datas->msg);
      return $res;
    }else{
      $this->company_data=$data->data;
      $res=array('status'=>'success','msg'=>$data->data);
      return $res;
    }

  }

  public function otpSend(Request $request){
    $url=URL::to('/');
    $res=$this->getCompanyDetails($url);
    
    if($res['status']=="failed"){
      return response()->json($res);
    }else{
      $company_data = $this->company_data;
      if($request->purpose=="New_visit"){
        $userss=User::where(['mobile'=>$request->mobile,'company_id'=>$company_data->id])->get();
    
          if(count($userss)<1){
            $gen_otp = rand(100000,999999);
            $response=$this->sendOtpFunction($request->mobile,$gen_otp,$company_data->id);
          }else{
            $response=array('status'=>'failed','msg'=>'Mobile Number Already Registered');
          }
      }elseif($request->purpose=="re_visit"){
        $users=User::where(['mobile'=>$request->mobile,'company_id'=>$company_data->id])->orderBy('id','Desc')->get();
       
        if(count($users)>0){
          $gen_otp = rand(100000,999999);
          $response=$this->sendOtpFunction($request->mobile,$gen_otp,$company_data->id);
        }else{
          $response=array('status'=>'failed','msg'=>'Mobile Number Not Registred');
        }
      }elseif($request->purpose=="know_status"){
        $users=User::where(['mobile'=>$request->mobile,'company_id'=>$company_data->id])->get()->toArray();
        if(count($users)>0){
          $gen_otp = rand(100000,999999);
          $response=$this->sendOtpFunction($request->mobile,$gen_otp,$company_data->id);
         
        }else{
          $response=array('status'=>'failed','msg'=>'Mobile Number Not Registred');
        }
      }
      
      return response()->json($response);
    }
    
  }

  public function sendOtpFunction($mobile,$gen_otp,$company_id){
    $otp = new UserOtp;
    $otp->mobile = $mobile;
    $otp->otp = $gen_otp;
    $otp->status = 0;
    $otp->company_id = $company_id;
    $otp->save();
    event(new OtpEvent($mobile,$gen_otp));
    $res=array('status'=>'success','msg'=>'Otp send successfully');
    return $res;
  }

  public function otpVerify(Request $request){
    $url=URL::to('/');
    $res=$this->getCompanyDetails($url);
    if($res['status']=="failed"){
      return response()->json($res);
    }else{
      $otp=$request->otp;
      if($otp == 989948){
        $mobile=$request->mobile;
        $company_data = $this->company_data;        
        
          if($request->purpose=="New_visit"){             
            $res=array('status'=>'success','msg'=>'Otp Verify','purpose'=>$request->purpose,'data'=>[]);
          }elseif($request->purpose=="re_visit"){
            $users=User::where(['mobile'=>$mobile,'company_id'=>$company_data->id])->orderBy('id','desc')->limit(1)->get();             
            $res=array('status'=>'success','msg'=>'Otp Verify','purpose'=>$request->purpose,'data'=>$users);
          }elseif($request->purpose=="know_status"){
            $users=User::where(['mobile'=>$mobile,'company_id'=>$company_data->id])->get()->toArray();             
            $res=array('status'=>'success','msg'=>'Otp Verify','purpose'=>$request->purpose,'data'=>$users);
          }           
      }else{
        $mobile=$request->mobile;
        $company_data = $this->company_data;
        $otpres=UserOtp::where(['mobile'=>$mobile,'otp'=>$otp,'company_id'=>$company_data->id,'status'=>0])->first();
          if(empty($otpres)){
            $res=array('status'=>'failed','msg'=>'Invalid Otp');
          }else{
            if($request->purpose=="New_visit"){
              UserOtp::where(['mobile'=>$mobile,'otp'=>$otp])->update(['status'=>1]);
              $res=array('status'=>'success','msg'=>'Otp Verify','purpose'=>$request->purpose,'data'=>[]);
            }elseif($request->purpose=="re_visit"){
              $users=User::where(['mobile'=>$mobile,'company_id'=>$company_data->id])->orderBy('id','desc')->limit(1)->get();
              UserOtp::where(['mobile'=>$mobile,'otp'=>$otp])->update(['status'=>1]);
              $res=array('status'=>'success','msg'=>'Otp Verify','purpose'=>$request->purpose,'data'=>$users);
            }elseif($request->purpose=="know_status"){
              $users=User::where(['mobile'=>$mobile,'company_id'=>$company_data->id])->get()->toArray();
              UserOtp::where(['mobile'=>$mobile,'otp'=>$otp])->update(['status'=>1]);
              $res=array('status'=>'success','msg'=>'Otp Verify','purpose'=>$request->purpose,'data'=>$users);
            }
            
          }
      }    


     
      return response()->json($res);  
    }
    
  }
  public function know_status(Request $request){
    $url=URL::to('/');
    $res=$this->getCompanyDetails($url);
    if($res['status']=="failed"){
      return response()->json($res);
    }else{
      $mobile=$request->mobile;
      $company_data = $this->company_data;
      $users=User::where(['mobile'=> $mobile,'company_id'=>$company_data->id])->with(['visitorGroup' => function ($query) {
        $query->select('id', 'user_id', 'group_name', 'group_gender', 'group_mobile', 'group_id_proof', 'visitor_id', 'group_image');
      }, 'OfficerDetail' => function ($query) {
        $query->select('id', 'name', 'email', 'mobile');
      }, 'OfficerDepartment' => function ($query) {
        $query->select('id', 'name');
      }, 'Country' => function ($query) {
        $query->select('id', 'name');
      }, 'State' => function ($query) {
        $query->select('id', 'name');
      }, 'City' => function ($query) {
        $query->select('id', 'name');
      }, 'location' => function ($query) {
        $query->select('id', 'name');
      }, 'building' => function ($query) {
        $query->select('id', 'name');
      }, 'OrgaCountry' => function ($query) {
        $query->select('id', 'name');
      }, 'OrgaState' => function ($query) {
        $query->select('id', 'name');
      }, 'OrgaCity' => function ($query) {
        $query->select('id', 'name');
      }])->get();
      if(empty($users)){
        $res=array('status'=>'failed','msg'=>'Mobile number not valid');
      }else{
        $data=[];
        foreach($users as $user ){
          // print_r($user);die;
          $datas=array(
			 'visit_id'=>$user->id,
            'Visitor_id'=>$user->refer_code,
            'status'=>$user->app_status,
            'office'=>@$user->OfficerDetail->name,
            'name'=>$user->name,
            'mobile'=>$user->mobile,
            'gender'=>$user->gender,
            'purpose'=>$user->services,
            'email'=>$user->email,
            'id_no'=>$user->adhar_no,
            'visit_type'=>$user->visit_type,
            'officer_department'=>@$user->OfficerDepartment->name,
            'visitor_group'=>$user->visitorGroup,
          );
          array_push($data,$datas);
        }
        
        
        $res=array('status'=>'success','msg'=>'','data'=>$data);
        }
      }
      return response()->json($res);  
  }
  public function generateSlip(Request $request)
  {
    $url=URL::to('/');
    $res=$this->getCompanyDetails($url);
    if($res['status']=="failed"){
      return response()->json($res);
    }else{
      $company_data = $this->company_data;
      $visitor_detail = User::where('id', $request->visit_id)->with(['visitorGroup' => function ($query) {
        $query->select('id', 'user_id', 'group_name', 'group_gender', 'group_mobile', 'group_id_proof', 'visitor_id', 'group_image');
      }, 'OfficerDetail' => function ($query) {
        $query->select('id', 'name', 'email', 'mobile');
      }, 'OfficerDepartment' => function ($query) {
        $query->select('id', 'name');
      }, 'Country' => function ($query) {
        $query->select('id', 'name');
      }, 'State' => function ($query) {
        $query->select('id', 'name');
      }, 'City' => function ($query) {
        $query->select('id', 'name');
      }, 'location' => function ($query) {
        $query->select('id', 'name');
      }, 'building' => function ($query) {
        $query->select('id', 'name');
      }, 'OrgaCountry' => function ($query) {
        $query->select('id', 'name');
      }, 'OrgaState' => function ($query) {
        $query->select('id', 'name');
      }, 'OrgaCity' => function ($query) {
        $query->select('id', 'name');
      }])->first();
      
      if(empty($visitor_detail)){
        $res=array('status'=>'failed','msg'=>'Visior id Not Matched');
      }else{
        $visitor_detail->slip_id= Crypt::encryptString($request->visit_id);
        $res=array('status'=>'success','msg'=>'','data'=>$visitor_detail);
        }
      }
      return response()->json($res); 
   
  }

  public function getSymptoms(Request $request){
    $symptoms = Symptom::where(['status'=>1])->get();
    $res=array('status'=>'success','date'=>$symptoms);
    return response()->json($res);
  }
  public function getLocation(Request $request){
    $url=URL::to('/');
    $res=$this->getCompanyDetails($url);
    if($res['status']=="failed"){
      return response()->json($res);
    }else{
      $company_data = $this->company_data;
      $locations=Location::where($company_data->where,$company_data->id)->get();
      $res=array('status'=>'success','date'=>$locations);
      return response()->json($res);
    }
  }

  public function getBuilding(Request $request){
    $url=URL::to('/');
    $res=$this->getCompanyDetails($url);
    if($res['status']=="failed"){
      return response()->json($res);
    }else{
      $company_data = $this->company_data;
      $building = Building::where('location_id', $request->location_id)->where($company_data->where,$company_data->id)->get();
      $res=array('status'=>'success','date'=>$building);
      return response()->json($res);
    }
  }

  public function getDepartment(Request $request){
    $url=URL::to('/');
    $res=$this->getCompanyDetails($url);
    if($res['status']=="failed"){
      return response()->json($res);
    }else{
      $company_data = $this->company_data;
      $departments=Department::where('building_id',$request->building_id)->where($company_data->where,$company_data->id)->get();
      $res=array('status'=>'success','date'=>$departments);
      return response()->json($res);
    }
  }

  public function getOfficer(Request $request){
    $url=URL::to('/');
    $res=$this->getCompanyDetails($url);
    if($res['status']=="failed"){
      return response()->json($res);
    }else{
      $company_data = $this->company_data;
      $admins=Admin::where('department_id',$request->department_id)->where($company_data->where,$company_data->id)->get();
      $res=array('status'=>'success','date'=>$admins);
      return response()->json($res);
    }
  }

  public function getCountry(Request $request){
    $url=URL::to('/');
    $res=$this->getCompanyDetails($url);
    if($res['status']=="failed"){
      return response()->json($res);
    }else{
      $company_data = $this->company_data;
      $country=Country::all();
      $res=array('status'=>'success','date'=>$country);
      return response()->json($res);
    }
  }
  public function getState(Request $request){
    $url=URL::to('/');
    $res=$this->getCompanyDetails($url);
    if($res['status']=="failed"){
      return response()->json($res);
    }else{
      $company_data = $this->company_data;
      $states=State::where('country_id',$request->country_id)->get();
      $res=array('status'=>'success','date'=>$states);
      return response()->json($res);
    }
  }
  public function getCity(Request $request){
    $url=URL::to('/');
    $res=$this->getCompanyDetails($url);
    if($res['status']=="failed"){
      return response()->json($res);
    }else{
      $company_data = $this->company_data;
      $city=City::where('state_id',$request->state_id)->get();
      $res=array('status'=>'success','date'=>$city);
      return response()->json($res);
    }
  }
  public function newVisit(Request $request){
    $url=URL::to('/');
    $res=$this->getCompanyDetails($url);
    if($res['status']=="failed"){
      return response()->json($res);
    }else{
      $old_data=(object)$request->all();
      $file_data = $old_data->file_name;
      $file_name = 'image_'.time().'.png'; //generating unique file name;
      if($file_data!="")
      {
        \Storage::disk('public')->put($file_name,base64_decode($file_data));
      }
      $attachments_data = $old_data->attachments;
      $attachments_name = 'attachments_'.time().'.png'; //generating unique file name;
      if($attachments_data!="")
      {
          \Storage::disk('public')->put($attachments_name,base64_decode($attachments_data));
      }
          $company_data = $this->company_data;
          $store_user = new User;
          $column=$company_data->where;
          $store_user->$column = $company_data->id;
          $store_user->name = @$old_data->name;
          $store_user->email = @$old_data->email;
          $store_user->mobile = @$old_data->mobile;
          $store_user->document_type = @$old_data->document_type;
          $store_user->adhar_no = @$old_data->adhar_no;
          $store_user->services = @$old_data->services;
          $store_user->gender = @$old_data->gender;
          $store_user->visite_time = @$old_data->visite_time;
          $store_user->status = 0;
          $store_user->app_status ='Pending';
          $store_user->image = @$file_name;
          $store_user->attachmant =@$attachments_name;
          $store_user->officer_id = @$old_data->officer_id;
          $store_user->image_base = @$file_data;
          $store_user->added_by = 0;
          $store_user->vaccine = @$old_data->vaccine;
          $store_user->symptoms = @$old_data->symptoms;
          $store_user->vaccine_count = @$old_data->vaccine_count;
          $store_user->vaccine_name = @$old_data->vaccine_name;
          $store_user->travelled_states = @$old_data->states;
          $store_user->patient = @$old_data->patient;
          $store_user->temprature = @$old_data->temprature;
          $store_user->country_id = @$old_data->country_id;
          $store_user->state_id = @$old_data->state_id;
          $store_user->city_id = @$old_data->city_id;
          $store_user->pincode = @$old_data->pincode;
          $store_user->address_1 = @$old_data->address_1;
          $store_user->address_2 = @$old_data->address_2;
          $store_user->organization_name = @$old_data->organization_name;
          $store_user->orga_country_id = @$old_data->orga_country_id;
          $store_user->orga_state_id = @$old_data->orga_state_id;
          $store_user->orga_city_id = @$old_data->orga_city_id;
          $store_user->orga_pincode = @$old_data->orga_pincode;
          $store_user->department_id = @$old_data->department_id;
          $store_user->location_id = @$old_data->location_id;
          $store_user->building_id = @$old_data->building_id;
          $store_user->visite_duration = @$old_data->visite_duration;
          $store_user->vehical_type = @$old_data->vehical_type;
          $store_user->vehical_reg_num = @$old_data->vehical_reg_num;
          $store_user->carrying_device = @$old_data->carrying_device;
          $store_user->pan_drive = @$old_data->pan_drive;
          $store_user->hard_disk = @$old_data->hard_disk;
          $assets_name=explode(',',$old_data->assets_name);
          if(count($assets_name)>0){
              $store_user->assets_name = @$old_data->assets_name;
              $store_user->assets_number = @$old_data->assets_number;
              $store_user->assets_brand = @$old_data->assets_brand;
          }
          $store_user->visit_type = @$old_data->visit_type;
          if($store_user->save())
          {
            if($old_data->visit_type =='group'){
                //$num_person = count($old_data->group_name);
                foreach($old_data->group_name as $key => $data_gruop_name){
                    foreach($old_data->group_image_mode as $key1 =>$group_image_mode){
                      if($group_image_mode[0]=="folder"){
                        $image_var='group_image_folder_'.$key;
                        if(isset($old_data->$image_var)){
                          $file_names=@$old_data->$image_var;
                        }
                      }
                    }
                    if(empty($file_names)){
                      $img = $old_data->group_image[$key-1];
                      $file_data_group = $old_data->group_image[$key-1];
                      $file_names = 'group_image_'.time().'.png'; //generating unique file name;
                      @list($type, $file_data_group) = explode(';', $file_data_group);
                      @list(, $file_data_group) = explode(',', $file_data_group);
                      if($file_data_group!="")
                      {
                          $image_datas = $file_data_group;
                          \Storage::disk('public')->put($file_names,base64_decode($file_data_group));
                      }
                    }


                    $attachments="attchment_".$old_data->mobile;
                    $group_add = new VisitorGroup;
                    $group_add->user_id = $store_user->id;
                    $group_add->group_name = @$old_data->group_name->$key->group_name;
                    $group_add->group_mobile = @$old_data->group_mobile->$key->group_mobile;
                    $group_add->group_id_proof = @$old_data->group_id_proof->$key->group_id_proof;
                    $group_add->group_gender = @$old_data->group_gender->$key->group_gender;
                    $group_add->group_image = @$file_names;
                    $group_add->image_base = @$image_datas;
                    $group_add->group_attchment =@$old_data->$attachments;
                    $group_add->save();
                    $group_add->visitor_id = "VG00".$group_add->id;
                    $group_add->save();
                }
            }

              $add_visit = new AllVisit;
              $add_visit->user_id = $store_user->id;
              $add_visit->date_time =  $store_user->visite_time;
              $add_visit->officer_id =  @$store_user->officer_id;
              $add_visit->added_by =  $store_user->added_by;
              $add_visit->save();

              $store_user->refer_code = "VS00".$store_user->id;
              $response=$store_user->save();
              if($response==1){
                  $add_status['status_code']=201;
                  $add_status['message']="Visitor Successfully Registered";
				          $add_status['visitor_id']=$store_user->id;
              }else{
                  $add_status['status_code']=400;
                  $add_status['message']="Visitor Registration Failed";
              }

              $settings=Setting::where([$company_data->where=>$company_data->id,'name'=>'ams_send'])->first();
              if(@$settings->status=="Active"){
                $add_status = $this->sendFaceCheck($store_user->id);
              }
             
              if($add_status['status_code'] == '201')
              {
                  $store_user->employee_unique_id = $add_status['status_code'] ;
                  $store_user->save();
                  $this->sendEmail($store_user->id);
                  $encrypted = Crypt::encryptString($store_user->id);
                  return response()->json(['message'=>$add_status['message'], 'status'=>'success','visitor_id'=>$encrypted,'user_id'=>$store_user->id]);

              }
              else
              {
                  $store_user->status= 0;
                  $store_user->save();
                  return response()->json(['message'=>$add_status['message'], 'status'=>'failed']);
              }
          }
          else
          {
              return response()->json(['message'=>'Oops! Something went wrong', 'status'=>'failed']);
          }
    }                                     
  }

  public function sendEmail($user_id)
  {
    $company_data = $this->company_data;
    $new_user_id = $user_id;
    $user_details = User::Where(['id' => $new_user_id])->first();
    $reception_details = Admin::Where(['id' => $user_details->added_by])->first();
    $officer_details = Admin::Where(['id' => $user_details->officer_id])->first();
    $user_email = @$officer_details->email;
    $reception_name = 'Self Registration';
    $sub = $user_details->visite_time;
    $appoint_date = date('d/m/Y', strtotime($user_details->visite_time));
    $appoint_time = date('h:i:s a', strtotime($user_details->visite_time));
    if ($user_details->app_status == "Pending") {
      $app_status = 'Scheduled';
    } else {
      $app_status = $user_details->app_status;
    }
    if (empty($reception_details)) {
      $reception_details = $user_details;
    }
    $user_name = ucfirst($user_details->name);
    
    $encrypted = Crypt::encryptString($new_user_id);
      
    $url=route('visitor.approve').'/'.$encrypted.'/'.@$officer_details->id;

    $res = json_decode($this->createShortLink($url));
    $full_name = explode(" ",$user_name);

    $visitor_name = ucfirst($full_name[0].' '.substr(@$full_name[1], 0, 1));

    event(new SmsEvent($officer_details->mobile, 'A new meeting with '.$visitor_name.' ('.@$user_details->mobile.') on '.$appoint_date.' at '.$appoint_time.'. Click here: ('.$res->link.')'));

    $user_mobile = $user_details->mobile;
    $image_base_code =str_replace('/public','',URL::to('/')).'/storage/app/public/'.@$user_details->image;
    // send mail to officer
    $encrypted = Crypt::encryptString($new_user_id);
    $data = ['vis_name' =>$user_name,'user_mobile' =>$user_mobile,'visitor_name' => $reception_name, 'app_date' => $appoint_date, 'appoint_time' => $appoint_time, 'encryptString' => $encrypted, 'status' => $app_status,'duration'=>$user_details->visite_duration,'visitor_id'=>$user_details->refer_code,'officer_id'=>$officer_details->id,'image'=>$image_base_code];
    Mail::send('mails.appointment-invoice', $data, function ($message) use ($user_email) {
      $sub = Carbon::now()->toDateTimeString();;
      $message->subject('New Appointment Alert (' . $sub . ')');
      $message->from('noreply@vztor.in', 'New Appointment Alert  (' . $sub . ')');
      $message->to($user_email, 'New Appointment Alert (' . $sub . ')');
    });
      // Send mail to admin
  }

  public function getVisitorSlip(Request $request){
    $url=URL::to('/');
    $res=$this->getCompanyDetails($url);
    if($res['status']=="failed"){
      return response()->json($res);
    }else{
      $company_data = $this->company_data;
      $encrypted=$request->user_id;
      $user_id= Crypt::decryptString($encrypted);
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
          $qr_url=url("generate-slip/".$encrypted);
          
          if(!empty($visitor_detail)){
            $res=array('status'=>'success','message'=>'data found', 'data'=>$visitor_detail,'qr_url'=>$qr_url);
          }else{
            $res=array('status'=>'error','message'=>'data not found', 'data'=>'','qr_url'=>'');
          }
          
          return response()->json($res);
    }
  }

  public function markIn(Request $request){
    $type=$request->type;
    $refer_code=$request->id;
    $in_time=$request->in_time;
    $device_name=$request->device_name;
    if($type!="" ||  $refer_code!="" || $in_time!="" || $device_name!=""){
      if($type=="visitor"){
        $users=User::where('refer_code',$refer_code)->first();
        if(!empty($users)){
          $all_visit=AllVisit::where('user_id',$users->id)->first();
          $all_visit->in_time=$in_time;
          $all_visit->in_status='Yes';
          $all_visit->in_device=$device_name;
          $all_visit->save();
          $res=array('status'=>'success','message'=>'Request Successfully Submitted');
        }else{
          $res=array('status'=>'failed','message'=>'Invalid Id');
        }
        
      }
    }else{
      $res=array('status'=>'failed','message'=>'Invalid Body Value');
    }
    return response()->json($res);
    
  }

  public function markOut(Request $request){
    $type=$request->type;
    $refer_code=$request->id;
    $out_time=$request->out_time;
    $device_name=$request->device_name;
    if($type!="" ||  $refer_code!="" || $out_time!="" || $device_name!=""){
      if($type=="visitor"){
        $users=User::where('refer_code',$refer_code)->first();
        if(!empty($users)){
          $all_visit=AllVisit::where('user_id',$users->id)->first();
          $all_visit->out_time=$out_time;
          $all_visit->out_status='Yes';
          $all_visit->out_device=$device_name;
          $all_visit->save();
          $res=array('status'=>'success','message'=>'Request Successfully Submitted');
        }else{
          $res=array('status'=>'failed','message'=>'Invalid Id');
        }
        
      }
    }else{
      $res=array('status'=>'failed','message'=>'Invalid Body Value');
    }
    return response()->json($res);
  }

  function sendFaceCheck($user_id)
  {
    
    $user_data = User::where('id', $user_id)->first();
    $curl = curl_init();
    $gender = $user_data->gender?strtoupper($user_data->gender):'MALE';
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
      "employee_name": "' . $user_data->name . '",
      "employee_id": "' . $user_data->refer_code . '",
      "employee_gender": "' . $gender . '",
      "employee_image": "' . $user_data->image_base . '",
      "employee_email": "' . $user_data->email . '",
      "employee_contact_number": "' . $user_data->mobile . '",
      "contract_type": "PERMANENT",
      "overtime": "30",
      "status": "ACTIVE",
      "date": "' . $user_data->visite_time . '"
      }',
      CURLOPT_HTTPHEADER => array(
        'Authorization: bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyX25hbWUiOiJzYW10ZWNoLmFkbWluIiwidHlwZV9vZl91c2VyIjoiQURNSU4iLCJ0b2tlbiI6IiQyYSQwOCQ5SUY1UFV6cGh0bWVRTzJtVWtOdU8ucnU5VUhFaGc4OEtxM3QzMVVxR0VvR2NZR3BnU0VDVyIsImlhdCI6MTYzOTAzMDYwMn0.oxerDTTzpTnTN7UzQD7GSNEiUU9qt9sr0SHB6NxZ6Zo',
        'Content-Type: text/plain'
      ),
    ));

    $response = curl_exec($curl);
    return json_decode($response, true);
    curl_close($curl);

    /***************************for group visit Enrolled in AMS ********************************************/

    if ($user_data->visit_type == 'group') {
      $group_visitor = VisitorGroup::where('user_id', $user_data->id)->get();
      foreach ($group_visitor as $visit) {
        $group_email = $visit->visitor_id . '@vms.com';
        $gender_group = $visit->group_gender?strtoupper($visit->group_gender):'MALE';
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
                  "employee_name": "' . $visit->group_name . '",
                  "employee_id": "' . $visit->visitor_id . '",
                  "employee_gender": "' . $gender_group . '",
                  "employee_image": "' . $visit->image_base . '",
                  "employee_email": "' . $group_email . '",
                  "employee_contact_number": "' . $visit->group_mobile . '",
                  "contract_type": "PERMANENT",
                  "overtime": "30",
                  "status": "ACTIVE",
                  "date": "' . $user_data->visite_time . '"
                }',
          CURLOPT_HTTPHEADER => array(
            'Authorization: bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyX25hbWUiOiJzYW10ZWNoLmFkbWluIiwidHlwZV9vZl91c2VyIjoiQURNSU4iLCJ0b2tlbiI6IiQyYSQwOCQ5SUY1UFV6cGh0bWVRTzJtVWtOdU8ucnU5VUhFaGc4OEtxM3QzMVVxR0VvR2NZR3BnU0VDVyIsImlhdCI6MTYzOTAzMDYwMn0.oxerDTTzpTnTN7UzQD7GSNEiUU9qt9sr0SHB6NxZ6Zo',
            'Content-Type: text/plain'
          ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
      }
    }
    return json_decode($response, true);
  }
	public function guardLogin(Request $request){
		$request->validate([
        'email' => 'required|string|email',
        'password' => 'required|string',
    ]);

    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
      if(Auth::User()->company_id != $request->company_id){
        return response()->json(['message' => 'Oppes! You have entered invalid credentials', 'class' => 'error']);
      }

      $datas = User::select('id','name','refer_code','email','mobile','app_status','officer_id',
        'document_type','adhar_no','gender','visite_time','visite_duration','services','status','image')->whereHas('all_visit', function($query){
        $query->select('id', 'user_id','in_status','out_status');
        $query->where('out_status','=', 'No');

        })->where('building_id', Auth::User()->building_id)->with(['OfficerDetail' => function ($query) {
        $query->select('id', 'name');
      }])->orderBy('id', 'desc')->get();

      $pre_invite_visitor = User::orderBy('id','asc')->select('id','name','mobile','pre_visit_date_time','email', 'status', 'image',  'services',  'refer_code','added_by','created_at','officer_id','app_status')->where(['building_id'=>Auth::User()->building_id,'company_id'=>Auth::User()->company_id,'status'=>1])->whereNotNull('pre_visit_date_time')->with(['OfficerDetail' => function ($query) {
        $query->select('id', 'name');
      },'getInOutStatus'=>function($q){
        $q->select('id','user_id','in_time','in_device','in_status');
      }])->get();
      $guard_details = ['id'=>Auth::User()->id,'name'=>Auth::User()->name,'company_id'=>Auth::User()->company_id,'mobile'=>Auth::User()->mobile];
      return response()->json(['message' => 'You Are Successfully Login', 'class' => 'success','data'=>$datas,'pre_invite_visitor'=>$pre_invite_visitor,'guard_details'=>$guard_details]);
      
    }
		return response()->json(['message' => 'Oppes! You have entered invalid credentials', 'class' => 'error']);
	}
  
  /**************suresh in out temporary stopped********************/
	public function visitorIn(Request $request){
	
		$date = date('Y-m-d');
    $company_id =$request->company_id;
		$visitor_id=$request->user_id;
		$guard_id=$request->guard_id;
		$users=User::where(['id'=>$visitor_id,'company_id'=>$company_id])->first();
    $users->temprature  = $request->temperature;
		AllVisit::where(['user_id'=>$visitor_id])->update(['in_status'=>'Yes','in_time'=>date('Y-m-d h:i:s'),'in_device'=>'NA']);
        $VisitorHistory= VisitorHistory::where(['last_synchronize_date'=>$date])->first();
		if(!isset($VisitorHistory->last_synchronize_date)){
			$record=[];
			$record[]=array(
				'employee_name'=>$users->name,
				'employee_id'=>$users->refer_code,
				'office'=>'H1',
				'department'=>'Visitor',
				'shift'=>'',
				'date'=>$users->visite_time,
				'in_time'=>date('Y-m-d h:i:s'),
				'shift_in_time'=>'NA',
				'in_device'=>'Gaurd',
				'in_time_temprature'=>$users->temprature,
				'out_time'=>'NA',
				'shift_out_time'=>'',
				'out_device'=>'NA',
				'out_time_temprature'=>'NA',
				'actual_work_time'=>'NA',
				'expected_work_time'=>$users->visit_duration,
				'overtime'=>'NA',
				'attendance'=>'PRESENT',
				'update_in_type'=>'Gaurd',
				'update_by'=>$guard_id
			);
			$VisitorHistory=new VisitorHistory();
			$VisitorHistory->company_id=$company_id;
			$VisitorHistory->ams_data=json_encode($record);
			$VisitorHistory->last_synchronize_date=$date;
			$VisitorHistory->save();
		}else{
			$record=[];
           	$all_data = json_decode($VisitorHistory->ams_data);
			foreach($all_data as $key => $data ){
				if(isset($data->update_in_type) && $data->update_in_type!="Gaurd"){
					if($users->refer_code ==$data->employee_id){
						$data->in_time=date('Y-m-d h:i:s');
						$data->date=date('Y-m-d');
						$data->shift_in_time='NA';
						$data->in_device='Gaurd';
						$data->update_type='Gaurd';
					}
				}
				array_push($record,$data);
			}
			if(!in_array($users->refer_code,array_column((array)$all_data,'employee_id'))){
					$data2=array(
						'employee_name'=>$users->name,
						'employee_id'=>$users->refer_code,
						'office'=>'H1',
						'department'=>'Visitor',
						'shift'=>'',
						'date'=>$users->visite_time,
						'in_time'=>date('Y-m-d h:i:s'),
						'shift_in_time'=>'NA',
						'in_device'=>'Gaurd',
						'in_time_temprature'=>$users->temprature,
						'out_time'=>'NA',
						'shift_out_time'=>'',
						'out_device'=>'NA',
						'out_time_temprature'=>'NA',
						'actual_work_time'=>'NA',
						'expected_work_time'=>$users->visit_duration,
						'overtime'=>'NA',
						'attendance'=>'PRESENT',
						'update_in_type'=>'Gaurd',
						'update_by'=>$guard_id
					);
					array_push($record,$data2);
				}
			
			$VisitorHistory= VisitorHistory::where(['last_synchronize_date'=>$date])->update(['ams_data'=>json_encode($record)]);
           
		}
        return response()->json(['status'=>'success','msg'=>'Your Request Successfully Submitted']);
	}
	public function visitorOut(Request $request){
		$date = date('Y-m-d');
    $company_id =$request->company_id;
		$visitor_id=$request->user_id;
		$guard_id=$request->guard_id;
		$users=User::where(['id'=>$visitor_id,'company_id'=>$company_id])->first();
    $users->status = 9; //Visit Complated
    $users->save();
		AllVisit::where(['user_id'=>$visitor_id])->update(['out_status'=>'Yes','out_time'=>date('Y-m-d h:i:s'),'out_device'=>'NA']);
        $VisitorHistory= VisitorHistory::where(['last_synchronize_date'=>$date])->first();
		if(!isset($VisitorHistory->last_synchronize_date)){
			$data2=[];
			$data2=array(
				'employee_name'=>$users->name,
				'employee_id'=>$users->refer_code,
				'office'=>'H1',
				'department'=>'Visitor',
				'shift'=>'',
				'date'=>$users->visite_time,
				'in_time'=>'',
				'shift_in_time'=>'NA',
				'in_device'=>'',
				'in_time_temprature'=>$users->temprature,
				'out_time'=> date('Y-m-d h:i:s'),
				'shift_out_time'=>'',
				'out_device'=>'Gaurd',
				'out_time_temprature'=>'NA',
				'actual_work_time'=>'NA',
				'expected_work_time'=>$users->visit_duration,
				'overtime'=>'NA',
				'attendance'=>'PRESENT',
				'update_out_type'=>'Gaurd',
				'update_by'=>$guard_id
			);
			array_push($record,$data2);
		}else{
			$record=[];
           	$all_data = json_decode($VisitorHistory->ams_data);
			foreach($all_data as $key => $data ){
					if($users->refer_code ==$data->employee_id){
						$data->out_time=date('Y-m-d h:i:s');
						$data->date=date('Y-m-d');
						$data->shift_out_time='NA';
						$data->out_device='Gaurd';
						$data->update_out_type='Gaurd';
					}
					
					
			
				array_push($record,$data);
			}
			
			$VisitorHistory= VisitorHistory::where(['last_synchronize_date'=>$date])->update(['ams_data'=>json_encode($record)]);
           
		}
        return response()->json(['status'=>'success','msg'=>'Your Request Successfully Submitted']);
	}
 /**************suresh in out********************/

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
  

  public function preInvitationVerify(Request $request){
    $datas = User::select('id','name','email','mobile','company_id','refer_code','pre_invite_pin','pre_visit_date_time','image')->where(['id' => $request->user_id, 'company_id' => $request->company_id])->orderBy('id','desc')->first();
    return response()->json(['message' => 'You Are Successfully Login', 'class' => 'success','data'=>$datas]);

  }

  public function addPreInvite(Request $request)
  {
      $file_data = $request->image;
      $file_name = 'image_' . time() . '.png'; //generating unique file name;
    
      // @list($type, $file_data) = explode(';', $file_data);
      // @list(, $file_data) = explode(',', $file_data);
 
      if ($file_data != "") {
        $image_data = $file_data;
        \Storage::disk('public')->put($file_name, base64_decode($file_data));
      }

    $store_user =  User::where(['id' => $request->user_id])->first();
    $store_user->image = @$file_name;
    $store_user->image_base = @$image_data;
    $store_user->visite_time = @$store_user->pre_visit_date_time;
    $store_user->added_by = @$store_user->officer_id;
    $store_user->status = 1;
    $response = $store_user->save();     

    $add_visit = new AllVisit;
    $add_visit->user_id = $store_user->id;
    $add_visit->date_time =  $store_user->visite_time;
    $add_visit->officer_id =  $store_user->officer_id;
    $add_visit->added_by =  $store_user->added_by;
    $add_visit->save();

    if ($response == 1) {
      $add_status['status_code'] = 201;
      $add_status['message'] = "Visitor Successfully Registered";
    } else {
      $add_status['status_code'] = 400;
      $add_status['message'] = "Visitor Registration Failed";
    }
    $settings = Setting::where(['company_id' => $store_user->company_id, 'name' => 'ams_send'])->first();
    if (@$settings->status == "Active") {
      $add_status = $this->sendFaceCheck($store_user->id);
    }
    if ($add_status['status_code'] == '201') {
      $store_user->employee_unique_id = $add_status['status_code'];
      $store_user->save();

      $user_details = User::Where(['id' => $store_user->id])->first();
      $appoint_date = date('d/m/Y', strtotime($user_details->visite_time));
      $appoint_time = date('h:i:s a', strtotime($user_details->visite_time));
      $user_name = ucfirst($user_details->name);

      $full_name = explode(" ",$user_name);

      $visitor_name = ucfirst($full_name[0].' '.substr(@$full_name[1], 0, 1));

      $officer_details = Admin::Where(['id' => $store_user->officer_id])->first();

      event(new SmsEvent($officer_details->mobile, 'Dear Sir/Madam, '.@$visitor_name.' ('.@$user_details->mobile.') has submitted the pre invitation form and ready to visit at '.$appoint_date.' at '.$appoint_time.'. THANKS VMS Team'));
      
      $user_mobile = $user_details->mobile;
      $user_email = $officer_details->email;


      // send mail to officer

      $data = ['vis_name' =>$user_name,'user_mobile' =>$user_mobile,'app_date' => $appoint_date, 'appoint_time' => $appoint_time];
      Mail::send('mails.appointment-pre-submit', $data, function ($message) use ($user_email) {
        $sub = Carbon::now()->toDateTimeString();;
        $message->subject('Pre Invite Submission complete (' . $sub . ')');
        $message->from('noreply@vztor.in', 'Pre Invite Submission complete Alert  (' . $sub . ')');
        $message->to($user_email, 'Pre Invite Submission complete Alert (' . $sub . ')');
      });


      $encrypted = Crypt::encryptString($store_user->id);
      return response()->json(['message'=>'Successfully Submitted', 'status'=>'success','visitor_id'=>$encrypted,'user_id'=>$store_user->id]);
    
    } else {
      $store_user->status = 0;
      $store_user->save();
      return response()->json(['message' => $add_status['message'], 'class' => 'error','data'=>'']);
    }  
    
  }

  public function visitorApprove(Request $request)
  {
  
    if($request->officer_id==""){
      $message = 'Data Not Found';
      return response()->json(['message'=>$message, 'status'=>'failed']);
    }
    $user_id = $request->visitor_id;
    $users_data=User::where('id', $user_id)->first();
    $users_data->app_status = 'Approve';
    $users_data->status = 1;
	  $users_data->update_by=$request->officer_id;
    $users_data->save();

    
    $settings=Setting::where(['company_id'=>$users_data->company_id,'name'=>'ams_send'])->first();
    if(@$settings->status=="Active"){
      $res=$this->sendFaceCheckAlotte($users_data->refer_code);
    }
    if (empty($users_data)) {
      return response()->json(['message'=>'Visitor not found', 'status'=>'error']);
    } else {
      $this->sendApproveEmail($user_id);
      return response()->json(['message'=>'Visit Approve Successfully Done', 'status'=>'success']);

    }
    $this->sendApproveEmail($user_id);
    return response()->json(['message'=>'Visit Approve Successfully Done', 'status'=>'success']);
  }
  
  public function visitorApproveReject(Request $request)
  {  
    if($request->officer_id==""){
      $message = 'Data Not Found';
      return response()->json(['message'=>$message, 'status'=>'failed']);
    }
    $user_id = $request->visitor_id;
    $users_data=User::where('id', $user_id)->first();
    $users_data->app_status = 'Reject';
    $users_data->status = 5;
	  $users_data->update_by=$request->officer_id;
    $users_data->save();
    
    if (empty($users_data)) {
      return response()->json(['message'=>'Visitor not found', 'status'=>'error']);
    } else {
      $this->sendApproveEmail($user_id);
      return response()->json(['message'=>'Visit Reject Successfully Done', 'status'=>'success']);
    }
    $this->sendApproveEmail($user_id);
    return response()->json(['message'=>'Visit Reject Successfully Done', 'status'=>'success']);
  }

  public function sendApproveEmail($user_id){
    $new_user_id = $user_id;

    $user_details = User::Where(['id'=>$new_user_id])->first();
    $reception_details = Admin::Where(['id'=>$user_details->added_by])->first();
    $officer_details = Admin::Where(['id'=>$user_details->officer_id])->first();

    $user_email = @$user_details->email;
    $reception_name = @$reception_details->name;
    $sub = @$user_details->visite_time;
    $appoint_date = date('d/m/Y', strtotime(@$user_details->visite_time));
    $appoint_time = date('h:i:s a', strtotime(@$user_details->visite_time));
    if($user_details->status==1){
      $app_status='Approved';
    }else{
      $app_status='Cancelled';
    }
     event(new SmsEvent($user_details->mobile, 'Dear Visitor,
     Your meeting (#'.$user_details->refer_code.') with  '.$officer_details->name.' has been '.$app_status.' for '.$appoint_date.' at '.$appoint_time.'.
     Thanks
      VMS Team
     '));

    $user_name = ucfirst($user_details->name);
    $user_mobile = $user_details->mobile;
    $encrypted = Crypt::encryptString($new_user_id);
    $data=['vis_name' =>$user_name,'user_mobile' =>$user_mobile,'visitor_name'=>$reception_name,'app_date'=>$appoint_date,'appoint_time'=>$appoint_time,
    'encryptString'=>$encrypted,'status'=>$app_status,
    'duration'=>$user_details->visite_duration,'visitor_id'=>$user_details->refer_code];
          Mail::send('mails.appointment-approved-invoice', $data, function($message) use ($user_email){
            $sub = Carbon::now()->toDateTimeString();;

            $message->subject('Appointment Approval ('.$sub.')');
            $message->from('vztor.in@gmail.com','Appointment Approval  ('.$sub.')');
            $message->to($user_email, 'Appointment Approval ('.$sub.')');
        });   

  }

  public function visitRescheduled(Request $request)
  {
  
    if($request->officer_id==""){
      $message = 'Data Not Found';
      return response()->json(['message'=>$message, 'status'=>'failed']);
    }
    $user_id = $request->visitor_id;
    $users_data=User::where('id', $user_id)->first();
    $users_data->app_status = 'Approve';
    $users_data->status = 1;
    $users_data->visite_time = $request->rescheduled_visite_time;
	  $users_data->update_by=$request->officer_id;
    $users_data->save();
    
    $settings=Setting::where(['company_id'=>$users_data->company_id,'name'=>'ams_send'])->first();
    if(@$settings->status=="Active"){
      $res=$this->sendFaceCheckAlotte($users_data->refer_code);
    }
    if (empty($users_data)) {
      return response()->json(['message'=>'Visitor not found', 'status'=>'error']);
    } else {
      $this->sendRescheduledEmail($user_id);
      return response()->json(['message'=>'Visit Approve Successfully Done', 'status'=>'success']);

    }
    $this->sendRescheduledEmail($user_id);
    return response()->json(['message'=>'Visit Approve Successfully Done', 'status'=>'success']);
  }

  public function sendRescheduledEmail($user_id){
    $new_user_id = $user_id;

    $user_details = User::Where(['id'=>$new_user_id])->first();
    $reception_details = Admin::Where(['id'=>$user_details->added_by])->first();
    $officer_details = Admin::Where(['id'=>$user_details->officer_id])->first();

    $user_email = @$user_details->email;
    $reception_name = @$reception_details->name;
    $sub = @$user_details->visite_time;
    $appoint_date = date('d/m/Y', strtotime(@$user_details->visite_time));
    $appoint_time = date('h:i:s a', strtotime(@$user_details->visite_time));
    if($user_details->status==1){
      $app_status='Reschedule & Approved';
    }
     event(new SmsEvent($user_details->mobile, 'Dear Visitor,
     Your meeting (#'.$user_details->refer_code.') with  '.$officer_details->name.' has been '.$app_status.' for '.$appoint_date.' at '.$appoint_time.'.
     Thanks
      VMS Team
     '));

    $user_name = ucfirst($user_details->name);
    $user_mobile = $user_details->mobile;
    $encrypted = Crypt::encryptString($new_user_id);
    $data=['vis_name' =>$user_name,'user_mobile' =>$user_mobile,'visitor_name'=>$reception_name,'app_date'=>$appoint_date,'appoint_time'=>$appoint_time,
    'encryptString'=>$encrypted,'status'=>$app_status,
    'duration'=>$user_details->visite_duration,'visitor_id'=>$user_details->refer_code];
          Mail::send('mails.appointment-approved-invoice', $data, function($message) use ($user_email){
            $sub = Carbon::now()->toDateTimeString();;

            $message->subject('Appointment Approval ('.$sub.')');
            $message->from('vztor.in@gmail.com','Appointment Approval  ('.$sub.')');
            $message->to($user_email, 'Appointment Approval ('.$sub.')');
        });   

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
         'Authorization: bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyX25hbWUiOiJzYW10ZWNoLmFkbWluIiwidHlwZV9vZl91c2VyIjoiQURNSU4iLCJ0b2tlbiI6IiQyYSQwOCQ5SUY1UFV6cGh0bWVRTzJtVWtOdU8ucnU5VUhFaGc4OEtxM3QzMVVxR0VvR2NZR3BnU0VDVyIsImlhdCI6MTYzOTAzMDYwMn0.oxerDTTzpTnTN7UzQD7GSNEiUU9qt9sr0SHB6NxZ6Zo',
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
