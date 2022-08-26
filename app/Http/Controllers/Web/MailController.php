<?php

namespace App\Http\Controllers\Web;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Mail;





class MailController extends Controller {

   public function html_email(){

   		  Mail::send(['text'=>'mail'],['name','suresh kumar'],function($message){


          $message->to('vztor.in@gmail.com','suresh kumar')->subject('test email');


          $message->from('vztor.in@gmail.com','testing from me');


         });







      echo "HTML Email Sent. Check your inbox.";





   }





}





