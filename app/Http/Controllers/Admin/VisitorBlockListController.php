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
use App\Model\AllVisit;
use App\Events\SmsEvent;
use Illuminate\Support\Facades\Redirect;
use Mail;
use App\Events\OtpEvent;
use App\Model\Symptom;
use App\Model\VisitorGroup;
use Illuminate\Support\Facades\Crypt;
use URL;
class VisitorBlockListController extends Controller
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
                $datas = User::orderBy('id','desc')->select('id','name','mobile','email', 'status', 'image',  'services', 'refer_code','added_by','created_at','officer_id','app_status')->with(['parentDetail'=>function($query){
                    $query->select('id','name', 'role_id');
                }])->where('status','3');
            }
            if($user_role ==4){
                $datas = User::orderBy('id','desc')->select('id','name','mobile','email', 'status', 'image',  'services',  'refer_code','added_by','created_at','officer_id','app_status')->with(['parentDetail'=>function($query){
                    $query->select('id','name', 'role_id');
                }])->where('status','3');
            }

            if($user_role == 5){
                $datas = User::orderBy('id','desc')->select('id','name','mobile','email', 'status', 'image',  'services',  'refer_code','added_by','created_at','officer_id','app_status')->with(['parentDetail'=>function($query){
                    $query->select('id','name', 'role_id');
                }])->where('added_by',$user_id)->Where('status','3');
            }

            if($user_role == 6){
                $datas = User::orderBy('id','desc')->select('id','name','mobile','email', 'status', 'image',  'services',  'refer_code','added_by','created_at','officer_id','app_status')->with(['parentDetail'=>function($query){
                    $query->select('id','name', 'role_id');
                }])->where('officer_id',$user_id)->Where('status','3');
            }

            if($request->dateFrom && $request->dateTo){
                $datas->whereBetween('created_at', [$request->dateFrom.' 00:00:00', $request->dateTo.' 23:59:59']);

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

          }

            $cidata=$request->session()->get('CIDATA');
            $cidata_ob=json_decode($cidata);
            $datas->where($cidata_ob->where,$cidata_ob->cid);//data get by company_id
            // $datas->orderBy('id','desc');

            $records = $datas->limit($request->length)->offset($request->start)->get();

            # fetch table records

            $result['data'] = [];

            foreach ($records as $data) {
             $result['data'][] =['sn'=>@$data->id,'name'=>@$data->name, 'image'=>str_replace("/public","",URL::to("/")).'/storage/app/public/'.@$data->image, 'email'=>@$data->email, 'mobile'=>@$data->mobile,'id'=>@$data->id, 'refer_code'=>'#'.@$data->refer_code, 'edit'=>'Edit', 'status'=>@$data->status=='3'?'<span style="color:red;">Block</span>':'<span style="color:green;">Unblock</span>', 'Appoint_status'=>@$data->app_status, 'parent_detail'=>@$data->parentDetail->name, 'services'=>@$data->services,'appo_status'=>@$data->status];

            }

            return $result;
        }
        return view('admin.visitor-block-lists.list');
    }



    public function userLogin($id){
        $user = User::find($id);
        if($user){
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



    public function create(){
        $get_officers = Admin::where(['role_id'=>6,'status_id'=>1])->get();
        return view('admin.user.create',compact('get_officers'));
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

        $this->validate($request, [
            'name'=>'required',
            'adhar_no'=>'required|unique:users',
            'mobile'=>'required',
            'email'=>'required|email',
            'visite_time'=>'required',
            'services'=>'required',
            'gender'=>'required',
            'image'=>'required',
            'officer'=>'required',
        ]);

        $img = $request->image;




       $file_data = $request->image;
       $file_name = 'image_'.time().'.png'; //generating unique file name;
       @list($type, $file_data) = explode(';', $file_data);
       @list(, $file_data) = explode(',', $file_data);
       if($file_data!=""){ // storing image in storage/app/public Folder
            $image_data = $file_data;
              \Storage::disk('public')->put($file_name,base64_decode($file_data));
        }

         $user_id =  auth('admin')->user()->id;

        $store_user = new User;
        $store_user->name = $request->name;
        $store_user->email = $request->email;
        $store_user->mobile = $request->mobile;
        $store_user->adhar_no = $request->adhar_no;
        $store_user->services = $request->services;
        $store_user->gender = $request->gender;
        $store_user->visite_time = $request->visite_time;
        $store_user->status = $request->status;
        $store_user->app_status ='Pending';
        $store_user->image = $file_name;
        $store_user->officer_id = $request->officer;
        $store_user->image_base = $image_data;
        $store_user->added_by = $user_id;
        $store_user->save();


        $add_visit = new AllVisit;
        $add_visit->user_id = $store_user->id;
        $add_visit->date_time =  $store_user->visite_time;
        $add_visit->officer_id =  $store_user->officer_id;
        $add_visit->added_by =  $store_user->added_by;
        $add_visit->save();

        if($store_user->save()){
            $store_user->refer_code = "VS00".$store_user->id;
            $store_user->save();
            $all_data = $request->all();
            $add_status = $this->sendFaceCheck($all_data,$store_user->refer_code,$image_data);

            if($add_status['status_code'] == '201'){
                $this->sendEmail($store_user->id);
                $store_user->employee_unique_id = $add_status['status_code'] ;
                $store_user->save();

                return back()->with(['message'=>'Visitor Registered Successfully', 'class'=>'success']);
            }else{
                 $store_user->status= 0;
                  $store_user->save();
               return back()->with(['message'=>'Oops! Something went wrong', 'class'=>'error']);
            }
        }else{
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

    public function edit($id)
    {
        $user=User::where('id',$id)->first();
        $visiter_list=VisitorGroup::where('user_id',$id)->get();
        $get_officers = Admin::where(['role_id'=>6,'status_id'=>1])->get();
        $symptoms = Symptom::where('status',1)->get();
        return view('admin.user.edit',compact('user','get_officers','symptoms','visiter_list'));
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




    public function update(Request $request ,$user){

       $data = User::where('id', $user)->first();

        if($request->types=='status') {
            $data =User::find($user);
                Auth::logout($user);
            if ($data->status==2) {
                $data->status = 0;
            }else{
                $data->status = 3;
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
                'visite_time'=>'required',
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

            if(auth('admin')->user()->role_id ==6){

                $store_user->app_status = $request->app_status;
                if($request->app_status == 'Approval'){
                    $this->sendFaceCheckAlotte($store_user->refer_code);
                }
            }
            $store_user->gender = $request->gender;
            $store_user->status = $request->status;
            $store_user->added_by = $user_id ;
            $store_user->officer_id = $request->officer;

            if($store_user->save()){
                  $this->sendEmail($store_user->id);
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
        if($user_details->app_status=="Pending"){
          $app_status='Scheduled';
        }else{
          $app_status=$user_details->app_status;
        }
        if(empty($reception_details)){
          $reception_details=$user_details;
        }
         event(new OtpEvent($officer_details->mobile, 'Dear Sir, '.$reception_details->name.' has scheduled a new visitor meeting on '.$appoint_date.' at '.$appoint_time.'.Kindly login to see the details.'));
        // send mail to patient
        $encrypted = Crypt::encryptString($new_user_id);
         $data=['visitor_name'=>$reception_name,'app_date'=>$appoint_date,'appoint_time'=>$appoint_time,'encryptString'=>$encrypted,'status'=>$app_status];
              Mail::send('mails.appointment-invoice', $data, function($message) use ($user_email){
                $sub = Carbon::now()->toDateTimeString();;

                $message->subject('New Appointment Alert ('.$sub.')');
                $message->from('noreply@sspl20.com','New Appointment Alert  ('.$sub.')');
                $message->to($user_email, 'New Appointment Alert ('.$sub.')');
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
          "employee_gender": "'.$all_data['gender'].'",
          "employee_image": "'.$image_data.'",
          "employee_email": "'.$all_data['email'].'",
          "employee_contact_number": "'.$all_data['mobile'].'",
          "contract_type": "PERMANENT",
          "overtime": "30",
          "status": "ACTIVE",
          "date": "'.$all_data['visite_time'].'"
        }',
          CURLOPT_HTTPHEADER => array(
            'Authorization: bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyX25hbWUiOiJzYW10ZWNoLmFkbWluIiwidHlwZV9vZl91c2VyIjoiQURNSU4iLCJ0b2tlbiI6IiQyYSQwOCQ5SUY1UFV6cGh0bWVRTzJtVWtOdU8ucnU5VUhFaGc4OEtxM3QzMVVxR0VvR2NZR3BnU0VDVyIsImlhdCI6MTYxNzcwNjExNH0.X0s8AWUu-IoYIBlj5X7dMvR2dYyURnanPToXjllmFcA',
            'Content-Type: text/plain'
          ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);


        return json_decode($response,true);
    }


    function sendFaceCheckAlotte($employee_id){

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
          "allotment_type": "ADD_ALL"
        }',
          CURLOPT_HTTPHEADER => array(
            'Authorization: bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyX25hbWUiOiJzYW10ZWNoLmFkbWluIiwidHlwZV9vZl91c2VyIjoiQURNSU4iLCJ0b2tlbiI6IiQyYSQwOCQ5SUY1UFV6cGh0bWVRTzJtVWtOdU8ucnU5VUhFaGc4OEtxM3QzMVVxR0VvR2NZR3BnU0VDVyIsImlhdCI6MTYxODQ3NTI3NX0.mXvnJi_I-8j2eNJJBkHzLBzVp766BdS-Rpk95BHz7RI',
            'Content-Type: text/plain'
          ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);


        return json_decode($response,true);
    }




}
