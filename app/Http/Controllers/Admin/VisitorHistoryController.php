<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Transaction;
use Carbon\Carbon;
use App\Model\Department;
use App\User;
use DB;
use App\Model\Location;
use App\Model\Building;
use App\Admin;
class VisitorHistoryController extends Controller
{
    public function index(Request $request){
      $cidata=$request->session()->get('CIDATA');
      $cidata_ob=json_decode($cidata);
      $department=$this->getAllDepartment($request);
      $locations=Location::where($cidata_ob->where,$cidata_ob->cid)->get();
      $buildings=Building::where($cidata_ob->where,$cidata_ob->cid)->get();
      $get_officers = Admin::where(['role_id'=>6,'status_id'=>1,$cidata_ob->where=>$cidata_ob->cid])->get();
      // $visiters=$this->getAllVisiter($request);
        if($request->date_from){

            $start_date = $request->date_from;
            $end_date = $request->date_to;
            $page = 1;
            $all_reports = $this->getVisitordata($request,$cidata_ob);
            $record = $all_reports;

            //dd($record);
            return view('admin.visitor-report.visitor-history',compact('record','department','locations','get_officers','buildings'));
        }
        $record = [];
        return view('admin.visitor-report.visitor-history',compact('record','department','locations','get_officers','buildings'));

    }

    public function Show(Request $request){

            $cidata=$request->session()->get('CIDATA');
            $cidata_ob=json_decode($cidata);
            $department=$this->getAllDepartment($request);
            $locations=Location::where($cidata_ob->where,$cidata_ob->cid)->get();
            $buildings=Building::where($cidata_ob->where,$cidata_ob->cid)->get();
            $get_officers = Admin::where(['role_id'=>6,'status_id'=>1,$cidata_ob->where=>$cidata_ob->cid])->get();
            $start_date = $request->date_from;
            $end_date = $request->date_to;
            $page = 1;
            $all_reports = $this->getVisitordata($request,$cidata_ob);
            $record = $all_reports;
            // echo "<pre>";
            // print_r($record);die;
            return view('admin.visitor-report.visitor-history',compact('record','department','locations','get_officers','buildings'));
    }


    public function getVisitordata($request,$cidata_ob){
      // dd($request->all());
      $start_date = $request->date_from;
      $end_date = $request->date_to;
      $page = 1;
      $department_id = $request->department_id;
      $status = $request->status;
      $location_id = $request->location_id;
      $building_id = $request->building_id;
      $officer_id = $request->officer_id;
      $users=User::where(function ($query) use ($start_date,$end_date,$page,$department_id,$status,$location_id,$building_id,$officer_id) {
        if($location_id!=''){
          $query->where('location_id', $location_id);
        }
        if($building_id!=''){
          $query->where('building_id', $building_id);
        }
        if($officer_id!=''){
          $query->where('officer_id', $officer_id);
        }
        if($department_id!=''){
          $query->where('department_id', $department_id);
        }

        if($status!=''){
          $query->where('status', $status);
        }

        if($start_date!=''){
          $query->whereBetween('created_at', [$start_date, $end_date]);
        }})

      ->with(['OfficerDetail'=>function($query){
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
          }])->where($cidata_ob->where,$cidata_ob->cid)->get()->toArray();
          return $users;
          // print_r($users);
          // dd(DB::getQueryLog());
    }
    public function getAllDepartment($request){
      $cidata=$request->session()->get('CIDATA');
      $cidata_ob=json_decode($cidata);
      return Department::where($cidata_ob->where,$cidata_ob->cid)->get()->toArray();
    }



    public function getAllVisiter($request){
      $cidata=$request->session()->get('CIDATA');
      $cidata_ob=json_decode($cidata);
      return User::where($cidata_ob->where,$cidata_ob->cid)->get()->toArray();
    }




}
