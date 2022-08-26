<?php


namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use DB;
use App\Model\Transaction;
use App\User;
use App\Model\Order;
use App\Model\Product;
use App\Model\UserCart;
use Carbon\Carbon;
use View;
use Session;
use Auth;
use URL;
use Illuminate\Http\Request;
use App\Model\Company;
use App\Model\Setting;
// use Carbon;
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public $company_data=[];
    public function __construct()  {
        // echo (Carbon::now());
        $url=str_replace("www.",'',explode('//',URL::current()))[1];
        // print_r(URL::to('/'));
        $this->getCompanyDetails(URL::to('/'));
  }

  public function viewHome(){
    return view('web.home');
  }
  public function reVisit(){
    return view('web.re-visit');
  }
  public function status(){
    return view('web.status');
  }
  public function download(){
    return view('web.visit-slip-download');
  }
  public function reViitRegistration(){
    return view('web.re-visit-registration');
  }

  public function otpVerify(){
    return view('web.otp-verify');
  }

  public function privacyPolicy(){
    return view('web.privacy-policy');
  }

  public function qrScan(){

    	return view('web.qr-scan');
  }

  public function qrScanBack(){
    return view('web.qr-scan_back');
  }

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
        abort(403);
    }
    if($datas->status=="failed"){
      abort(403, $datas->msg);
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
        abort(403);
    }
    if($data->status=="failed"){
      abort(403, $data->msg);
    }else{
      $this->company_data=$data->data;
      View::share('company_data',json_encode($data->data));
    }

  }
	
 
	
	public function packageCheck($url,$purpose,$count){
		 $comp_id= auth('admin')->user()->company_id;
		
		$token=Setting::where(['company_id'=>$comp_id,'name'=>'token'])->first();
		
		$curl = curl_init();
		curl_setopt_array($curl, array(
		  CURLOPT_URL => 'https://vztor.sspl22.in/superadmin/public/api/packageCheck',
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'POST',
		  CURLOPT_POSTFIELDS => array('purpose' => $purpose,'company_id'=>$comp_id,'count'=>$count),
		  CURLOPT_HTTPHEADER => array(
			'Authorization: Bearer '.$token->value
		  ),
		));

		$response = curl_exec($curl);
		
		curl_close($curl);
		return $response;

	}














}
