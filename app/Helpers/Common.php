<?php // Code within app\Helpers\Helper.php























namespace App\Helpers;


use Appnings\Payment\Facades\Payment;























class Common











{ 











    public static function ifscCheck($ifsc_code){





        $header = stream_context_create([





                'http' => [





                  'header' => 'DY-X-Authorization: 7681df1857dac2e01af8fc8e79723c8bf5c1c22d', 





                ]





              ]);











          $response = file_get_contents('https://ifsc.datayuge.com/api/v1/'.$ifsc_code.'', false, $header);





        return json_decode($response, true);





    }

















    public static function checkPinCode($pin_code){











        $data = file_get_contents('http://postalpincode.in/api/pincode/'.$pin_code.'');





        return $data;











       $url = 'https://pincode.saratchandra.in/api/pincode/'.$pin_code.'';











       $curl = curl_init();





       curl_setopt($curl, CURLOPT_URL, $url);





       





       curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);





       curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);











       // EXECUTE:





       $response = curl_exec($curl);





       if(!$response){





        die("Connection Failure");





       }





       curl_close($curl);





       return json_decode($response, true);





    }











    public static function getCompanyBalance($data, $header=false){


    





      $url = "https://www.acplmart.com/index.php?route=api/custom/check-wallet-amount-for-acpl";





      // if($data['amount_type'] == 'money_transfer' || ){


      //   $url = "https://www.acplmart.com/index.php?route=api/custom/check-wallet-amount-for-acpl";


      // }








       $curl = curl_init();











       curl_setopt($curl, CURLOPT_POST, 1);





        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);











       // OPTIONS:











       curl_setopt($curl, CURLOPT_URL, $url);

















       











       curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);











       curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);























       // EXECUTE:











       $result = curl_exec($curl);











       if(!$result){die("Connection Failure");}











       curl_close($curl);











       // return json_decode($result);

















        return json_decode($result);





   }





    public static function updateCompanyWallet($data, $type){





      $url = "https://www.acplmart.com/index.php?route=api/custom/update-company-wallet";


      $data['transaction_type'] = $type;





      // dd($data);





      $curl = curl_init();











       curl_setopt($curl, CURLOPT_POST, 1);





        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);











       // OPTIONS:











       curl_setopt($curl, CURLOPT_URL, $url);

















       











       curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);











       curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);























       // EXECUTE:











       $result = curl_exec($curl);











       if(!$result){die("Connection Failure");}











       curl_close($curl);











       // return json_decode($result);

















        return json_decode($result);


   }








    public static function ccavenuePay($user_detail, $transaction_id, $amount, $data, $booking_type){





      if($booking_type == 'bus'){


        $redirect_url = '/bus/ccavenue-payment-response';


      }elseif($booking_type == 'flight'){


        $redirect_url = '/flight/ccavenue-payment-flight-response';


      }elseif($booking_type == 'shopping'){


      $redirect_url = '/user/ccavenue-pay-shopping-response';


      }





      $parameters = [


          // 'tid' => '123322122',


          'order_id' => $transaction_id,


          'amount' => $amount,


          'billing_name'=>$user_detail['first_name'].' '.$user_detail['last_name'],


          'billing_address'=>$user_detail->userDetail()->address??'Plot No. 6, Sector 14, Kaushambi,',


          'billing_city'=>'New Delhi',


          'billing_state'=>'Delhi',


          'billing_zip'=>'110063',


          'billing_country'=>'India',


          'billing_tel'=>$user_detail['mobile'],


          'billing_email'=>$user_detail['email'],


          'merchant_param4'=>json_encode($data),


        ];





        config(['payment.remove_csrf_check'=>$redirect_url, 'payment.ccavenue.redirectUrl'=>$redirect_url, 'payment.ccavenue.cancelUrl'=>$redirect_url]);





        $order = Payment::prepare($parameters);


        return Payment::process($order);


   }











   public static function ccavenuePayFranchisee($user_detail, $transaction_id, $amount, $data, $booking_type){








      if($booking_type == 'bus'){


        $redirect_url = '/franchisee/bus/ccavenue-payment-response';


      }elseif($booking_type == 'flight'){


        $redirect_url = '/franchisee/flight/ccavenue-payment-flight-response';


      }elseif($booking_type == 'shopping'){


      // $redirect_url = '/user/ccavenue-pay-shopping-response';


      }





      $parameters = [


          // 'tid' => '123322122',


          'order_id' => $transaction_id,


          'amount' => $amount,


          'billing_name'=>$user_detail['name'],


          'billing_address'=>'Plot No. 6, Sector 14, Kaushambi,',


          'billing_city'=>'New Delhi',


          'billing_state'=>'Delhi',


          'billing_zip'=>'110063',


          'billing_country'=>'India',


          'billing_tel'=>$user_detail['mobile'],


          'billing_email'=>'',


          'merchant_param4'=>json_encode($data),


        ];





        // dd($parameters);


        config(['payment.remove_csrf_check'=>$redirect_url, 'payment.ccavenue.redirectUrl'=>$redirect_url, 'payment.ccavenue.cancelUrl'=>$redirect_url]);





        $order = Payment::prepare($parameters);


        return Payment::process($order);


   }


   








}





























