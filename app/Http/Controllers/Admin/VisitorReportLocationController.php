<?php
namespace App\Http\Controllers\Admin;
use DB;
use App\User;
use App\Admin;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Events\SmsEvent;
use App\Model\Location;
use App\Events\OtpEvent;
use App\Model\Setting;
class VisitorReportLocationController extends Controller
{

  public function index(Request $request){
    $cidata=$request->session()->get('CIDATA');
    $cidata_ob=json_decode($cidata);
    $datas=Location::with(['building'=>function($query){
      $query->select('id','name','location_id')->with(['department'=>function($query){
        $query->select('id','name','building_id')->with(['user'=>function($query){
          $query->select('id','name','department_id')->whereDate('visite_time', Carbon::today());
        }]);
      }]);
    }])->where($cidata_ob->where,$cidata_ob->cid)
    // ->groupby('location_id')
    ->get()->toArray();
    // echo "<pre>";
    // print_r($data);
    // die;
    return view('/admin.visitor-report.visitor-report-location',compact('datas'));
  }






}
