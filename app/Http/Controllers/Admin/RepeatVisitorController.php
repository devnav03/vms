<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Symptom;
use App\Admin;
use App\User;
use App\Model\UserOtp;
use App\Model\Department;
class RepeatVisitorController extends Controller
{
    public function __construct()
  {
    parent::__construct();
  }
    public function index(Request $request){
        $company_data = $this->company_data;
        $start_date = $request->date_from;
        $end_date = $request->date_to;
        $page = 1;
        $all_reports = User::get()->groupBy('mobile');
        
        return view('admin.user.repeat-list',compact('all_reports'));
        

    }
    public function visitorDetails(Request $request,$mobile){
        $company_data = $this->company_data;
        
        $datas = User::where('mobile', $mobile)->with(['OfficerDetail' => function ($query) {
        $query->select('id', 'name');
        }])->orderBy('id', 'desc')->get();

        return view('admin.user.revisitor-details',compact('datas'));
    }

   




}
