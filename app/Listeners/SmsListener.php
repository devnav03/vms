<?php







namespace App\Listeners;







use App\Events\SmsEvent;



use Illuminate\Queue\InteractsWithQueue;



use Illuminate\Contracts\Queue\ShouldQueue;







class SmsListener



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



     * @param  SmsEvent  $event



     * @return void



     */



    public function handle(SmsEvent $event)



    {


       $response = file_get_contents('http://my.logicboxitsolutions.com/api/send_gsm?api_key=476076e5e398b99&text='.urlencode($event->message).'&mobile='.$event->mobile); 
    
       return  $response ;

    }



}



