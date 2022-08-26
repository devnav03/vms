<?php
namespace App\Http\Controllers\Admin;

use App\Admin;
use App\Model\Building;
use App\Model\Department;
use App\Model\MeetingsInvite;
use App\Model\Location;
use App\Model\ConferenceRoom;
use App\Model\ConferenceMeeting;
use App\Model\VisitorHistory;
use App\Model\Meeting;
use App\Model\Menu;
use App\Model\Permission;
use App\Model\PermissionRole;
use App\Http\Controllers\Controller;
use Mail;


use Illuminate\Http\Request;
class ManageMeetingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // dd("vivek");/
     
        $cidata=$request->session()->get('CIDATA');
        $user_id =  auth('admin')->user()->id;
        $cidata_ob=json_decode($cidata);
        $result = Meeting::where(['added_by'=>$user_id,'company_id'=>$cidata_ob->cid])->with(['confrence'=>function($q){
            $q->select('id','room');
        }])->get();
        return view('admin.meeting.list',compact('result'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user_id =  auth('admin')->user()->id;
        $company_data = $this->company_data;    
        $menus = ConferenceRoom::where(['added_by'=>$user_id])->with(['getLocation'=>function($q){
            $q->select('id','name');
        },'getBuilding'=>function($q){
			$q->select('id','name');
		},'getDepartment'=>function($q){
			$q->select('id','name');
		},'getDevice'=>function($q){
			$q->select('id','name');
		}])->get();
        $userdetails = Admin::where('id',$user_id)->with(['getLocation'=>function($q){
			$q->select('id','name');
		},'getBuilding'=>function($q){
			$q->select('id','name');
		},'getDepartment'=>function($q){
			$q->select('id','name');
		}])->get();
        $user_details = Admin::where('id',$user_id)->first();
        //dd($user_details);
		return view('admin.meeting.create',compact('menus','user_details','userdetails'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $cidata=$request->session()->get('CIDATA');
        $cidata_ob=json_decode($cidata);
        $user_id =  auth('admin')->user()->id;
        $this->validate($request,[
            'meeting_title' => 'required',
            'room_id' => 'required',
            'from_date' => 'required',
            'to_date' => 'required',
        ]);
        $panic = new Meeting;
        $panic->meeting_title = $request->meeting_title;
        $panic->room_id = $request->room_id;
        $panic->from_date = $request->from_date;
        $panic->to_date = $request->to_date;
        $panic->assigned_by = $request->assigned_by;
        $panic->descriptions = $request->descriptions;
        $panic->guest_id = $request->guest_id;
        $panic->company_id = $cidata_ob->cid;
        $panic->added_by = $user_id;
        $panic->save();
        $meeting_members=explode(',',$request->meeting_members);

        $userdetails = Admin::where('id',$user_id)->with(['getLocation'=>function($q){
			$q->select('id','name');
		},'getBuilding'=>function($q){
			$q->select('id','name');
		},'getDepartment'=>function($q){
			$q->select('id','name');
		}])->get();
        
        $count=count($meeting_members);
        for($i=0;$i<$count;$i++){
            $Meeting = new MeetingsInvite;
            $var=$meeting_members[$i];
            $Meeting->meeting_members=$var;
            $Meeting->company_id=$cidata_ob->cid;
            $Meeting->added_by=$user_id;
            $useremail=$this->extract_emails_from($var);
            $find_mail=implode("\n", $useremail);
            $encrypt_email=base64_encode($find_mail);
            Mail::send('mail',['name' => 'VMS','meeting_title'=>$request->meeting_title,'from_date'=>$request->from_date,'to_date'=>$request->to_date,'descriptions'=>$request->descriptions,'assigned_by'=>$request->assigned_by,'link'=>url('registration-conference-meeting?with='.$encrypt_email.'&room='.$request->room_id.'')], function ($message) use ($request, $useremail){
                $message->from('vztor.in@gmail.com', 'VMS');
                $message->to( $useremail);
                $message->subject("Conference Meeting Notification : @ ".date('d-M-Y H:i:s', strtotime($request->from_date))." To ".date('d-M-Y H:i:s', strtotime($request->to_date))." ( ".$request->assigned_by." )");
            });
            $Meeting->save();
        }
        return redirect()->back()->with(['class'=>'success','message'=>'Meeting created successfully']);
    }
	public function extract_emails_from($string){
        preg_match_all("/[\._a-zA-Z0-9-]+@[\._a-zA-Z0-9-]+/i", $string, $matches);
        return $matches[0];
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($table)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($table)
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
        
        $menus = ConferenceRoom::where(['id'=>$table,'added_by'=>$user_id])->with(['getLocation'=>function($q){
            $q->select('id','name');
        },'getBuilding'=>function($q){
			$q->select('id','name');
		},'getDepartment'=>function($q){
			$q->select('id','name');
		},'getDevice'=>function($q){
			$q->select('id','name');
		}])->first();
        return view('admin.conference.edit',compact('menus','locations','user_details'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $table)
    {
        $this->validate($request,[
            'room' => 'required'
        ]);
        $user_id =  auth('admin')->user()->id;
        $panic = ConferenceRoom::where('id', $table)->first();
        $panic->room = $request->room;
        $panic->location_id = $request->location_id;
        $panic->building_id = $request->building_id;
        $panic->department_id = $request->department_id;
        $panic->officer_id = $request->officer_id;
        $panic->added_by = $user_id;

        if($panic->save()){
            return redirect()->back()->with(['class'=>'success','message'=>'Conference Rooms Update successfully']);
        }else{
            return redirect()->back()->with(['class'=>'error','message'=>'Some error occur']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($table)
    {
        $menu = Meeting::where('id',$table);
        if ($menu->count()) {
            $menu->delete();
        }
        return redirect()->back()->with(['class'=>'success','message'=>'Conference Room deleted successfully']);

    }
    public function AjaxGetUsers(Request $request)
    {
        //dd($request->all());die;
        if(!empty($request->keyword)){
            $value=$request->keyword;
            $user_details = Admin::where('name','like', "%$value%")->with(['getLocation'=>function($q){
                $q->select('id','name');
            },'getBuilding'=>function($q){
                $q->select('id','name');
            },'getDepartment'=>function($q){
                $q->select('id','name');
            }])->get();
            foreach($user_details as $row){
                echo '<li style="padding: 2px;" onclick="add_users("'.$row->email.'")"><small style="background: #c6c6c6;padding: 5px;border-radius: 5px;margin-bottom:2px;cursor: pointer;">'.$row->name.' ( '.$row->email.' )</small></li>';
            }
        }
    }
	public function viewAttendance(Request $request)
    {
        $cidata=$request->session()->get('CIDATA');
        $cidata_ob=json_decode($cidata);
        $company_id=$cidata_ob->cid;
        $records=VisitorHistory::where(['company_id'=>$company_id])->orderBy('id', 'desc')->take(1)->get()->toArray();
        //dd($records);
        $record=[];
        foreach($records as $datas ){
            $all_daat = json_decode($datas['ams_data']);
            //dd($all_daat);die;
            foreach($all_daat as $key => $data ){
				//dd(strtoupper($data->employee_id));
                @$select=ConferenceMeeting::where(['refer_code'=>$data->employee_id,'room_id'=>$request->id])->first();
                if(!empty($select)){
					$data->image=$select->avatar;
					array_push($record,$data);
                }
            }
        }
        return view('admin.meeting.attendance_histories',compact('record'));
    }
}
