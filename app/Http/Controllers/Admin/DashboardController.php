<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Admin;
use App\Model\AllVisit;
use Image;
use Mail;
use Carbon\Carbon;
use App\Events\OtpEvent;
class DashboardController extends Controller
{
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
        }
            $reports=[];
            $reports['total']=$this->getTotalVisitorData($from_date,$end_date,$company_id);
            $reports['today_in']=$this->getTodatInVisitorData($from_date,$end_date,$company_id);
            $reports['today_out']=$this->getTodayOutVisitorData($from_date,$end_date,$company_id);

            $today_appointments=$this->getTodayAppoinments($company_id); //not in use
            $total_appointments=$this->getTotalAppoinments($company_id); //not in use
            $total_visitor=$this->getTotalVisitor($company_id); //not in use
            $new_appointments=$this->getnewAppoinments($company_id); //not in use


            $all_checkin_visitor= AllVisit::where(['in_status'=>'Yes','out_status'=>'No'])->whereHas('getVisitor', function($query) use($company_id){
				$query->where('company_id',$company_id);
			})->count(); 

            $all_upcoming_visitor= AllVisit::where('date_time','>',Carbon::now())->where(['in_status'=>'No','out_status'=>'No'])->whereHas('getVisitor', function($query) use($company_id){
                    $query->where('company_id',$company_id);
                })->count(); 

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
            return view('admin.dashboard',compact('appointments','reports','total_appointments','today_appointments','total_visitor','new_appointments','last_input','all_checkin_visitor','all_upcoming_visitor','all_overstaying_visitor','all_checkout_visitor'));
        }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)

    {

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

    public function getTodayOutVisitorData($from_date,$end_date,$company_id){
        $total=AllVisit::where(['out_status'=>'Yes','company_id'=>$company_id])->whereBetween('created_at',[$from_date,$end_date])->count();
        return $total;
    }

    public function sendSosMessage(Request $request){
        $cidata=$request->session()->get('CIDATA');
        $cidata_ob=json_decode($cidata);
        $company_id=$cidata_ob->cid;
      $today_in=User::join('all_visits','users.id','=','all_visits.user_id')
      ->where(['all_visits.in_status'=>'Yes','all_visits.out_status'=>'No','company_id'=>$company_id])->whereDate('created_at', Carbon::today())->get()->toarray();
      foreach($today_in as $key =>$val){
        event(new OtpEvent($val['mobile'], 'Its an emengency situation kindly execute the building Asap.'));
      }
      $message = 'Emengency Alert successfuly send';
      $res=array('status'=>'success','message'=>$message);
       $value = $request->session()->get('CIDATA');
       echo $value;
      return response()->json($res);
    }



    /**

     * Store a newly created resource in storage.

     *

     * @param  \Illuminate\Http\Request  $request

     * @return \Illuminate\Http\Response

     */

     public function store(Request $request)

    {



    }



    /**

     * Display the specified resource.

     *

     * @param  int  $id

     * @return \Illuminate\Http\Response

     */

    public function show(Request $request)

    {





    }



    /**

     * Show the form for editing the specified resource.

     *

     * @param  int  $id

     * @return \Illuminate\Http\Response

     */

    public function edit(Request $request, $id)

    {

        //

    }



    /**

     * Update the specified resource in storage.

     *

     * @param  \Illuminate\Http\Request  $request

     * @param  int  $id

     * @return \Illuminate\Http\Response

     */

    public function update(Request $request, $id)

    {

        //

    }



    /**

     * Remove the specified resource from storage.

     *

     * @param  int  $id

     * @return \Illuminate\Http\Response

     */

    public function destroy(Request $request, $id)

    {

        //

    }

     public function reVisite(Request $request){
         $visit_list =[];
        return view('admin.user.revisit-index',compact('visit_list'));
    }

     public function checkVisit(Request $request){
        $cidata=$request->session()->get('CIDATA');
        $cidata_ob=json_decode($cidata);
        $company_id=$cidata_ob->cid;
         $user_visit_id = $request->name;
         $visit_list = User::where(['adhar_no'=>$user_visit_id,'company_id'=>$company_id])->orwhere('mobile',$user_visit_id)->with(['parentDetail'=>function($query){
                    $query->select('id','name', 'role_id');
                },'OfficerDetail'=>function($query){
                    $query->select('id','name', 'role_id');
                }])->orderBy('id', 'desc')->get();



        return view('admin.user.revisit-index',compact('visit_list'));
    }

    public function reVisitform($id){
        $user = User::where('id',$id)->first();
        $last_visit = AllVisit::where('user_id',$user->id)->orderBy('id','desc')->first();
        $get_officers = Admin::where(['role_id'=>6,'status_id'=>1])->get();

        return view('admin.user.revisit-form',compact('user','get_officers','last_visit'));
   }

   public function addRevisit(Request $request){

        $this->validate($request, [

            'revisite_time'=>'required',
            'services'=>'required',
            'officer'=>'required',
        ]);

        $store_user = User::where('id',$request->id)->first();

        $add_status = $this->sendFaceCheck($store_user,$request->revisite_time);

         if($add_status['status_code'] == '201'){
                $this->sendEmail($store_user->id);
                $store_user->employee_unique_id = $add_status['status_code'] ;
                $store_user->services = $request->services;

                $store_user->save();
                $add_visit = AllVisit::where('user_id',$request->id)->first();
                $add_visit->user_id = $store_user->id;
                $add_visit->date_time =  $request->revisite_time;
                $add_visit->officer_id =  $request->officer;
                $add_visit->added_by =  $store_user->added_by;
                $add_visit->save();
              return back()->with(['message'=>'Visitor Registered Successfully', 'class'=>'success']);
            }else{
                 $store_user->status= 0;
                  $store_user->save();
               return back()->with(['message'=>'Oops! Something went wrong', 'class'=>'error']);
            }





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



    function sendFaceCheck($all_data,$date_time){

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
          "employee_name": "'.$all_data->name.'",
          "employee_id": "'.$all_data->refer_code.'",
          "employee_gender": "'.$all_data->gender.'",
          "employee_image": "'.$all_data->image_base.'",
          "employee_email": "'.$all_data->email.'",
          "employee_contact_number": "'.$all_data->mobile.'",
          "contract_type": "PERMANENT",
          "overtime": "30",
          "status": "ACTIVE",
          "date": "'.$date_time.'"
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

}
