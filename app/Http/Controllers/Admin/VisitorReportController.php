<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Transaction;
use Carbon\Carbon;
use App\User;
use App\Model\AllVisit;
use App\Model\Department;
use App\Model\VisitorHistory;
class VisitorReportController extends Controller
{
    public function index(Request $request){
        if($request->date_from){

            $start_date = $request->date_from;
            $end_date = $request->date_to;
            $page = 1;
            $all_reports = $this->sendFaceCheckAlotte($start_date,$end_date,$page);
            $record = $all_reports['data'];
            //dd($record);
            return view('admin.visitor-report.list',compact('record'));
        }
        $record = [];
        return view('admin.visitor-report.list',compact('record'));

    }
    function reportSync(Request $request){
       
        $start_date = date('Y-m-d');
        $end_date = date('Y-m-d');
        $page = 2;
        $all_reports = $this->sendFaceCheckAlotte($start_date,$end_date,$page);
        $record=[];
        if(isset($all_reports['total_pages']) && $all_reports['total_pages'] > 0){
            $total_page=$all_reports['total_pages'];
            for($i=1; $i <= $total_page; $i++){
                $all_reports = $this->sendFaceCheckAlotte($start_date,$end_date,$i);
                if(!empty($all_reports['data'])){
                    foreach($all_reports['data'] as $data){
                        if (strpos($data['employee_id'], 'vs') !== false) {							
							$data['update_in_type']='ams';
							$data['update_out_type']='ams';
                            array_push($record,$data);
                        }
						if (strpos($data['employee_id'], 'vms') !== false) {							
							$data['update_in_type']='ams';
							$data['update_out_type']='ams';
                            array_push($record,$data);
                        }
                    }
                }
                
                
            }
        }
        $cidata=$request->session()->get('CIDATA');
	    $cidata_ob=json_decode($cidata);
        $VisitorHistory_data= VisitorHistory::where(['last_synchronize_date'=>$start_date])->first();
     
        if(!isset($VisitorHistory_data->last_synchronize_date)){
			
			$VisitorHistory=new VisitorHistory();
			$VisitorHistory->company_id=$cidata_ob->cid;
			$VisitorHistory->ams_data=json_encode($record);
			$VisitorHistory->last_synchronize_date=$start_date;
			$VisitorHistory->save();         
			
		}else{
			$new_record=[];
           	$all_data = json_decode($VisitorHistory_data->ams_data);
			$visitor_ids=array_column((array)$all_data,'employee_id');
			$delete_employee=[];
			foreach($record as $key => $data ){
				if(in_array($data['employee_id'],$visitor_ids)){
                    $user_visitor = User::where('refer_code',$data['employee_id'])->first();
					if(!empty($user_visitor)){
                    $all_visit_update = AllVisit::where('user_id', $user_visitor->id)->first();
					
					$all_visit_update->in_time =@$data['in_time'];
					$all_visit_update->in_device =@$data['in_device'];
					$all_visit_update->in_status =@$data['in_time']?'Yes':'';
					$all_visit_update->out_device =@$data['out_device'];
					$all_visit_update->out_time =@$data['out_time'];
					$all_visit_update->out_status =@$data['out_time']?@'Yes':'';
					$all_visit_update->save();
					}
				}else{
					if($data['update_in_type']=="ams"){
						$data['in_time']=$data['in_time'];
						$data['in_device']=$data['in_device'];
						$data['update_in_type']='ams';
					}
					if($data['update_out_type']=="ams"){
						$data['out_time']=$data['out_time'];
						$data['out_device']=$data['out_device'];
						$data['update_out_type']='ams';
					}
					if($data['in_time'] !="NA" && $data['out_time'] !="NA"){
						array_push($delete_employee,$data['employee_id']);
					}
					array_push($new_record,$data);
					if(isset($all_data[$key])){
						array_push($new_record,$all_data[$key]);
					}
				}
				
			}
			foreach($delete_employee as $delete){
				$this->deleteUser($delete);
			}
			if(!empty($new_record)){
				$VisitorHistory= VisitorHistory::where(['last_synchronize_date'=>$start_date])->update(['ams_data'=>json_encode($new_record)]);
			}
			
		}
        return response()->json(['message'=>'Your Request SuccessfullY Submitted', 'class'=>'success']);
    }

    public function deleteUserFromAmsToday(Request $request){
        $cidata=$request->session()->get('CIDATA');
        $cidata_ob=json_decode($cidata);
        $company_id=$cidata_ob->cid;
        $date = date('Y-m-d');
        $records=VisitorHistory::where('last_synchronize_date',$date)->where(['company_id'=>$company_id])->first();
		
        $delete_users=json_decode($records->ams_data,true);
		foreach($delete_users as $users){
			$this->deleteUser($users['employee_id']);
		}
    }
    public function Show(Request $request){
            $cidata=$request->session()->get('CIDATA');
            $cidata_ob=json_decode($cidata);
            $company_id=$cidata_ob->cid;
            $start_date = $request->date_from;
            $end_date = $request->date_to;
		
            $records=VisitorHistory::whereBetween('last_synchronize_date',[$start_date,$end_date])->where(['company_id'=>$company_id])->get()->toArray();
            
            $record=[];
            foreach($records as $datas ){
                $all_daat = json_decode($datas['ams_data']);
                
                foreach($all_daat as $key => $data ){
                    
                    array_push($record,$data);
                }
            }
			//dd($request->all());
            return view('admin.visitor-report.list',compact('record'));
    }

    function deleteUser($employee_id)
  {
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
		dd($response);
    return json_decode($response, true);
  }

    function sendFaceCheckAlotte($start_date,$end_date,$page_no){

            $curl = curl_init();

            curl_setopt_array($curl, array(
              CURLOPT_URL => 'https://ams.facer.in/api/public/simplified',
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS =>'{
              "start_date": "'.$start_date.'",
              "end_date": "'.$end_date.'",
              "page": "'.$page_no.'"
            }',
              CURLOPT_HTTPHEADER => array(
                 'Authorization: bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyX25hbWUiOiJzYW10ZWNoLmFkbWluIiwidHlwZV9vZl91c2VyIjoiQURNSU4iLCJ0b2tlbiI6IiQyYSQwOCQ5SUY1UFV6cGh0bWVRTzJtVWtOdU8ucnU5VUhFaGc4OEtxM3QzMVVxR0VvR2NZR3BnU0VDVyIsImlhdCI6MTY0NTE2MDIzMX0.KGAyVEivIQ6Fncg8JPmlwNZfkBwNcPaTJCNj5wKruR8',
              'Content-Type: text/plain'
              ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);

			//dd($response);
            return json_decode($response,true);
    }

    public function visitorReport(Request $request){
        $cidata=$request->session()->get('CIDATA');
        $cidata_ob=json_decode($cidata);
        $company_id=$cidata_ob->cid;

      $department=$this->getAllDepartment($request);
      $report=$request['report'];
      if($report=="total"){
        $record=User::select('users.*','all_visits.in_status','all_visits.in_time','all_visits.out_time','all_visits.out_status')->join('all_visits','users.id','=','all_visits.user_id')->whereDate('visite_time', Carbon::today())->with(['visitorGroup'=>function($query){
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
            }])->where('users.company_id',$company_id)->get();
           
      }elseif($report=="in"){
        $record=AllVisit::select('users.*','all_visits.in_time','all_visits.in_status','all_visits.out_time','all_visits.out_status')->join('users','all_visits.user_id','=','users.id')->where('all_visits.in_status','Yes')->where('all_visits.out_status','No')->whereDate('in_time', Carbon::today())->with(['visitorGroup'=>function($query){
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
            }])->where('users.company_id',$company_id)->get();
            dd( $record);
      }elseif($report=="out"){
        $record=AllVisit::select('users.*','all_visits.in_status','all_visits.in_time','all_visits.out_time','all_visits.out_status')->join('users','all_visits.user_id','=','users.id')->where('out_status','Yes')->whereDate('in_time', Carbon::today())->with(['visitorGroup'=>function($query){
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
            }])->where('users.company_id',$company_id)->get();
      }
      
      return view('admin.visitor-report.visitor-analys',compact('record','department','report'));
    }

    public function visitorReportSearch(Request $request){
      // dd($request->all());
      $department=$this->getAllDepartment($request);
      $report=$request['report'];
      $department_id=$request['department_id'];
      $status=$request['status'];
      if($report=="total"){
        $record=User::select('users.*','all_visits.in_status','all_visits.out_time','all_visits.out_status')->join('all_visits','users.id','=','all_visits.user_id')->where(function ($query) use ($department_id,$status) {
          if($department_id!=''){
            $query->where('department_id', $department_id);
          }

          if($status!=''){
            $query->where('status', $status);
          }
          $query->whereDate('visite_time', Carbon::today());
          })->with(['visitorGroup'=>function($query){
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

      }elseif($report=="in"){
        $record=AllVisit::select('users.*','all_visits.in_status','all_visits.out_time','all_visits.out_status')->join('users','all_visits.user_id','=','users.id')
        ->where('all_visits.in_status','Yes')->where('all_visits.out_status','No')
        ->where(function ($query) use ($department_id,$status) {
          if($department_id!=''){
            $query->where('users.department_id', $department_id);
          }

          if($status!=''){
            $query->where('users.status', $status);
          }
          $query->whereDate('users.visite_time', Carbon::today());
          })->with(['visitorGroup'=>function($query){
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
      }elseif($report=="out"){
        $record=AllVisit::select('users.*','all_visits.in_status','all_visits.out_time','all_visits.out_status')->join('users','all_visits.user_id','=','users.id')
        ->where(function ($query) use ($department_id,$status) {
          if($department_id!=''){
            $query->where('department_id', $department_id);
          }

          if($status!=''){
            $query->where('status', $status);
          }
          $query->where('out_status','Yes');
          $query->whereDate('visite_time', Carbon::today());
          })->with(['visitorGroup'=>function($query){
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
      }

      // dd($record);
      return view('admin.visitor-report.visitor-analys',compact('record','department','report'));
    }

    public function getAllDepartment($request){
      $cidata=$request->session()->get('CIDATA');
      $cidata_ob=json_decode($cidata);
      return Department::where($cidata_ob->where,$cidata_ob->cid)->get()->toArray();
    }

    public function chackMarkInVisitor(Request $request){
        $record=AllVisit::join('users','all_visits.user_id','=','users.id')
        ->where('users.visite_time','like', '%' .$request->date .'%')->where('all_visits.out_status','No')->count();
        return response()->json($record);
    }

    public function markOutVisitor(Request $request){
        $record=AllVisit::join('users','all_visits.user_id','=','users.id')
        ->where('users.visite_time','like', '%' .$request->date .'%')->where('all_visits.out_status','No')->count();
        if($record==0){
            $res=array('status'=>'false');
            return response()->json($res);
        }
        $current_date=Carbon::now();
         AllVisit::join('users','all_visits.user_id','=','users.id')
        ->where('users.visite_time','like', '%' .$request->date .'%')
        ->where('all_visits.out_status','No')
        ->update(['out_status'=>'Yes','in_status'=>'Yes','out_time'=>$current_date]);
        $res=array('status'=>'true');
		
        return response()->json($res);
    }
}
