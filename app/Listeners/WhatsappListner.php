<?php







namespace App\Listeners;







use App\Events\WhatsappEvent;



use Illuminate\Queue\InteractsWithQueue;



use Illuminate\Contracts\Queue\ShouldQueue;







class WhatsappListner



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



     * @param  WhatsappEvent  $event



     * @return void


     */



    public function handle(WhatsappEvent $event)
    {

		 $res = file_get_contents('http://web.cloudwhatsapp.com/wapp/api/send?apikey=eadb4d77cc1a47b0a515d214d554dec7&mobile='.$event->mobile.'&msg='.urlencode($event->message)); 
		
		return $res;
		


    }



}



