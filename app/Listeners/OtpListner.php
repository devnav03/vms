<?php



namespace App\Listeners;



use App\Events\OtpEvent;

use Illuminate\Queue\InteractsWithQueue;

use Illuminate\Contracts\Queue\ShouldQueue;

use App\Model\UserOtp;



class OtpListner

{

    /**

     * Create the event listener.

     *

     * @return void

     */

    public function __construct()

    {

        //

    }



    /**

     * Handle the event.

     *

     * @param  OtpEvent  $event

     * @return void

     */

     public function handle(OtpEvent $event)



     {





         //$message = $event->message.' msg no.'.rand(9999,1000);
        //  $otp = 'Dear User, Your One Time Password is '.rand(9999,1000).' Thanks VZTOR';

          // $response = file_get_contents('http://my.logicboxitsolutions.com/api/send_gsm?api_key=476076e5e398b99&text='.urlencode($message).'&mobile='.$event->mobile);

          //$url = 'https://my.logicboxitsolutions.com/api/send_gsm?api_key=476076e5e398b99&text='.urlencode($message).'&mobile='.$event->mobile;
		$url='http://43.252.88.230/index.php/smsapi/httpapi/?secret=TMIzJJzl9PWP6ClWtJPU&sender=OTPSPL&tempid=1207161943113739364&receiver='.$event->mobile.'&route=TA&msgtype=1&sms=Dear%20User,%20Your%20One%20Time%20Password%20is%20'.$event->message.'%20Thanks%20VZTOR';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_USERAGENT,"Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
		curl_close($ch);
		
       
        return json_decode($result);



     }



}

