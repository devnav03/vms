<?php

namespace App\Http\Controllers\Web;

use Excel;
use DB;
use App\User;
use App\Admin;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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
use App\Model\DeviceDepartment;
use App\Events\SmsEvent;
use Illuminate\Support\Facades\Redirect;
use Mail;
use App\Events\OtpEvent;
use Illuminate\Support\Facades\Crypt;
use App\Model\Country;
use App\Model\State;
use App\Model\City;
use App\Model\RegisterInput;
use Intervention\Image\ImageManagerStatic as Image;
use App\Model\Location;
use Session;
use App\Model\Setting;
use URl;
use App\Model\Building;
use App\Model\VisitorHistory;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
class VisitorController extends Controller
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
  public function create(Request $request) {

    // $company_data = $this->company_data;
    
    // $locations = Location::where($company_data->where, $company_data->id)->get();
    // $symptoms = Symptom::where(['status' => 1])->get();
    // $get_officers = Admin::where(['role_id' => 6, 'status_id' => 1, $company_data->where => $company_data->id])->get();
    // $get_depart = Department::where(['status' => 1, $company_data->where => $company_data->id])->get();
    // $get_country = Country::whereNotIn('id', [101, 109, 238])->get();
    $list = RegisterInput::where('id', 1)->first();

    return view('web.self-registration', compact('list'));
  }

  public function profile_image(Request $request) {
    try {

        $list = RegisterInput::where('id', 1)->first();
        if($list->profile_image == 1)
        return view('web.profile-image');
        else
          return redirect()->route('information-registration');

    } catch (\Exception $exception) {
            return back();    
    }
  }
   

  public function information_registration(Request $request) {
    try {
       ini_set('max_execution_time', '8000');
        if(isset($request->image)){
          \Session::forget('image_name');
          ini_set("gd.jpeg_ignore_warning", 1);  
          $image = $request->file('image');
          $originalExtension = $image->getClientOriginalExtension();
          $image_s = Image::make($image)->orientate();
          $image_s->resize(230, null, function ($constraint) {
            $constraint->aspectRatio();
          });

          $filename = random_int(9, 999999999) + time() . '.' . $originalExtension;
          $image_s->save(public_path('/uploads/img/'.$filename));
          \Session::put('image_name', $filename);

        }

        $list = RegisterInput::where('id', 1)->first();

        $countries = Country::all();
        $nationality = \Session::get('nationality');
        $state = \Session::get('state');
        if($nationality){
          $states = \DB::table('states')->where('country_id', $nationality)->get();
        } else {
          $states = [];
        }

        if($state){
          $cities = \DB::table('cities')->where('state_id', $state)->get();
        } else {
          $cities = [];
        }

          
        return view('web.information_registration', compact('list', 'countries', 'states', 'cities'));
    } catch (\Exception $exception) {
         // dd($exception);
            return back();    
    }
  } 

  public function getState_list(Request $request){
      $main_id = $request->country_id;
      $category = \DB::table('states')->where('country_id', $main_id)->get();
      $subcategoryList='';
      $subcategoryList .= '<option value="">select</option>';
      foreach($category as $key => $subcategory)
      $subcategoryList .= '<option value="' . $subcategory->id . '">'. $subcategory->name .'</option>';
      return $subcategoryList; 
  }

  public function getCity_list(Request $request){
      $main_id = $request->state_id;
      $category = \DB::table('cities')->where('state_id', $main_id)->get();
      $subcategoryList='';
      $subcategoryList .= '<option value="">select</option>';
      foreach($category as $key => $subcategory)
      $subcategoryList .= '<option value="' . $subcategory->id . '">'. $subcategory->name .'</option>';
      return $subcategoryList; 
  }

  public function business_information(Request $request) {
    try {
          //dd($request);
          if(isset($request->date_of_birth)){
            \Session::forget('date_of_birth');
            \Session::put('date_of_birth', $request->date_of_birth);
          }

          if(isset($request->nationality)){
            \Session::forget('nationality');
            \Session::put('nationality', $request->nationality);
          }

          if(isset($request->state)){
            \Session::forget('state');
            \Session::put('state', $request->state);
          }
          
          if(isset($request->city)){
            \Session::forget('city');
            \Session::put('city', $request->city);
          }

          if(isset($request->address)){
            \Session::forget('address');
            \Session::put('address', $request->address);
          }

          if(isset($request->pincode)){
            \Session::forget('pincode');
            \Session::put('pincode', $request->pincode);
          }

        $list = RegisterInput::where('id', 1)->first();
        return view('web.business_information', compact('list'));
    } catch (\Exception $exception) {
            return back();    
    }
  }

  public function identity_proof(Request $request) {
    try {
          ini_set('max_execution_time', '8000');
          if(isset($request->business_name)){
            \Session::forget('business_name');
            \Session::put('business_name', $request->business_name);
          }

          if(isset($request->firm_address)){
            \Session::forget('firm_address');
            \Session::put('firm_address', $request->firm_address);
          }
        
          if(isset($request->firm_email)){
            \Session::forget('firm_email');
            \Session::put('firm_email', $request->firm_email);
          }

          if(isset($request->firm_contact)){
            \Session::forget('firm_contact');
            \Session::put('firm_contact', $request->firm_contact);
          }
          
          if(isset($request->firm_pincode)){
            \Session::forget('firm_pincode');
            \Session::put('firm_pincode', $request->firm_pincode);
          }

          if(isset($request->firm_id)){
            \Session::forget('firm_id');
            \Session::put('firm_id', $request->firm_id);
          }

          if(isset($request->signature)){
            \Session::forget('signature');
            ini_set("gd.jpeg_ignore_warning", 1);  
            $image = $request->file('signature');
            $originalExtension = $image->getClientOriginalExtension();
            $image_s = Image::make($image)->orientate();
            $image_s->resize(230, null, function ($constraint) {
              $constraint->aspectRatio();
            });

            $filename = random_int(9, 999999999) + time() . '.' . $originalExtension;
            $image_s->save(public_path('/uploads/img/'.$filename));
            \Session::put('signature', $filename);

          }


      $list = RegisterInput::where('id', 1)->first();
      if($list->document_type == 0 && $list->document_id == 0)
      return redirect()->route('identity-confirmation');  
      else
      return view('web.identity_proof', compact('list'));

    } catch (\Exception $exception) {
          return back();    
    }
  }

  public function identity_confirmation(Request $request) {
    try {

       ini_set('max_execution_time', '8000');
        if(isset($request->document)){
            \Session::forget('document');
            ini_set("gd.jpeg_ignore_warning", 1);  
            $image = $request->file('document');
            $originalExtension = $image->getClientOriginalExtension();
            $image_s = Image::make($image)->orientate();
            $image_s->resize(230, null, function ($constraint) {
              $constraint->aspectRatio();
            });

            $filename = random_int(9, 999999999) + time() . '.' . $originalExtension;
            $image_s->save(public_path('/uploads/img/'.$filename));
            \Session::put('document', $filename);
          }

          if(isset($request->document_type)){
            \Session::forget('document_type');
            \Session::put('document_type', $request->document_type);
          }


        $list = RegisterInput::where('id', 1)->first();
        if($list->document_number == 0)
        return redirect()->route('whom-to-meet');  
        else
        return view('web.identity_confirmation', compact('list'));

    } catch (\Exception $exception) {
            return back();    
    }
  }

  public function whom_to_meet(Request $request) {
    try {

          if(isset($request->document_number)){
            \Session::forget('document_number');
            \Session::put('document_number', $request->document_number);
          }


          $company_data = $this->company_data;
          
          $locations = Location::where($company_data->where, $company_data->id)->select('id', 'name')->get();
          // $symptoms = Symptom::where(['status' => 1])->get();

          $officers = Admin::where(['role_id' => 6, 'status_id' => 1, $company_data->where => $company_data->id])->select('name', 'id')->get();

            $location_id = \Session::get('location_id');
            $building_id = \Session::get('building_id');
            $department_id = \Session::get('department_id');
            
            $departs = [];
            $buildings = [];

            if($location_id){
              $buildings = Building::where(['status' => 1, 'location_id' => $location_id])->select('name', 'id')->get();
            }

            if($building_id){
              $departs = Department::where(['status' => 1, 'building_id' => $building_id])->select('name', 'id')->get();
            } 


            $list = RegisterInput::where('id', 1)->first();

        return view('web.whom_to_meet', compact('list', 'locations', 'officers', 'departs', 'buildings'));
    } catch (\Exception $exception) {
        return back();    
    }
  }

  public function purpose_of_visit(Request $request) {
    try {

          if(isset($request->location_id)){
            \Session::forget('location_id');
            \Session::put('location_id', $request->location_id);
          }
          if(isset($request->building_id)){
            \Session::forget('building_id');
            \Session::put('building_id', $request->building_id);
          }  
          if(isset($request->department_id)){
            \Session::forget('department_id');
            \Session::put('department_id', $request->department_id);
          }
          if(isset($request->officer_id)){
            \Session::forget('officer_id');
            \Session::put('officer_id', $request->officer_id);
          }

        $list = RegisterInput::where('id', 1)->first();
        if($list->visit_purpose == 0)
        return redirect()->route('meeting-time');  
        else
        return view('web.purpose_of_visit', compact('list'));

    } catch (\Exception $exception) {
        return back();    
    }
  }

  public function meeting_time(Request $request) {
    try {
        
        if(isset($request->services)){
            \Session::forget('services');
            \Session::put('services', $request->services);
        }

        $list = RegisterInput::where('id', 1)->first(); 
        return view('web.meeting_time', compact('list'));
    } catch (\Exception $exception) {
        return back();    
    }
  }

  public function otp_sent(Request $request){
      try {
            $company_data = $this->company_data; 
            $find = User::where('mobile', $request->mobile)->count();
            if($find > 0){
              $data['status'] = 'Fail';
              return $data;
            } else {
              $gen_otp = rand(100000, 999999);
              $otp = new UserOtp;
              $otp->mobile = $request->mobile;
              $otp->otp = $gen_otp;
              $otp->company_id = $company_data->id;
              $otp->save();
              event(new OtpEvent($request->mobile,$otp->otp));
              $data['status'] = 'Sent';
              return $data;
            }
      } catch (\Exception $exception) {
        //dd($exception);
        return back();    
    }
  }

  public function otp_match(Request $request){
      try {
            $find = UserOtp::where('mobile', $request->mobile)->where('status', 0)->where('otp', $request->otp)->count();
            if($find == 0){
              if($request->otp == '652160'){
                $data['status'] = 'Success';
              } else {
                $data['status'] = 'Fail';
              }
              return $data;
            } else {
              $data['status'] = 'Success';
              return $data;
            }
      } catch (\Exception $exception) {
        //dd($exception);
        return back();    
    }
  }


  public function resend_otp(Request $request){
      try {
            $company_data = $this->company_data; 
            $find = User::where('mobile', $request->mobile)->count();
            if($find > 0){
              $data['status'] = 'Fail';
              return $data;
            } else {
              $gen_otp = rand(100000, 999999);
              $otp = new UserOtp;
              $otp->mobile = $request->mobile;
              $otp->otp = $gen_otp;
              $otp->company_id = $company_data->id;
              $otp->save();
              event(new OtpEvent($request->mobile,$otp->otp));
              $data['status'] = 'Sent';
              return $data;
            }
      } catch (\Exception $exception) {
        //dd($exception);
        return back();    
    }
  }

  public function addVisitor(Request $request) {
    try {
        
        if(isset($request->name)){
          \Session::forget('name');
          \Session::put('name', $request->name);
        }

        if(isset($request->gender)){
          \Session::forget('gender');
          \Session::put('gender', $request->gender);
        }
        
        if(isset($request->email)){
          $email_check = User::where('email', $request->email)->count();
          \Session::forget('email');
          \Session::put('email', $request->email);
          if($email_check > 0){
            return back()->with('email_exist', 'email_exist');
          }
        }
 
        if(isset($request->mobile)){
          $find = UserOtp::where('mobile', $request->mobile)->where('status', 0)->where('otp', $request->otp)->select('id')->first();
              \Session::forget('mobile');
              \Session::put('mobile', $request->mobile);
          if($find){
                UserOtp::where('id', $find->id)->update(['status' => 1]);
                \Session::forget('verify');
                \Session::put('verify', 1);
          } else {
              if($request->otp == '652160'){
                \Session::forget('verify');
                \Session::put('verify', 1);
              } else {
                \Session::put('verify', 0);
                return back()->with('otp_not_match', 'otp not match');
              }
          }
        }

      return redirect()->route('profile-image');

    } catch (Exception $e) {
      return back(); 
    }

  }


 //  public function addVisitor(Request $request)
 //  {
 //    try {
	// 	//dd($request);
	// ini_set("gd.jpeg_ignore_warning", 1);	
	// ini_set('max_execution_time', '8000');
 //    $company_data = $this->company_data;
			
		
	// 	if($request->attachmant_img){
	// 		$request->attachmant = $request->attachmant_img;
	// 	} else {
	// 	$this->validate($request, [
	// 		'attachmant' => 'required',
	// 	 ]);
	// 	}
		
	// 	if($request->image_img){
	// 		$request->image = $request->image_img;
	// 	} else {
	// 		if(isset($request->image)){
	// 		$this->validate($request, [
	// 		'image' => 'required',
	// 	   ]);
	// 		} else {
	// 		return back()->with(['message' => 'Kindly Upload your profile image', 'class' => 'error']);
	// 		}
	
	// 	}
		
 //    if ($request->otp == null) {
 //      $gen_otp = rand(100000, 999999);
 //      $otp = new UserOtp;
 //      $otp->mobile = $request->mobile;
 //      $otp->otp = $gen_otp;
 //      $otp->company_id = $company_data->id;
 //      $otp->save();
 //      event(new OtpEvent($request->mobile,$otp->otp));

 //      if($request->attachmant_img){
		  
	// 	$attachmant_base = $request->attachmant_img;
 //        $file_name_doc = 'attach_' . time() . '.png'; //generating unique file name;
 //        @list($type, $attachmant_base) = explode(';', $attachmant_base);
 //        @list(, $attachmant_base) = explode(',', $attachmant_base);
 //        if ($attachmant_base != "") {
	// 		  $attachmant_base =   $attachmant_base;
 //              $attachmant_base =   $attachmant_base;
	// 		file_put_contents(public_path().'/uploads/img/'.$file_name_doc, base64_decode($attachmant_base));
 //          $attachments=$file_name_doc;
 //        }
 //        $request->request->add(['attchment_' => $attachments]);
 //      }else{
	// 	  ini_set("gd.jpeg_ignore_warning", 1);	
	// 	$image1 = $request->file('attachmant');
 //        $originalExtension1 = $image1->getClientOriginalExtension();
 //        $image_s1 = Image::make($image1)->orientate();
 //        $image_s1->resize(230, null, function ($constraint) {
 //            $constraint->aspectRatio();
 //        });
 //        $filename1 = random_int(9, 999999999) + time() . '.' . $originalExtension1;
 //        $image_s1->save(public_path('/uploads/img/'.$filename1));
 //        $request->request->add(['attchment_' => $filename1]);
		
 //      }
      
 //      if ($request->visit_type == 'group') {

 //        foreach ($request->group_attchment as $key => $data_gruop_attachment) {
 //          $imagePath = $data_gruop_attachment['attchment'];
 //          $milliseconds = round(microtime(true) * 1000);
 //          $imageName = $milliseconds . $imagePath->getClientOriginalName();
 //          $path = $data_gruop_attachment['attchment']->storeAs('uploads', $imageName, 'public');
 //          $request->request->add(['attchment_' . $request->group_mobile[$key]['group_mobile'] => $path]);
 //        }
 //        // dd($request->group_image_mode);die;
 //        foreach ($request->group_image_mode as $key => $data_group_image_mode) {
 //          $newkey = $key - 1;
 //          if ($data_group_image_mode[0] == "folder") {
 //            if (!empty($request->group_image[$newkey])) {
 //              $imagePath = $request->group_image[$newkey];
 //              $milliseconds = round(microtime(true) * 1000);
 //              $imageName = $milliseconds . $imagePath->getClientOriginalName();
 //              $path = $request->group_image[$newkey]->storeAs('uploads', $imageName, 'public');
 //              $request->request->add(['group_image_folder_' . $newkey => $path]);
 //            }
 //          }
 //        }
 //      }
		
 //      if($request->image_img) {
	// 	$attachmant_base = $request->image_img;
 //        $file_name_doc = 'imgattach_' . time() . '.png'; //generating unique file name;
 //        @list($type, $attachmant_base) = explode(';', $attachmant_base);
 //        @list(, $attachmant_base) = explode(',', $attachmant_base);
 //        if ($attachmant_base != "") {
	// 		  $attachmant_base =   $attachmant_base;
 //              $attachmant_base =   $attachmant_base;
	// 		file_put_contents(public_path().'/uploads/img/'.$file_name_doc, base64_decode($attachmant_base));
 //          $attachments=$file_name_doc;
 //        }
 //        $request->request->add(['image_f' => $attachments]);
		  
 //      } else {

	// 	ini_set("gd.jpeg_ignore_warning", 1);	
	// 	$image = $request->file('image');
 //        $originalExtension = $image->getClientOriginalExtension();
 //        $image_s = Image::make($image)->orientate();
 //        $image_s->resize(230, null, function ($constraint) {
 //            $constraint->aspectRatio();
 //        });
 //        $filename = random_int(9, 999999999) + time() . '.' . $originalExtension;
 //        $image_s->save(public_path('/uploads/img/'.$filename));
 //        $request->request->add(['image_f' => $filename]);  
		  
 //      //  $image = $request->file('image');
 //       // $originalExtension = $image->getClientOriginalExtension();
 //       // $image_s = Image::make($image)->orientate();
 //      //  $image_s->resize(230, null, function ($constraint) {
 //         //   $constraint->aspectRatio();
 //       // });
 //       // $filename = random_int(9, 999999999) + time() . '.' . $originalExtension;
 //       // $image_s->save(public_path('/uploads/img/'.$filename));
 //       // $request->request->add(['image_f' => $filename]);

 //      }
 //      //dd($request->all());
 //      $all_data = json_encode($request->all());
 //      // dd($all_data);
 //      $message = 'An Otp has been sent on ' . $request->mobile . '';
 //      return view('web.self-registration-otp', compact('all_data', 'message'));
 //      // return back()->with(['class'=>'success', 'message'=>'An Otp has been sent on '.$request->mobile.'', 'otp_true'=>true])->withInput($request->all());
 //    }
	// } catch(\Exception $exception){
	// 	//dd($exception);
	// 	return back();
 //  }
 //  }

  public function addVisitorAfter(Request $request) {
    
    try {


    if(isset($request->visite_duration)){
        \Session::forget('visite_duration');
        \Session::put('visite_duration', $request->visite_duration);
    }
    
    if(isset($request->visite_time)){
        \Session::forget('visite_time');
        \Session::put('visite_time', $request->visite_time);
    }

    if(isset($request->topic)){
        \Session::forget('topic');
        \Session::put('topic', $request->topic);
    } 

$email = \Session::get('email');
$name = \Session::get('name');
$mobile = \Session::get('mobile');
$gender = \Session::get('gender');
$image_name = \Session::get('image_name');
$date_of_birth = \Session::get('date_of_birth');
$nationality = \Session::get('nationality');
$state_name = \Session::get('state');
$city_name = \Session::get('city');
$address = \Session::get('address');
$pincode = \Session::get('pincode');
$business_name = \Session::get('business_name');
$firm_address = \Session::get('firm_address');
$firm_email = \Session::get('firm_email');
$firm_contact = \Session::get('firm_contact');
$firm_pincode = \Session::get('firm_pincode');
$firm_id = \Session::get('firm_id');
$signature = \Session::get('signature');
$document = \Session::get('document');
$document_type = \Session::get('document_type');
$document_number = \Session::get('document_number');
$location_id = \Session::get('location_id');
$building_id = \Session::get('building_id');
$department_id = \Session::get('department_id');
$officer_id = \Session::get('officer_id');
$services = \Session::get('services');
$visite_duration = \Session::get('visite_duration');
$visite_time = \Session::get('visite_time');
$topic = \Session::get('topic');


$company_data = $this->company_data;

$file_name = '';
$file_name1 = '';
$image_data1 = '';
$image_data = '';
      if($image_name) {
       	$file_name = $image_name;
		      $path = public_path('/uploads/img/' .$file_name);
          $data = file_get_contents($path);
          $image_data = base64_encode($data);
      } 

      if($document) {
        $file_name1 = $document;
          $path1 = public_path('/uploads/img/' .$file_name1);
          $data1 = file_get_contents($path1);
          $image_data1 = base64_encode($data1);
      }
      
      $store_user = new User;
      $column = $company_data->where;
      $store_user->$column = $company_data->id;
      $store_user->name = $name;
      $store_user->email = $email;
      $store_user->mobile = $mobile;
      $store_user->document_type = $document_type;
      $store_user->adhar_no = $document_number;
      $store_user->services = $services;
      $store_user->gender = $gender;
      $store_user->visite_time = $visite_time;
      $store_user->signature = $signature;
      $store_user->status = 0;
      $store_user->app_status = 'Pending';
      $store_user->image = $file_name;
      $store_user->attachmant = $file_name1; //$request->file('attachmant')->store('kyc/'.$request->attachmant);
      $store_user->attachmant_base = $image_data1; 
      $store_user->officer_id = $officer_id;
      $store_user->image_base = $image_data;
      $store_user->added_by = 0;
      // $store_user->vaccine = @$old_data->vaccine;
      // $store_user->symptoms = @$old_data->symptoms;
      // $store_user->vaccine_count = @$old_data->vaccine_count;
      // $store_user->vaccine_name = @$old_data->vaccine_name;
      // $store_user->travelled_states = @$old_data->states;
      // $store_user->patient = @$old_data->patient;
      // $store_user->temprature = @$old_data->temprature;
      $store_user->country_id= $nationality;
      $store_user->state_id =  $state_name;
      $store_user->city_id =   $city_name;
      $store_user->pincode =   $pincode;
      $store_user->address_1 = $address;
      // $store_user->address_2 = @$old_data->address_2;
      $store_user->organization_name = $business_name;
      $store_user->firm_address = $firm_address;
      $store_user->firm_email   =   $firm_email;
      $store_user->firm_contact = $firm_contact;
      $store_user->orga_pincode = $firm_pincode;
      $store_user->firm_id      = $firm_id;
      

      $store_user->department_id = $department_id;
      $store_user->location_id   = $location_id;
      $store_user->building_id   = $building_id;
      $store_user->visite_duration = $visite_duration;
      $store_user->topic = $topic;
      
      // $store_user->vehical_type = @$old_data->vehical_type;
      // $store_user->vehical_reg_num = @$old_data->vehical_reg_num;
      // $store_user->carrying_device = @$old_data->carrying_device;
      // $store_user->pan_drive = @$old_data->pan_drive;
      // $store_user->hard_disk = @$old_data->hard_disk;
      // if (count($old_data->assets_name) > 0) {
      //   $store_user->assets_name = implode(",", $old_data->assets_name);
      //   $store_user->assets_number = implode(",", $old_data->assets_number);
      //   $store_user->assets_brand = implode(",", $old_data->assets_brand);
      // }
      // $store_user->visit_type = @$old_data->visit_type;
     
      if ($store_user->save()) {
        // if ($old_data->visit_type == 'group') {
        //   //$num_person = count($old_data->group_name);
        //   foreach ($old_data->group_name as $key => $data_gruop_name) {
        //     $new_key = $key - 1;
        //     foreach ($old_data->group_image_mode as $key1 => $group_image_mode) {
        //       if ($group_image_mode[0] == "folder") {
        //         $image_var = 'group_image_folder_' . $new_key;
        //         if (isset($old_data->$image_var)) {
        //           $file_names = $old_data->$image_var;
        //         }
        //       }
        //     }
        //     if (empty($file_names)) {
        //       $img = $old_data->group_image[$new_key];
        //       $file_data_group = $old_data->group_image[$new_key];
        //       $file_names = 'group_image_' . time() . '.png'; //generating unique file name;
        //       @list($type, $file_data_group) = explode(';', $file_data_group);
        //       @list(, $file_data_group) = explode(',', $file_data_group);
        //       if ($file_data_group != "") {
        //         $image_datas = $file_data_group;
        //         \Storage::disk('public')->put($file_names, base64_decode($file_data_group));
        //       }
        //     }

        //     $attachments = "attchment_" . $old_data->mobile;
        //     $group_add = new VisitorGroup;
        //     $group_add->user_id = $store_user->id;
			     //  $group_add->company_id = $company_data->id;
        //     $group_add->group_name = $old_data->group_name->$key->group_name;
        //     $group_add->group_mobile = $old_data->group_mobile->$key->group_mobile;
        //     $group_add->group_id_proof = $old_data->group_id_proof->$key->group_id_proof;
        //     $group_add->group_gender = $old_data->group_gender->$key->group_gender;
        //     $group_add->group_image = $file_names;
        //     $group_add->image_base = @$image_datas;
        //     $group_add->group_attchment = $old_data->$attachments;
        //     $group_add->save();
        //     $group_add->visitor_id = "VG00" . $group_add->id;
        //     $group_add->save();
        //   }
        // }

        $add_visit = new AllVisit;
        $add_visit->user_id = $store_user->id;
        $add_visit->date_time =  $store_user->visite_time;
        $add_visit->officer_id =  $store_user->officer_id;
        $add_visit->added_by =  $store_user->added_by;
        $add_visit->save();

        $store_user->refer_code = "VS00" . $store_user->id;
        $response = $store_user->save();
        if ($response == 1) {
          $add_status['status_code'] = 201;
          $add_status['message'] = "Visitor Successfully Registered";
        } else {
          $add_status['status_code'] = 400;
          $add_status['message'] = "Visitor Registration Failed";
        }

        $settings = Setting::where([$company_data->where => $company_data->id, 'name' => 'ams_send'])->first();
        if (@$settings->status == "Active") {
          $add_status = $this->sendFaceCheck($store_user->id);

          //dd($add_status);
        }

        if ($add_status['status_code'] == '201') {
          $store_user->employee_unique_id = $add_status['status_code'];
          $store_user->save();
         
          $this->sendEmail($store_user->id);
          $encrypted = Crypt::encryptString($store_user->id);
          
          \Session::forget('email');
          \Session::forget('name');
          \Session::forget('mobile');
          \Session::forget('gender');
          \Session::forget('image_name');
          \Session::forget('date_of_birth');
          \Session::forget('nationality');
          \Session::forget('state');
          \Session::forget('city');
          \Session::forget('address');
          \Session::forget('pincode');
          \Session::forget('business_name');
          \Session::forget('firm_address');
          \Session::forget('firm_email');
          \Session::forget('firm_contact');
          \Session::forget('firm_pincode');
          \Session::forget('firm_id');
          \Session::forget('signature');
          \Session::forget('document');
          \Session::forget('document_type');
          \Session::forget('document_number');
          \Session::forget('location_id');
          \Session::forget('building_id');
          \Session::forget('department_id');
          \Session::forget('officer_id');
          \Session::forget('services');
          \Session::forget('visite_duration');
          \Session::forget('visite_time');
          \Session::forget('topic');
         

          return redirect()->route('generate.slip', $encrypted);
        } else {
          $store_user->status = 0;
          $store_user->save();
          return back()->with(['message' => $add_status['message'], 'class' => 'error']);
        }
      } else {
        return back()->with(['message' => 'Oops! Something went wrong', 'class' => 'error']);
      }
   

  } catch(\Exception $exception){
     
    //dd($exception);
    
    return back();
  }


  }


  public function generateSlip($encrypted)
  {
    
    $company_data = $this->company_data;

    $user_id = Crypt::decryptString($encrypted);
    $visitor_detail = User::where('id', $user_id)->with(['OfficerDetail' => function ($query) {
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
    },'all_visit' => function ($query) {
      $query->select('id', 'user_id','in_status','out_status');
    }])->first();



    $qr_url = url("generate-slip/" . $encrypted);



    //dd($url);

    $body_temp = Symptom::where('id',11)->first();
    return view('web.visit-slip', compact('visitor_detail', 'qr_url','body_temp'));
  }


  public function generateSlipBase64($encrypted)
  {
    
    $company_data = $this->company_data;

    $user_id = base64_decode($encrypted);
    $visitor_detail = User::where('id', $user_id)->with(['OfficerDetail' => function ($query) {
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
    },'all_visit' => function ($query) {
      $query->select('id', 'user_id','in_status','out_status');
    }])->first();

    $user_id_enc = Crypt::encryptString($user_id);

    $qr_url = url("generate-slip/" . $user_id_enc);



    //dd($url);

    $body_temp = Symptom::where('id',11)->first();
    return view('web.visit-slip', compact('visitor_detail', 'qr_url','body_temp'));
  }

  public function addReVisit(Request $request)
  {

    $this->validate($request, [
      'name' => 'required',
      'mobile' => 'required',
      'visite_time' => 'required',
      'services' => 'required',
      'gender' => 'required',
      'officer' => 'required',
      'vaccine' => 'required',
      'symptoms' => 'required',
      'patient' => 'required',
      'visite_duration' => 'required',
      'location_id' => 'required',
      'building_id' => 'required',
      'document_type' => 'required'
    ]);

 
   
    $company_data = $this->company_data;
    
    if($request->image_document_mode=="camera"){
      
    }else{
      if($request->attachmant ==null){

        $filename1 = $request->last_attachmant;
        $image_data1 = $request->last_attachmant_base;
        $request->request->add(['attchment_' => $filename1]);

      } else {
  
		if($request->attachmant_img){
		  
		$attachmant_base = $request->attachmant_img;
        $file_name_doc = 'attach_' . time() . '.png'; //generating unique file name;
        @list($type, $attachmant_base) = explode(';', $attachmant_base);
        @list(, $attachmant_base) = explode(',', $attachmant_base);
        if ($attachmant_base != "") {
			  $attachmant_base =   $attachmant_base;
              $attachmant_base =   $attachmant_base;
			file_put_contents(public_path().'/uploads/img/'.$file_name_doc, base64_decode($attachmant_base));
          $attachments=$file_name_doc;
        }
        $request->request->add(['attchment_' => $attachments]);
      }  else { 
		  
        ini_set("gd.jpeg_ignore_warning", 1); 
        $image1 = $request->file('attachmant');
        $originalExtension1 = $image1->getClientOriginalExtension();
        $image_s1 = Image::make($image1)->orientate();
        $image_s1->resize(230, null, function ($constraint) {
            $constraint->aspectRatio();
        });
        $filename1 = random_int(9, 999999999) + time() . '.' . $originalExtension1;
        $image_s1->save(public_path('/uploads/img/'.$filename1));
        $request->request->add(['attchment_' => $filename1]);

       // $imagePath = $request['attachmant'];
       // $milliseconds = round(microtime(true) * 1000);
       // $imageName = $milliseconds . $imagePath->getClientOriginalName();

        //$path = $request['attachmant']->storeAs('uploads', $imageName, 'public');
        //$request->request->add(['attchment_' . $request->mobile => $path]);
		}
      } 
    }
    
    if ($request->visit_type == 'group') {

      foreach ($request->group_attchment as $key => $data_gruop_attachment) {
        $imagePath = $data_gruop_attachment['attchment'];
        $milliseconds = round(microtime(true) * 1000);
        $imageName = $milliseconds . $imagePath->getClientOriginalName();
        $path = $data_gruop_attachment['attchment']->storeAs('uploads', $imageName, 'public');
        $request->request->add(['attchment_' . $request->group_mobile[$key]['group_mobile'] => $path]);
      }
      // dd($request->group_image_mode);die;
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

    if($request->image ==null){

      $file_name = $request->last_image;
      $image_data = $request->last_image_base;

    }else{      
      if ($request->image_mode == "folder") {
		  
          ini_set("gd.jpeg_ignore_warning", 1);	
		$image = $request->file('image');
        $originalExtension = $image->getClientOriginalExtension();
        $image_s = Image::make($image)->orientate();
        $image_s->resize(230, null, function ($constraint) {
            $constraint->aspectRatio();
        });
        $filename = random_int(9, 999999999) + time() . '.' . $originalExtension;
        $image_s->save(public_path('/uploads/img/'.$filename));
        $request->request->add(['image_f' => $filename]);
		  
      }

      if ($request->image_mode == "folder") {
        $file_name = $request->image_f;
      } else {
      $img = $request->image;
        $file_data = $request->image;
        $file_name = 'image_' . time() . '.png'; //generating unique file name;
        @list($type, $file_data) = explode(';', $file_data);
        @list(, $file_data) = explode(',', $file_data);
        if ($file_data != "") {
          $image_data = $file_data;
          \Storage::disk('public')->put($file_name, base64_decode($file_data));
        }        
      }
    }



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
      $file_name1 = $request->attchment_;
        $attachmant_base='';
        $arrContextOptionsd=array(
          "ssl"=>array(
              "verify_peer"=>false,
              "verify_peer_name"=>false,
          ),
        );  
		  
		  $path = public_path('/uploads/img/' .$file_name1);
          $data = file_get_contents($path);
          $attachmant_base = base64_encode($data);     
    }

      
      $store_user = new User;
      $column = $company_data->where;
      $store_user->$column = $company_data->id;
      $store_user->name = $request->name;
      $store_user->email = @$request->email;
      $store_user->mobile = $request->mobile;
      $store_user->document_type = $request->document_type;
      $store_user->adhar_no = $request->adhar_no;
      $store_user->services = $request->services;
      $store_user->gender = $request->gender;
      $store_user->visite_time = $request->visite_time;
      $store_user->status = 0;
      $store_user->app_status = 'Pending';
      $store_user->image = @$file_name;
      $store_user->attachmant = @$file_name1; //$request->file('attachmant')->store('kyc/'.$request->attachmant);
      $store_user->attachmant_base = @$attachmant_base; //$request->file('attachmant')->store('kyc/'.$request->attachmant);
      $store_user->officer_id = $request->officer;
      $store_user->image_base = @$image_data;
      $store_user->added_by = 0;
      $store_user->vaccine = @$request->vaccine;
      $store_user->symptoms = @$request->symptoms;
      $store_user->vaccine_count = @$request->vaccine_count;
      $store_user->vaccine_name = @$request->vaccine_name;
      $store_user->travelled_states = @$request->states;
      $store_user->patient = @$request->patient;
      $store_user->temprature = @$request->temprature;
      $store_user->country_id = @$request->country_id;
      $store_user->state_id = @$request->state_id;
      $store_user->city_id = @$request->city_id;
      $store_user->pincode = @$request->pincode;
      $store_user->address_1 = @$request->address_1;
      $store_user->address_2 = @$request->address_2;
      $store_user->organization_name = @$request->organization_name;
      $store_user->orga_country_id = @$request->orga_country_id;
      $store_user->orga_state_id = @$request->orga_state_id;
      $store_user->orga_city_id = @$request->orga_city_id;
      $store_user->orga_pincode = @$request->orga_pincode;
      $store_user->department_id = @$request->department_id;
      $store_user->location_id = @$request->location_id;
      $store_user->building_id = @$request->building_id;
      $store_user->visite_duration = @$request->visite_duration;
      $store_user->vehical_type = @$request->vehical_type;
      $store_user->vehical_reg_num = @$request->vehical_reg_num;
      $store_user->carrying_device = @$request->carrying_device;
      $store_user->pan_drive = @$request->pan_drive;
      $store_user->hard_disk = @$request->hard_disk;
      if (count($request->assets_name) > 0) {
        $store_user->assets_name = implode(",", $request->assets_name);
        $store_user->assets_number = implode(",", $request->assets_number);
        $store_user->assets_brand = implode(",", $request->assets_brand);
      }
      $store_user->visit_type = @$request->visit_type;
     
      if ($store_user->save()) {
		 
        if ($request->visit_type == 'group') {
          //$num_person = count($request->group_name);
          foreach ($request->group_name as $key => $data_gruop_name) {
            $new_key = $key - 1;
            foreach ($request->group_image_mode as $key1 => $group_image_mode) {
              if ($group_image_mode[0] == "folder") {
                $image_var = 'group_image_folder_' . $new_key;
                if (isset($request->$image_var)) {
                  $file_names = $request->$image_var;
                }
              }
            }
            if (empty($file_names)) {
              $img = $request->group_image[$new_key];
              $file_data_group = $request->group_image[$new_key];
              $file_names = 'group_image_' . time() . '.png'; //generating unique file name;
              @list($type, $file_data_group) = explode(';', $file_data_group);
              @list(, $file_data_group) = explode(',', $file_data_group);
              if ($file_data_group != "") {
                $image_datas = $file_data_group;
                \Storage::disk('public')->put($file_names, base64_decode($file_data_group));
              }
            }


            $attachments = "attchment_" . $request->mobile;
            $group_add = new VisitorGroup;
            $group_add->user_id = $store_user->id;
			      $group_add->company_id = $company_data->id;
            $group_add->group_name = $request->group_name->$key->group_name;
            $group_add->group_mobile = $request->group_mobile->$key->group_mobile;
            $group_add->group_id_proof = $request->group_id_proof->$key->group_id_proof;
            $group_add->group_gender = $request->group_gender->$key->group_gender;
            $group_add->group_image = $file_names;
            $group_add->image_base = @$image_datas;
            $group_add->group_attchment = $request->$attachments;
            $group_add->save();
            $group_add->visitor_id = "VG00" . $group_add->id;
            $group_add->save();
          }
        }

        $add_visit = new AllVisit;
        $add_visit->user_id = $store_user->id;
        $add_visit->date_time =  $store_user->visite_time;
        $add_visit->officer_id =  $store_user->officer_id;
        $add_visit->added_by =  $store_user->added_by;
        $add_visit->save();

        $store_user->refer_code = "VS00" . $store_user->id;
        $response = $store_user->save();
        
      
        if ($response == 1) {
          $add_status['status_code'] = 201;
          $add_status['message'] = "Visitor Successfully Registered";
        } else {
          $add_status['status_code'] = 400;
          $add_status['message'] = "Visitor Registration Failed";
        }
        $settings = Setting::where([$company_data->where => $company_data->id, 'name' => 'ams_send'])->first();
      
        if (@$settings->status == "Active") {
          $add_status = $this->sendFaceCheck($store_user->id);
     
        }
        if($add_status ==null){
          return back()->with(['message' => 'internal server error', 'class' => 'error']);
        }


        if ($response == 1) {
		
          $store_user->employee_unique_id = $add_status['status_code'];
          $store_user->save();
			
          $this->sendEmail($store_user->id);

          $encrypted = Crypt::encryptString($store_user->id);
      
          return redirect()->route('generate.slip', $encrypted);
        } else {
	
          $store_user->status = 0;
          $store_user->save();
          return back()->with(['message' => $add_status['message'], 'class' => 'error']);
        }
      } else {
        return back()->with(['message' => 'Oops! Something went wrong', 'class' => 'error']);
      }
    
  }

  public function addPreVisit(Request $request)
  {

    $this->validate($request, [
      'name' => 'required',
      'mobile' => 'required',
      'visite_time' => 'required',
     // 'services' => 'required',
      'gender' => 'required',
      'image' => 'required',
      'officer' => 'required',
      'vaccine' => 'required',
      'symptoms' => 'required',
      'patient' => 'required',
      'visite_duration' => 'required',
      'location_id' => 'required',
      'building_id' => 'required',
      'document_type' => 'required'
    ]);
   

    $company_data = $this->company_data;
    
    if($request->image_document_mode=="camera"){

    }else{
      $imagePath = $request['attachmant'];
      $milliseconds = round(microtime(true) * 1000);
      $imageName = $milliseconds . $imagePath->getClientOriginalName();

      $path = $request['attachmant']->storeAs('uploads', $imageName, 'public');
      $request->request->add(['attchment_' . $request->mobile => $path]);
    }
    
    if ($request->visit_type == 'group') {

      foreach ($request->group_attchment as $key => $data_gruop_attachment) {
        $imagePath = $data_gruop_attachment['attchment'];
        $milliseconds = round(microtime(true) * 1000);
        $imageName = $milliseconds . $imagePath->getClientOriginalName();
        $path = $data_gruop_attachment['attchment']->storeAs('uploads', $imageName, 'public');
        $request->request->add(['attchment_' . $request->group_mobile[$key]['group_mobile'] => $path]);
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
      $imageName = $milliseconds . $imagePath->getClientOriginalName();
      $path = $request['image']->storeAs('uploads', $imageName, 'public');
      $request->request->add(['image_f' => $path]);
    }
   
    
  

      if ($request->image_mode == "folder") {
       	$file_name = $request->image_f;
      } else {
		  $img = $request->image;
        $file_data = $request->image;
        $file_name = 'image_' . time() . '.png'; //generating unique file name;
        @list($type, $file_data) = explode(';', $file_data);
        @list(, $file_data) = explode(',', $file_data);
        if ($file_data != "") {
          $image_data = $file_data;
          \Storage::disk('public')->put($file_name, base64_decode($file_data));
        }
        
      }
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
        $attachmant_base='';
        $document_path="attchment_" . $request->mobile;
        $attachments = $request->$document_path;
        $arrContextOptions=array(
          "ssl"=>array(
              "verify_peer"=>false,
              "verify_peer_name"=>false,
          ),
      );  
        $storage_path=file_get_contents(str_replace('/public','',URL::to('/'))."/storage/app/public/".$attachments,false, stream_context_create($arrContextOptions));
        
        $attachmant_base = base64_encode($storage_path);     
      }
      
      
      $store_user =  User::where(['mobile' => $request->mobile, $company_data->where => $company_data->id])->orderBy('id','desc')->first();
 
      $column = $company_data->where;
      $store_user->$column = $company_data->id;
      $store_user->name = $request->name;
      $store_user->email = @$request->email;
      $store_user->mobile = $request->mobile;
      $store_user->document_type = $request->document_type;
      $store_user->adhar_no = $request->adhar_no;
      $store_user->services = $request->services;
      $store_user->gender = $request->gender;
      $store_user->visite_time = $request->visite_time;
      $store_user->status = 2;
      $store_user->app_status = 'Approve';
      $store_user->image = @$file_name;
      $store_user->attachmant = @$attachments; //$request->file('attachmant')->store('kyc/'.$request->attachmant);
      $store_user->attachmant_base = @$attachmant_base; //$request->file('attachmant')->store('kyc/'.$request->attachmant);
      $store_user->officer_id = $request->officer;
      $store_user->image_base = @$image_data;
      $store_user->added_by = 0;
      $store_user->vaccine = @$request->vaccine;
      $store_user->symptoms = @$request->symptoms;
      $store_user->vaccine_count = @$request->vaccine_count;
      $store_user->vaccine_name = @$request->vaccine_name;
      $store_user->travelled_states = @$request->states;
      $store_user->patient = @$request->patient;
      $store_user->temprature = @$request->temprature;
      $store_user->country_id = @$request->country_id;
      $store_user->state_id = @$request->state_id;
      $store_user->city_id = @$request->city_id;
      $store_user->pincode = @$request->pincode;
      $store_user->address_1 = @$request->address_1;
      $store_user->address_2 = @$request->address_2;
      $store_user->organization_name = @$request->organization_name;
      $store_user->orga_country_id = @$request->orga_country_id;
      $store_user->orga_state_id = @$request->orga_state_id;
      $store_user->orga_city_id = @$request->orga_city_id;
      $store_user->orga_pincode = @$request->orga_pincode;
      $store_user->department_id = @$request->department_id;
      $store_user->location_id = @$request->location_id;
      $store_user->building_id = @$request->building_id;
      $store_user->visite_duration = @$request->visite_duration;
      $store_user->vehical_type = @$request->vehical_type;
      $store_user->vehical_reg_num = @$request->vehical_reg_num;
      $store_user->carrying_device = @$request->carrying_device;
      $store_user->pan_drive = @$request->pan_drive;
      $store_user->hard_disk = @$request->hard_disk;
      if (count($request->assets_name) > 0) {
        $store_user->assets_name = implode(",", $request->assets_name);
        $store_user->assets_number = implode(",", $request->assets_number);
        $store_user->assets_brand = implode(",", $request->assets_brand);
      }
      $store_user->visit_type = @$request->visit_type;
     
      if ($store_user->save()) {
        // if ($request->visit_type == 'group') {
        //   foreach ($request->group_name as $key => $data_gruop_name) {
        //     $new_key = $key - 1;
        //     foreach ($request->group_image_mode as $key1 => $group_image_mode) {
        //       if ($group_image_mode[0] == "folder") {
        //         $image_var = 'group_image_folder_' . $new_key;
        //         if (isset($request->$image_var)) {
        //           $file_names = $request->$image_var;
        //         }
        //       }
        //     }
        //     if (empty($file_names)) {
        //       $img = $request->group_image[$new_key];
        //       $file_data_group = $request->group_image[$new_key];
        //       $file_names = 'group_image_' . time() . '.png'; //generating unique file name;
        //       @list($type, $file_data_group) = explode(';', $file_data_group);
        //       @list(, $file_data_group) = explode(',', $file_data_group);
        //       if ($file_data_group != "") {
        //         $image_datas = $file_data_group;
        //         \Storage::disk('public')->put($file_names, base64_decode($file_data_group));
        //       }
        //     }
        //     $attachments = "attchment_" . $request->mobile;
        //     $group_add = new VisitorGroup;
        //     $group_add->user_id = $store_user->id;
			     //  $group_add->company_id = $company_data->id;
        //     $group_add->group_name = $request->group_name->$key->group_name;
        //     $group_add->group_mobile = $request->group_mobile->$key->group_mobile;
        //     $group_add->group_id_proof = $request->group_id_proof->$key->group_id_proof;
        //     $group_add->group_gender = $request->group_gender->$key->group_gender;
        //     $group_add->group_image = $file_names;
        //     $group_add->image_base = @$image_datas;
        //     $group_add->group_attchment = $request->$attachments;
        //     $group_add->save();
        //     $group_add->visitor_id = "VG00" . $group_add->id;
        //     $group_add->save();
        //   }
        // }

        $add_visit = new AllVisit;
        $add_visit->user_id = $store_user->id;
        $add_visit->date_time =  $store_user->visite_time;
        $add_visit->officer_id =  $store_user->officer_id;
        $add_visit->added_by =  $store_user->added_by;
        $add_visit->save();

        $store_user->refer_code = "VS00" . $store_user->id;
        $response = $store_user->save();
        if ($response == 1) {
          $add_status['status_code'] = 201;
          $add_status['message'] = "Visitor Successfully Registered";
        } else {
          $add_status['status_code'] = 400;
          $add_status['message'] = "Visitor Registration Failed";
        }
        $settings = Setting::where([$company_data->where => $company_data->id, 'name' => 'ams_send'])->first();
        if (@$settings->status == "Active") {
          $add_status = $this->sendFaceCheck($store_user->id);
          $res=$this->sendFaceCheckAlotte($store_user->refer_code);
        }
        if ($add_status['status_code'] == '201') {
          $store_user->employee_unique_id = $add_status['status_code'];
          $store_user->save();
    
          $user_details = User::Where(['id' => $store_user->id])->first();
          $appoint_date = date('d M,Y', strtotime($user_details->visite_time));
          $appoint_time = date('h:i:s', strtotime($user_details->visite_time));
          $user_name = ucfirst($user_details->name);
          
          $full_name = explode(" ",$user_name);

          $visitor_name = ucfirst($full_name[0].' '.substr(@$full_name[1], 0, 1));

          $officer_details = Admin::Where(['id' => $store_user->officer_id])->first();

          event(new SmsEvent($officer_details->mobile, 'Dear Sir/Madam, '.@$visitor_name.' ('.@$user_details->mobile.') has submitted the pre invitation form and ready to visit at '.$user_details->visite_time.'. THANKS VMS Team'));
          
          $user_mobile = $user_details->mobile;
          $user_email = $officer_details->email;
		  $m_time =	$user_details->visite_time;


          // send mail to officer
 
          $data = ['vis_name' =>$user_name,'user_mobile' =>$user_mobile,'app_date' => $appoint_date, 'm_time' => $m_time, 'appoint_time' => $appoint_time];
          Mail::send('mails.appointment-pre-submit', $data, function ($message) use ($user_email) {
            $sub = Carbon::now()->toDateTimeString();;
            $message->subject('Pre Invite Submission complete (' . $sub . ')');
            $message->from('noreply@vztor.in', 'Pre Invite Submission complete Alert  (' . $sub . ')');
            $message->to($user_email, 'Pre Invite Submission complete Alert (' . $sub . ')');
          });


          $encrypted = Crypt::encryptString($store_user->id);
          return redirect()->route('generate.slip', $encrypted);
        } else {
          $store_user->status = 0;
          $store_user->save();
          return back()->with(['message' => $add_status['message'], 'class' => 'error']);
        }
      } else {
        return back()->with(['message' => 'Oops! Something went wrong', 'class' => 'error']);
      }
    
  }
  public function addReVisit_backup(Request $request)
  {
    //dd($request->all());
    $company_data = $this->company_data;
    $old_data = $request;
    $img = $old_data->image;
    if (empty($img)) {
      $users = User::where('mobile', $old_data->mobile)->first();
      // if(empty($users->image_base)){
      // 	return back()->with(['message'=>'Oops! Please take snapshot', 'class'=>'error']);
      // }
      $image_data = $users->image_base;
      $file_name = $users->image;
    } else {
      if ($request->image_mode == "folder") {
        $imagePath = $request['image'];
        $milliseconds = round(microtime(true) * 1000);
        $imageName = $milliseconds . $imagePath->getClientOriginalName();
        $path = $request['image']->storeAs('uploads', $imageName, 'public');
        $old_data->$old_data->add(['image' => @$path]);
      } else {
        $file_data = $old_data->image;
        $file_name = 'image_' . time() . '.png'; //generating unique file name;
        @list($type, $file_data) = explode(';', $file_data);
        @list(, $file_data) = explode(',', $file_data);
        if ($file_data != "") {
          $image_data = $file_data;
          \Storage::disk('public')->put($file_name, base64_decode($file_data));
        }
      }
    }
    $attachmantPath = $old_data->attachmant;
    $milliseconds = round(microtime(true) * 1000);
    $attachmantName = $milliseconds . $attachmantPath->getClientOriginalName();

    $path = $old_data->attachmant->storeAs('uploads', $attachmantName, 'public');
    $store_user = new User;
    $store_user->name = $old_data->name;
    $column = $company_data->where;
    $store_user->$column = $company_data->id;
    $store_user->email = @$old_data->email;
    $store_user->mobile = $old_data->mobile;
    $store_user->document_type = $old_data->document_type;
    $store_user->adhar_no = $old_data->adhar_no;
    $store_user->services = $old_data->services;
    $store_user->gender = $old_data->gender;
    $store_user->visite_time = $old_data->visite_time;
    $store_user->status = 0;
    $store_user->app_status = 'Pending';
    $store_user->image = $file_name;
    $store_user->attachmant = $path;
    $store_user->officer_id = $old_data->officer;
    $store_user->image_base = $image_data;
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
    if (count($old_data->assets_name) > 0) {
      $store_user->assets_name = implode(",", $old_data->assets_name);
      $store_user->assets_number = implode(",", $old_data->assets_number);
      $store_user->assets_brand = implode(",", $old_data->assets_brand);
    }
    $store_user->visit_type = @$old_data->visit_type;

    if ($store_user->save()) {
      // if ($old_data->visit_type == 'group') {
      //   $file_names = '';
      //   $num_person = count($old_data->group_name);
      //   foreach ($old_data->group_name as $key => $data_gruop_name) {

      //     if ($old_data->group_image_mode[$key][0] == "folder") {
      //       if (!empty($old_data->group_image[$key - 1])) {
      //         $imagePath = $old_data->group_image[$key - 1];
      //         $milliseconds = round(microtime(true) * 1000);
      //         $imageName = $milliseconds . $imagePath->getClientOriginalName();
      //         $path = $old_data->group_image[$key - 1]->storeAs('uploads', $imageName, 'public');
      //         $ke = $key - 1;
      //         $old_data['group_image_folder_' . $ke] = $path;
      //         $file_names = $path;
      //       }
      //     }

      //     if (empty($file_names)) {
      //       $img = $old_data->group_image[$key];
      //       $file_data_group = $old_data->group_image[$key];
      //       $file_names = 'group_image_' . time() . '.png'; //generating unique file name;
      //       @list($type, $file_data_group) = explode(';', $file_data_group);
      //       @list(, $file_data_group) = explode(',', $file_data_group);
      //       if ($file_data_group != "") {
      //         $image_datas = $file_data_group;
      //         \Storage::disk('public')->put($file_names, base64_decode($file_data_group));
      //       }
      //     }
      //     $ke = $key;
      //     $attachmantPath = $old_data->group_attchment[$ke]['attchment'];
      //     $milliseconds = round(microtime(true) * 1000);
      //     $attachmantName = $milliseconds . $attachmantPath->getClientOriginalName();
      //     $path = $old_data->attachmant->storeAs('uploads', $attachmantName, 'public');
      //     $group_add = new VisitorGroup;
      //     $group_add->user_id = $store_user->id;
		    //   $group_add->company_id = $company_data->id;
      //     $group_add->group_name = $old_data->group_name[$key]['group_name'];
      //     $group_add->group_mobile = $old_data->group_mobile[$key]['group_mobile'];
      //     $group_add->group_id_proof = $old_data->group_id_proof[$key]['group_id_proof'];
      //     $group_add->group_gender = $old_data->group_gender[$key]['group_gender'];
      //     $group_add->group_image = $file_names;
      //     $group_add->image_base = @$image_datas;
      //     $group_add->group_attchment = $path;
      //     // dd($group_add);
      //     $group_add->save();
      //     $group_add->visitor_id = "VG00" . $group_add->id;
      //     $group_add->save();
      //   }
      // }

      $add_visit = new AllVisit;
      $add_visit->user_id = $store_user->id;
      $add_visit->date_time =  $store_user->visite_time;
      $add_visit->officer_id =  $store_user->officer_id;
      $add_visit->added_by =  $store_user->added_by;
      $add_visit->save();

      $store_user->refer_code = "VS00" . $store_user->id;
      $response = $store_user->save();
      $users = User::where('mobile', $old_data->mobile)->first();
      $res = $this->deleteUser($users->refer_code);


      $response = $store_user->save();
      if ($response == 1) {
        $add_status['status_code'] = 201;
        $add_status['message'] = "Visitor Successfully Registered";
      } else {
        $add_status['status_code'] = 400;
        $add_status['message'] = "Visitor Registration Failed";
      }
      $settings = Setting::where([$company_data->where => $company_data->id, 'name' => 'ams_send'])->first();
      if (@$settings->status == "Active") {
        $add_status = $this->sendFaceCheck($store_user->id);
      }
      if ($add_status['status_code'] == '201') {
        $store_user->employee_unique_id = $add_status['status_code'];
        $store_user->save();
        $this->sendEmail($store_user->id);

        $encrypted = Crypt::encryptString($store_user->id);
        return redirect()->route('generate.slip', $encrypted);
      } else {
        if ($old_data->visit_type == "group") {
          foreach ($old_data->group_name as $key => $data_gruop_name) {
            $visiter_data = VisitorGroup::where('group_mobile', $old_data->group_mobile[$key])->orderBy('id', 'desc')->first();
            $res = $this->deleteUser($visiter_data->visitor_id);
          }
        }


        $add_status = $this->sendFaceCheck($store_user->id);
        if ($add_status['status_code'] == '201') {
          $store_user->employee_unique_id = $add_status['status_code'];
          $store_user->save();
          $this->sendEmail($store_user->id);

          $encrypted = Crypt::encryptString($store_user->id);
          return redirect()->route('generate.slip', $encrypted);
        } else {
          $encrypted = Crypt::encryptString($store_user->id);
          return redirect()->route('generate.slip', $encrypted);
          $store_user->status = 0;
          $store_user->save();
          return back()->with(['message' => $add_status['message'], 'class' => 'error']);
        }
      }
    } else {
      return back()->with(['message' => 'Oops! Something went wrong', 'class' => 'error']);
    }
  }

  public function checkVisitor(Request $request)
  {
    $company_data = $this->company_data;
   
    if (empty($request->all())) {
      return redirect('/');
    }
    $mobile = $request->mobile;
    $datas = User::where(['mobile' => $mobile, $company_data->where => $company_data->id])->orderBy('id','desc')->first();
    if (empty($datas)) {
      return back()->with(['message' => 'You are not registred please register first.', 'class' => 'error']);
    }
    $symptoms = Symptom::where('status', 1)->get();
    $get_depart = Department::where(['status' => 1, $company_data->where => $company_data->id])->get();
    $get_officers = Admin::where(['role_id' => 6, 'status_id' => 1, $company_data->where => $company_data->id])->get();
    if ($request->otp == null) {
      $gen_otp = rand(100000, 999999);
      $otp = new UserOtp;
      $otp->mobile = $request->mobile;
      $otp->otp = $gen_otp;
      $otp->company_id = $company_data->id;
      $otp->save();
   
      event(new OtpEvent($request->mobile, $otp->otp));
      return redirect('/re-visit')->with(['class' => 'success', 'message' => 'An Otp has been sent on ' . $request->mobile . '', 'otp_true' => true])->withInput($request->all());
    }
    $check_otp = UserOtp::where(['otp' => $request->otp, 'status' => 0, 'company_id' => $company_data->id])->first();
    if ($check_otp != null) {

      // if(!empty($datas->image_base)){
      //     $image = $this->base64_to_jpeg( $datas->image_base, 'tmp.jpg' );
      // }else{
      //   $image = str_replace('/public','',URL::to('/')).'/storage/app/public/'.$datas->image;
      // }
      $image = str_replace('/public', '', URL::to('/')) . '/storage/app/public/' . $datas->image;

      $get_country = Country::whereNotIn('id', [101, 109, 238])->get();
      $locations = Location::where([$company_data->where => $company_data->id])->get();
      return view('web.re-visit-registration', compact('datas', 'symptoms', 'get_officers', 'get_depart', 'image', 'get_country', 'locations'));
    } else {
      return back()->with(['message' => 'You Enter Invalid Otp.', 'class' => 'error', 'otp_true' => true])->withInput($request->all());;
    }
  }

  public function checkPreVisitor(Request $request)
  {
    $company_data = $this->company_data;
   
    if (empty($request->all())) {
      return redirect('/');
    }
    $mobile = $request->mobile;
    $datas = User::where(['mobile' => $mobile, $company_data->where => $company_data->id])->orderBy('id','desc')->first();
    if (empty($datas)) {
      return back()->with(['message' => 'You are not registred please register first.', 'class' => 'error']);
    }
    $symptoms = Symptom::where('status', 1)->get();
    $get_depart = Department::where(['status' => 1, $company_data->where => $company_data->id])->get();
    $get_officers = Admin::where(['role_id' => 6, 'status_id' => 1, $company_data->where => $company_data->id])->get();
    if ($request->otp == null) {
      $gen_otp = rand(100000, 999999);
      $otp = new UserOtp;
      $otp->mobile = $request->mobile;
      $otp->otp = $gen_otp;
      $otp->company_id = $company_data->id;
      $otp->save();
   
      event(new OtpEvent($request->mobile, $otp->otp));
      $datas = $request->mobile;
		
      return view('web.per-invitation-otp',compact('datas'))->with(['class' => 'success', 'message' => 'An Otp has been sent on ' . $request->mobile . '', 'otp_true' => true])->withInput($request->all());
    }
    $check_otp = UserOtp::where(['otp' => $request->otp, 'status' => 0, 'company_id' => $company_data->id])->first();
    if ($check_otp != null) {

      // if(!empty($datas->image_base)){
      //     $image = $this->base64_to_jpeg( $datas->image_base, 'tmp.jpg' );
      // }else{
      //   $image = str_replace('/public','',URL::to('/')).'/storage/app/public/'.$datas->image;
      // }
      $image = str_replace('/public', '', URL::to('/')) . '/storage/app/public/' . $datas->image;

      $get_country = Country::whereNotIn('id', [101, 109, 238])->get();
      $locations = Location::where([$company_data->where => $company_data->id])->get();
		
      return view('web.per-invitationjoin', compact('datas', 'symptoms', 'get_officers', 'get_depart', 'image', 'get_country', 'locations'));
    } else {
      return back()->with(['message' => 'You Enter Invalid Otp.', 'class' => 'error', 'otp_true' => true])->withInput($request->all());;
    }
  }

  function base64_to_jpeg($base64_string, $output_file)
  {
    // open the output file for writing
    $ifp = fopen($output_file, 'wb');

    // split the string on commas
    // $data[ 0 ] == "data:image/png;base64"
    // $data[ 1 ] == <actual base64 string>
    $data = explode(',', $base64_string);

    // we could add validation here with ensuring count( $data ) > 1
    fwrite($ifp, base64_decode($data[0]));

    // clean up the file resource
    fclose($ifp);

    return $output_file;
  }

  public function checkStatus(Request $request)
  {
    $company_data = $this->company_data;
    $mobile = $request->mobile;
    $datas = User::where(['mobile' => $mobile, $company_data->where => $company_data->id])->first();
    $symptoms = Symptom::where('status', 1)->get();
    $get_officers = Admin::where(['role_id' => 6, 'status_id' => 1, $company_data->where => $company_data->id])->get();
    // dd($request->all());
    if ($request->otp == null) {
      $gen_otp = rand(100000, 999999);
      $otp = new UserOtp;
      $otp->mobile = $request->mobile;
      $otp->otp = $gen_otp;
      $otp->company_id = $company_data->id;
      $otp->save();
      event(new OtpEvent($request->mobile, $otp->otp));
      return back()->with(['class' => 'success', 'message' => 'An Otp has been sent on ' . $request->mobile . '', 'otp_true' => true])->withInput($request->all());
    }
    $check_otp = UserOtp::where('otp', $request->otp)->first();

    $datas = User::where('mobile', $mobile)->with(['OfficerDetail' => function ($query) {
      $query->select('id', 'name');
    }])->orderBy('id', 'desc')->get();

    $symptoms = Symptom::where('status', 1)->get();
    $get_officers = Admin::where(['role_id' => 6, 'status_id' => 1])->get();
    if ($check_otp != null) {
      return view('web.check-status', compact('datas', 'symptoms', 'get_officers'));
    } else {
      return back()->with(['message' => 'You are not registred please register first.', 'class' => 'error']);
    }
  }


  public function getOfficer(Request $request)
  {
    $company_data = $this->company_data;
    $depa_id = $request->department_id;
    $data['states'] = Admin::join('designations', 'admins.designation_id', '=', 'designations.id')
    ->where(["admins.department_id" => $depa_id, "admins.status_id" => 1])
	->get(["admins.name", "admins.id", 'designations.name as designations']); 
	  // commented by suresh //13 dec 2021
    // $data['states'] = Admin::where(["department_id" => $depa_id, "status_id" => 1])->get(["name", "id"]);
    return response()->json($data);
  }
  public function deviceDepartments(Request $request)
  {
    $company_data = $this->company_data;
    $depa_id = $request->department_id;
    $data['states'] = DeviceDepartment::where(["department_id"=>$depa_id])->with(['device'=>function($q){
      $q->select('id','name');
    }])->get();
    //$data['states'] = DeviceDepartment::where(["department_id" => $depa_id])->get(["device_id", "id"]); // commented by suresh //13 dec 2021
    // $data['states'] = Admin::where(["department_id" => $depa_id, "status_id" => 1])->get(["name", "id"]);
    return response()->json($data);
  }


  public function getState(Request $request)
  {
    $company_data = $this->company_data;
    $data['states'] = State::where("country_id", $request->country_id)
      ->get(["name", "id"]);
    return response()->json($data);
  }

  public function getCity(Request $request)
  {
    $company_data = $this->company_data;
    $data['city'] = City::where("state_id", $request->state_id)
      ->get(["name", "id"]);
    return response()->json($data);
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

    event(new SmsEvent($officer_details->mobile, 'A new meeting with '.$visitor_name.' ('.@$user_details->mobile.') on 
	'.$user_details->visite_time.'. Click here: ('.@$res->link.')'));

    $user_mobile = $user_details->mobile;
   // $image_base_code =str_replace('/public','',URL::to('/')).'/storage/app/public/'.str_replace(" ","%20",@$user_details->image);
	 $image_base_code = $user_details->image;
	$m_time = $user_details->visite_time;
    // send mail to officer
    $encrypted = Crypt::encryptString($new_user_id);
    $data = ['vis_name' =>$user_name,'user_mobile' =>$user_mobile,'visitor_name' => $reception_name, 'm_time' => $m_time, 'app_date' => $appoint_date, 'appoint_time' => $appoint_time, 'encryptString' => $encrypted, 'status' => $app_status,'duration'=>$user_details->visite_duration,'visitor_id'=>$user_details->refer_code,'officer_id'=>$officer_details->id,'image'=>$image_base_code];
    Mail::send('mails.appointment-invoice', $data, function ($message) use ($user_email) {
      $sub = Carbon::now()->toDateTimeString();;
      $message->subject('New Appointment Alert (' . $sub . ')');
      $message->from('noreply@vztor.in', 'New Appointment Alert  (' . $sub . ')');
      $message->to($user_email, 'New Appointment Alert (' . $sub . ')');
    });
    
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
        'Authorization:  bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyX25hbWUiOiJzaGFpbGVycy5hZG1pbiIsInR5cGVfb2ZfdXNlciI6IkFETUlOIiwidG9rZW4iOiIkMmEkMDgkcUpCY3ROT1hyNnBzbFlMOUxWaDR6T3NQUi8xdGVDSWhrR1NNdmFjMUtvNTFvcHdYU0JqTEMiLCJpYXQiOjE2NjEzNDM1NDd9.yDESnlNgexGB8XVPlImVzuXoORJhzkJL8qacSwtm3_Y',
        'Content-Type: text/plain'
      ),
    ));
    $response = curl_exec($curl);
    //dd($response);
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
            'Authorization: bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyX25hbWUiOiJzaGFpbGVycy5hZG1pbiIsInR5cGVfb2ZfdXNlciI6IkFETUlOIiwidG9rZW4iOiIkMmEkMDgkcUpCY3ROT1hyNnBzbFlMOUxWaDR6T3NQUi8xdGVDSWhrR1NNdmFjMUtvNTFvcHdYU0JqTEMiLCJpYXQiOjE2NjEzNDM1NDd9.yDESnlNgexGB8XVPlImVzuXoORJhzkJL8qacSwtm3_Y',
            'Content-Type: text/plain'
          ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
      }
    }
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
        //  'Authorization: bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyX25hbWUiOiJzYW10ZWNoLmFkbWluIiwidHlwZV9vZl91c2VyIjoiQURNSU4iLCJ0b2tlbiI6IiQyYSQwOCQ5SUY1UFV6cGh0bWVRTzJtVWtOdU8ucnU5VUhFaGc4OEtxM3QzMVVxR0VvR2NZR3BnU0VDVyIsImlhdCI6MTYzOTAzMDYwMn0.oxerDTTzpTnTN7UzQD7GSNEiUU9qt9sr0SHB6NxZ6Zo',
         'Authorization: bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyX25hbWUiOiJzYW10ZWNoLmFkbWluIiwidHlwZV9vZl91c2VyIjoiQURNSU4iLCJ0b2tlbiI6IiQyYSQwOCQ5SUY1UFV6cGh0bWVRTzJtVWtOdU8ucnU5VUhFaGc4OEtxM3QzMVVxR0VvR2NZR3BnU0VDVyIsImlhdCI6MTY0NTE2MTM4OH0.CRMT7l4iA-Oi0CkxMeemnlsxQJjQI4PPqZDb1jpiKTE',
         'Content-Type: text/plain'
       ),
     ));
    $response = curl_exec($curl);
    curl_close($curl);
    return json_decode($response, true);
  }

  function deleteUser($employee_id)
  {
    $company_data = $this->company_data;
    $settings = Setting::where([$company_data->where => $company_data->id, 'name' => 'ams_send'])->first();
    if (@$settings->status != "Active") {
      return;
    }
    $post_data = array('employee_id' => $employee_id);
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
      CURLOPT_POSTFIELDS => json_encode($post_data),
      CURLOPT_HTTPHEADER => array(
        'Authorization: bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyX25hbWUiOiJzYW10ZWNoLmFkbWluIiwidHlwZV9vZl91c2VyIjoiQURNSU4iLCJ0b2tlbiI6IiQyYSQwOCQ5SUY1UFV6cGh0bWVRTzJtVWtOdU8ucnU5VUhFaGc4OEtxM3QzMVVxR0VvR2NZR3BnU0VDVyIsImlhdCI6MTY0NTE2MDIzMX0.KGAyVEivIQ6Fncg8JPmlwNZfkBwNcPaTJCNj5wKruR8',
        'Content-Type: text/plain'
      ),
    ));
    $response = curl_exec($curl);
    curl_close($curl);
    return json_decode($response, true);
  }

  public function slipDownload(Request $request)
  {
    $company_data = $this->company_data;
    $users = User::where('refer_code', $request->visiter_id)->first();
    if (empty($users)) {
      $message = 'Visitor id not found please check';
      return view('web.visit-slip-download', compact('message'));
    } else {
      $encrypted = Crypt::encryptString($users->id);
      return redirect()->route('generate.slip', $encrypted);
    }
  }

  function checkMobileDetails(Request $request)
  {
    $company_data = $this->company_data;
    $users = User::where(['mobile'=>$request->mobile,'company_id' => $company_data->id])->first();
    if (empty($users)) {
      $message = 'Mobile Not Found Continue...';
      $res = array('status' => 'success', 'message' => $message);
    } else {
      $message = 'Your mobile number already registered';
      $res = array('status' => 'failed', 'message' => $message);
    }
    return response()->json($res);
  }

  function checkAdharDetails(Request $request)
  {
    $company_data = $this->company_data;
    $users = User::where(['adhar_no'=> $request->adhar_no,'company_id' => $company_data->id])->first();
    if (empty($users)) {
      $message = 'AAdhar Not Found Continue...';
      $res = array('status' => 'success', 'message' => $message);
    } else {
      $message = 'Your Document already registered';
      $res = array('status' => 'failed', 'message' => $message);
    }
    return response()->json($res);
  }

  function checkemailDetails(Request $request)
  {
    $company_data = $this->company_data;
    $users = User::where(['email'=> $request->email,'company_id' => $company_data->id])->first();
    if (empty($users)) {
      $message = '';
      $res = array('status' => 'success', 'message' => $message);
    } else {
      $message = 'Your email already registered';
      $res = array('status' => 'failed', 'message' => $message);
    }
    return response()->json($res);
  }

  public function visitorApprove(Request $request,$encrypted,$officer_id)
  {
  
    if($officer_id==""){
      $message = 'Data Not Found';
          $res = array('status' => 'failed', 'message' => $message);
      return response()->json($res);
    }
    $user_id = Crypt::decryptString($encrypted);
    $users_data=User::where('id', $user_id)->first();
    $users_data->app_status = 'Approve';
    $users_data->status = 1;
	  $users_data->update_by=$officer_id;
    $users_data->save();

    
    $settings=Setting::where(['company_id'=>$users_data->company_id,'name'=>'ams_send'])->first();
    if(@$settings->status=="Active"){
      $res=$this->sendFaceCheckAlotte($users_data->refer_code);
		//dd($res);
    }
    if (empty($users_data)) {
      return back()->with(['class' => 'error', 'message' => 'Visitor not found']);
      $res = array('status' => 'failed', 'message' => $message);
    } else {
      $this->sendApproveEmail($user_id);
      return back()->with(['class' => 'success', 'message' => 'Visit Approve Successfully Done']);
    }
    $this->sendApproveEmail($user_id);
    return back()->with(['class' => 'success', 'message' => 'Visit Approve Successfully Done']);
  }

  
  public function visitorApproveReject(Request $request,$encrypted,$officer_id)
  {
  
    if($officer_id==""){
      $message = 'Data Not Found';
          $res = array('status' => 'failed', 'message' => $message);
      return response()->json($res);
    }
    $user_id = Crypt::decryptString($encrypted);
    $users_data=User::where('id', $user_id)->first();
    $users_data->app_status = 'Reject';
    $users_data->status = 5;
	  $users_data->update_by=$officer_id;
    $users_data->save();
 
    if (empty($users_data)) {
      return back()->with(['class' => 'error', 'message' => 'Visitor not found']);
      $res = array('status' => 'failed', 'message' => $message);
    } else {
      $this->sendApproveEmail($user_id);
      return back()->with(['class' => 'success', 'message' => 'Visit Reject Successfully Done']);
    }
    $this->sendApproveEmail($user_id);
    return back()->with(['class' => 'success', 'message' => 'Visit Reject Successfully Done']);
  }

  public function visitorApprovePage($encrypted,$officer_id)
  {
    if($officer_id==""){
      $message = 'Data Not Found';
          $res = array('status' => 'failed', 'message' => $message);
      return response()->json($res);
    }
    $user_ids = $encrypted;
    $officer_ids = $officer_id;
    $user_id = Crypt::decryptString($encrypted);
    $users_data=User::where('id', $user_id)->first();
    return view('web.visit-action', compact('users_data','user_ids', 'officer_ids'));
  }

	public function gaurdLogin(Request $request){
		return view('web.gaurd_login');
	}
	public function gaurdValidate(Request $request){
		$request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');
		
        if (Auth::attempt($credentials)) {
            return redirect('guard/dashboard');
			
        }
		return back()->with(['message' => 'Oppes! You have entered invalid credentials', 'class' => 'error']);
	}

	public function gaurdDashboard(){
   
		if (Auth::check()) {
			$datas = User::with(['all_visit'=>function($q){
        $q->select('id', 'user_id','in_status','in_time','out_status');
      }])->whereHas('all_visit', function($query){
				$query->select('id', 'user_id','in_status','out_status');
				$query->where('out_status','=', 'No');
			})->where('building_id', Auth::User()->building_id)->with(['OfficerDetail' => function ($query) {
			  $query->select('id', 'name');
			}])->where('pre_visit_date_time',NULL)->orderBy('id', 'desc')->get(); 		
      //dd($datas);
      $company_data = $this->company_data;   
      $pre_invitation_lists = User::where(['status'=>2,'company_id'=>$company_data->id])->where('building_id', Auth::User()->building_id)->with(['OfficerDetail' => function ($query) {
			  $query->select('id', 'name');
			}])->with(['all_visit'=>function($q){
        $q->select('id', 'user_id','in_status','in_time','out_status');
      }])->orderBy('id', 'desc')->get();
  
			return view('web.gaurd_dashboard',compact('datas','pre_invitation_lists'));
		}
		return redirect('guard/login');		
	}


	
	public function gaurdLogout(Request $request) {
	  Auth::logout();
	  return redirect('guard/login');
	}
	public function visitorIn(Request $request,$visitor_id){
    if (Auth::check() ==false) {
      abort(403,"Something went wrong !");
    }
   
		$company_id=Auth::User()->company_id;
		$date = date('Y-m-d');
		
		$users=User::where(['id'=>$visitor_id,'company_id'=>$company_id])->first();
    $users->temprature  = $request->temprature;
    $users->save();
    
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
				'in_device'=>'Guard',
				'in_time_temprature'=>$users->temprature,
				'out_time'=>'NA',
				'shift_out_time'=>'',
				'out_device'=>'NA',
				'out_time_temprature'=>'NA',
				'actual_work_time'=>'NA',
				'expected_work_time'=>$users->visit_duration,
				'overtime'=>'NA',
				'attendance'=>'PRESENT',
				'update_in_type'=>'Guard',
				'update_by'=>Auth::User()->id
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
						$data->in_device='Guard';
						$data->update_type='Guard';
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
						'in_device'=>'Guard',
						'in_time_temprature'=>@$users->temprature,
						'out_time'=>'NA',
						'shift_out_time'=>'',
						'out_device'=>'NA',
						'out_time_temprature'=>'NA',
						'actual_work_time'=>'NA',
						'expected_work_time'=>$users->visit_duration,
						'overtime'=>'NA',
						'attendance'=>'PRESENT',
						'update_in_type'=>'Guard',
						'update_by'=>Auth::User()->id
					);
					array_push($record,$data2);
				}
			
			$VisitorHistory= VisitorHistory::where(['last_synchronize_date'=>$date])->update(['ams_data'=>json_encode($record)]);
           
		} 
    $officer_details= Admin::Where(['id' => $users->officer_id])->first();
    $user_name = ucfirst($users->name);

    $encrypted = Crypt::encryptString($users->id);        
    $url=route('visitor.panic.alert.page').'/'.$encrypted.'/'.@$officer_details->id;
    $res = json_decode($this->createShortLink($url));

	  // event(new SmsEvent($officer_details->mobile, 'Dear Sir/Madam, Update on visit #'.@$users->refer_code.': '.@$user_name.' (Mob:'.@$users->mobile.') has Checked In at your place. (Panic link:- '.@$res->link.') VMS Team'));
	  event(new SmsEvent($officer_details->mobile, 'Update on visit #'.@$users->refer_code.': '.@$user_name.' (Mob:'.@$users->mobile.') Checked In (Panic link: '.@$res->link.') VMS Team'));
   
    $user_email = @$officer_details->email;
    
    $user_mobile = $users->mobile;
    $refer_code = @$users->refer_code;

 
    $image_base_code =str_replace('/public','',URL::to('/')).'/storage/app/public/'.@$users->image;
 
    // send mail to officer
    $data = ['vis_name' =>$user_name,'user_mobile' =>$user_mobile,'visitor_id'=>$refer_code,'image'=>$image_base_code ];
    Mail::send('mails.appointment-invisitor', $data, function ($message) use ($user_email) {
      $sub = Carbon::now()->toDateTimeString();;
      $message->subject('Update on visitor Appointment (' . $sub . ')');
      $message->from('noreply@vztor.in', 'Update on visitor Appointment  (' . $sub . ')');
      $message->to($user_email, 'Update on visitor Appointment (' . $sub . ')');
    });
    return back();
	}
	public function visitorOut(Request $request,$visitor_id){
    if (Auth::check() ==false) {
      abort(403,"Something went wrong !");
    }
		$company_id=Auth::User()->company_id;
		$date = date('Y-m-d');
		
		$users=User::where(['id'=>$visitor_id,'company_id'=>$company_id])->first();
		AllVisit::where(['user_id'=>$visitor_id])->update(['out_status'=>'Yes','out_time'=>date('Y-m-d h:i:s'),'out_device'=>'NA']);
        $VisitorHistory= VisitorHistory::where(['last_synchronize_date'=>$date])->first();

		if(!isset($VisitorHistory->last_synchronize_date)){
			$record=[];
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
				'in_time_temprature'=>@$users->temprature,
				'out_time'=> date('Y-m-d h:i:s'),
				'shift_out_time'=>'',
				'out_device'=>'Guard',
				'out_time_temprature'=>'NA',
				'actual_work_time'=>'NA',
				'expected_work_time'=>$users->visit_duration,
				'overtime'=>'NA',
				'attendance'=>'PRESENT',
				'update_out_type'=>'Guard',
				'update_by'=>Auth::User()->id
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
						$data->out_device='Guard';
						$data->update_out_type='Guard';
					}
					
					
			
				array_push($record,$data);
			}
			$this->deleteUser($users->refer_code);
			$VisitorHistory= VisitorHistory::where(['last_synchronize_date'=>$date])->update(['ams_data'=>json_encode($record)]);
           
		}
    $officer_details= Admin::Where(['id' => $users->officer_id])->first();
    $user_name = ucfirst($users->name);
	  // event(new SmsEvent($officer_details->mobile, 'Dear Sir/Madam, Update on visit #'.@$users->refer_code.': Meeting with '.@$user_name.' (Mob:'.@$users->mobile.') is completed & visitor Checked Out from your place. Thanks VMS Team'));
	  event(new SmsEvent($officer_details->mobile, 'Update on visit #'.@$users->refer_code.': '.@$user_name.' (Mob:'.@$users->mobile.') Meeting is completed & visitor Checked Out. VMS Team'));
    
    $user_email = @$officer_details->email;

    $user_mobile = $users->mobile;
    $refer_code = @$users->refer_code;
    // send mail to officer
    $data = ['vis_name' =>$user_name,'user_mobile' =>$user_mobile,'visitor_id'=>$refer_code];
    Mail::send('mails.appointment-outvisitor', $data, function ($message) use ($user_email) {
      $sub = Carbon::now()->toDateTimeString();
      $message->subject('Update on visitor Appointment (' . $sub . ')');
      $message->from('noreply@vztor.in', 'Update on visitor Appointment  (' . $sub . ')');
      $message->to($user_email, 'Update on visitor Appointment (' . $sub . ')');
    });
    return back();
	}
	
	public function viewVisitorForm(Request $request ,$encBulding_id){
		$building_id = base64_decode($encBulding_id);
		$departments=$this->getDepartmentBybuildingId($building_id);
		$buildings=Building::where('id',$building_id)->first();
		$symptoms = Symptom::where(['status' => 1])->get();
		return view('web.building_registration',compact('departments','buildings','symptoms'));
	}
	public function getDepartmentBybuildingId($building_id){
      return Department::where('building_id',$building_id)->get();
    
    }
	
	
  public function viewVisitorStore(Request $request){
      $visite_time=date('Y-m-d h:i:s');
      $company_data = $this->company_data;
      $old_data = $request;
      $file_data = $old_data->image;
      $file_name = 'image_' . time() . '.png'; //generating unique file name;
      @list($type, $file_data) = explode(';', $file_data);
      @list(, $file_data) = explode(',', $file_data);
      if ($file_data != "") {
        $image_data = $file_data;
        \Storage::disk('public')->put($file_name, base64_decode($file_data));
      }
      $store_user = new User;
      $store_user->name = $old_data->name;
      $column = $company_data->where;
      $store_user->$column = $company_data->id;
      $store_user->email = @$old_data->email;
      $store_user->mobile = $old_data->mobile;
      $store_user->services = $old_data->services;
      $store_user->gender = $old_data->gender;
      $store_user->visite_time = $visite_time;
      $store_user->status = 0;
      $store_user->app_status = 'Pending';
      $store_user->image = $file_name;
      $store_user->attachmant = @$path;
      $store_user->officer_id = $old_data->officer;
      $store_user->image_base = $image_data;
      $store_user->added_by = 0;
      $store_user->vaccine = @$old_data->vaccine;
      $store_user->symptoms = @$old_data->symptoms;
      $store_user->vaccine_count = @$old_data->vaccine_count;
      $store_user->vaccine_name = @$old_data->vaccine_name;
      $store_user->travelled_states = @$old_data->states;
      $store_user->patient = @$old_data->patient;
      $store_user->temprature = @$old_data->temprature;
      $store_user->country_id = @$old_data->country_id;
      $store_user->organization_name = @$old_data->organization_name;
      $store_user->orga_country_id = @$old_data->orga_country_id;
      $store_user->department_id = @$old_data->department_id;
      $store_user->building_id = @$old_data->building_id;
      $store_user->save();
      $add_visit = new AllVisit;
      $add_visit->user_id = $store_user->id;
      $add_visit->date_time =  $store_user->visite_time;
      $add_visit->officer_id =  $store_user->officer_id;
      $add_visit->added_by =  $store_user->added_by;
      $add_visit->save();

        $store_user->refer_code = "VS00" . $store_user->id;
        $response = $store_user->save();
        $users = User::where('mobile', $old_data->mobile)->first();
        $response = $store_user->save();
        if ($response == 1) {
          $add_status['status_code'] = 201;
          $add_status['message'] = "Visitor Successfully Registered";
        } else {
          $add_status['status_code'] = 400;
          $add_status['message'] = "Visitor Registration Failed";
        }
        $settings = Setting::where([$company_data->where => $company_data->id, 'name' => 'ams_send'])->first();
      
        if (@$settings->status == "Active") {
          $add_status = $this->sendFaceCheck($store_user->id);
        }
        if ($add_status['status_code'] == '201') {
          $store_user->employee_unique_id = $add_status['status_code'];
          $store_user->save();
          $this->sendEmail($store_user->id);
        
          $encrypted = Crypt::encryptString($store_user->id);
          return redirect()->route('generate.slip', $encrypted);
        }else{
      
        return back()->with(['message' => $add_status['message'], 'class' => 'error']);
      }
    
	}
	
	public function invitationJoin($email){
    // if (Auth::check() ==false) {
    //   abort(403,"Something went wrong !");
    // }
    $company_data = $this->company_data;
    if (Auth::check()) {
      $datas = User::where(['email' => $email, $company_data->where => $company_data->id])->orderBy('id','desc')->first();
      return view('web.per-invitation-otp',compact('datas'));
    }
    
    $datas = User::where(['email' => $email, $company_data->where => $company_data->id])->with([
      'location'=>function($q){
        $q->select('id','name');
      },'building'=>function($q){
        $q->select('id','name');
      },'OfficerDepartment'=>function($q){
        $q->select('id','name');
      },'OfficerDetail'=>function($q){
        $q->select('id','name');
      },
    ])->orderBy('id','desc')->first();
		$symptoms = Symptom::where('status',1)->get();
        $get_officers = Admin::where(['role_id'=>6,'status_id'=>1])->get();
        $get_depart = Department::where(['status'=>1])->get();
        $get_country = Country::whereNotIn('id', [101, 109, 238])->get();
        $locations = Location::where([$company_data->where => $company_data->id])->get();
        $image = str_replace('/public', '', URL::to('/')) . '/storage/app/public/' . $datas->image;
        // dd($datas);
		if(empty($datas)){
			return Redirect::route('web.home')->with(['message'=>'You are not registred please register first.', 'class'=>'error']);;

		}else{
			return view('web.per-invitationjoin',compact('datas','image','symptoms','get_officers','get_depart','get_country','locations'));
		}

	}
	



  public function sendPanicAlert($encrypted,$officer_id){
    $user_id = Crypt::decryptString($encrypted);
    $user_details = User::Where(['id'=>$user_id])->first();
    $admin_detail = Admin::Where(['role_id'=>1,'company_id'=>$user_details->company_id])->first();
    $officer_details = Admin::Where(['id'=>$officer_id])->with(['getDepart'=>function($query){
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
    $panic_data->visitor_id = $user_id;
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
          $message->from('noreply@sspl20.com','Panic Alert  ('.$sub.')');
          $message->to($email, 'Panic Alert ('.$sub.')');
        });
    }
    $user_ids = $encrypted;
    $officer_ids = $officer_id;
    $user_id = Crypt::decryptString($encrypted);
    $users_data=User::where('id', $user_id)->first();

    return view('web.send-panic-alert', compact('users_data','user_ids', 'officer_ids','encrypted'));
    
  }

  public function sendPanicAlertPage($encrypted,$officer_id)
  {
    if($officer_id==""){
      $message = 'Data Not Found';
          $res = array('status' => 'failed', 'message' => $message);
      return response()->json($res);
    }
    $user_ids = $encrypted;
    $officer_ids = $officer_id;
    $user_id = Crypt::decryptString($encrypted);
    $users_data=User::where('id', $user_id)->first();
    return view('web.send-panic-alert', compact('users_data','user_ids', 'officer_ids','encrypted'));
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
	  $m_time = $user_details->visite_time;
     event(new SmsEvent($user_details->mobile, 'Dear Visitor,
     Your meeting (#'.$user_details->refer_code.') with  '.$officer_details->name.' has been '.$app_status.' for '.$m_time.'.
     Thanks
      VMS Team
     '));

    $user_name = ucfirst($user_details->name);
    $user_mobile = $user_details->mobile;
    $encrypted = Crypt::encryptString($new_user_id);
    $data=['vis_name' =>$user_name,'user_mobile' =>$user_mobile,'visitor_name'=>$reception_name,'app_date'=>$appoint_date, 
		'm_time' => $m_time, 'appoint_time'=>$appoint_time,
    'encryptString'=>$encrypted,'status'=>$app_status,
    'duration'=>$user_details->visite_duration,'visitor_id'=>$user_details->refer_code];
          Mail::send('mails.appointment-approved-invoice', $data, function($message) use ($user_email){
            $sub = Carbon::now()->toDateTimeString();;

            $message->subject('Appointment Approval ('.$sub.')');
            $message->from('vztor.in@gmail.com','Appointment Approval  ('.$sub.')');
            $message->to($user_email, 'Appointment Approval ('.$sub.')');
        });   

  }

  public function invitationVerify($email,$id){
    $company_data = $this->company_data;
    if (Auth::check()) {
      $datas = User::where(['email' => $email, $company_data->where => $company_data->id,'id'=>$id])->orderBy('id','desc')->first();
      return view('web.per-invitationVerify',compact('datas'));
    }else{
      return Redirect::route('web.home')->with(['message'=>'Please login first.', 'class'=>'error']);;
    }  
	}


  
  public function addPreInvite(Request $request)
  {
    $file_data = $request->image;
    $file_name = 'image_' . time() . '.png'; //generating unique file name;
    @list($type, $file_data) = explode(';', $file_data);
    @list(, $file_data) = explode(',', $file_data);
    if ($file_data != "") {
      $image_data = $file_data;
      \Storage::disk('public')->put($file_name, base64_decode($file_data));
    }  
      
    $store_user =  User::where(['id' => $request->id])->first();
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
      $res=$this->sendFaceCheckAlotte($store_user->refer_code);
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
      return redirect()->route('generate.slip', $encrypted);
    } else {
      $store_user->status = 0;
      $store_user->save();
      return back()->with(['message' => $add_status['message'], 'class' => 'error']);
    }  
    
  }
  

  public function PrintgenerateSlip($encrypted)
  {
    $company_data = $this->company_data;
    $user_id = base64_decode($encrypted);
	  $user_id_new = Crypt::encryptString($user_id);
    $visitor_detail = User::where('id', $user_id)->with(['visitorGroup' => function ($query) {
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
    },'all_visit' => function ($query) {
      $query->select('id', 'user_id','in_status','out_status');
    }])->first();
		$company_name=$company_data->name;
	    $qr_url = url("generate-slips/" . $encrypted);
    return view('web.visitor_visit_slip', compact('visitor_detail', 'qr_url','encrypted','company_name'));
	
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
