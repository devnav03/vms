<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Http\Controllers\Controller;

use App\Events\SmsEvent;
use App\Model\Setting;
use DB;
class CronSchedule extends Command

{
  protected $signature = 'cron:schedule {--option=}';
  protected $description = 'Acpl Cron Jobs';
  public function __construct()  {
	parent::__construct();

  }
  public function handle(){
		switch($this->option('option')){
			case 'pv-matching':
			$this->schedulePvMatching();
			break;
			case 'team-promotional':
			$this->scheduleTeamPromotion();
			break;
			case 'update-token':
			$this->updateToken();
			break;
		}
	}

  public function updateToken(){
    $curl = curl_init();
    curl_setopt_array($curl, array(
    CURLOPT_URL => "https://vztor.sspl22.in/superadmin/public/api/tokenUpdate",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "post",
    CURLOPT_HTTPHEADER => array(
    "Authorization: Bearer ".$token->value
      ),
     ));
    $response = curl_exec($curl);
    // $data=json_decode($response);
    DB::table('test')->insert(['name'=>'tfhujk']);

  }

}
