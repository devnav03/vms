<?php // Code within app\Helpers\Helper.php

namespace App\Helpers;

class pay2AllApi{

	public static function apiToken(){
    	return $token = [
    		'api_token' => 'djR0ytiUl5vQeXHb5phM9sEnwSW7ZtNttidJHRiRXXv8A8MSoxur1ik58pLm',
    	];
	}
	public static function callApi($method, $url, $data,$header=false){
		$curl = curl_init();
		switch ($method){
			case "POST":
				curl_setopt($curl, CURLOPT_POST, 1);
				if ($data)
					curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
				break;
			case "PUT":
				curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
				if ($data)
					curl_setopt($curl, CURLOPT_POSTFIELDS, $data);			 					
					break;
			default:
				if ($data)
					$url = sprintf("%s?%s", $url, http_build_query($data));
		}
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
	           'Accept:application/json',
	           'User-Agent: Mozilla',
	           'Cookie: troute=t1;',
	        ));
	   // OPTIONS:
	   curl_setopt($curl, CURLOPT_URL, $url);
	   if ($header==true){

	   		$api_token = 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjNmZWM2YTI1ZjJjMTNiMTRhMWYxZWYwMjdkNWEyYTJiMWExNThiYTg2NzgzNGViYjM1OTAyMjJhZTc4NTcyODc4MTI3NzAwMzkwOWM0OTM0In0.eyJhdWQiOiIxIiwianRpIjoiM2ZlYzZhMjVmMmMxM2IxNGExZjFlZjAyN2Q1YTJhMmIxYTE1OGJhODY3ODM0ZWJiMzU5MDIyMmFlNzg1NzI4NzgxMjc3MDAzOTA5YzQ5MzQiLCJpYXQiOjE1ODk4ODMwOTEsIm5iZiI6MTU4OTg4MzA5MSwiZXhwIjoxNjIxNDE5MDkxLCJzdWIiOiIyMTUiLCJzY29wZXMiOltdfQ.iEx3mF34N4eikJcCfZJ69o9l6-yQrnEo1JZJHeoilHVabvMthu6HabCmK4Qypc4d3wn3iCSq2bYwMlF_uRVyUHbtpBkDAMgOBjWt4zWY4sai3Gv79pnUBHB4-gH7FHGjc0hhWBkVje9v1wbGoRPbxRxTcgU3XEGBgVFFxL2LrlG9drGXHjIcKG-wWcU84_-ZPaXIk2a1789eQv69dAx9HdepKz-3Mqtfd_GtBbDrS-capnsWkga1ivYY5mA5SUc2uSLcrXRcFTN3j2PSVLXdJtEAn4UYy3dJ6sbPWtApjlg81rUKnA98WzlSrhavTnpI3BzjDX6d41AtkR_HqijNtrMHrlfSvGeOyCGBBJzrATMVkPP5fHP0pJXgqjONWzoFHyRdGdFQDeH00L7GfzOhFHtwnyQNpK93-lTO_MGVitkFFBUSp4_SCDc98wiI0kbUGk41caNLe8xiH64IBVGtACbhT-zlaldCqOUq9eArcnDWsNAlIvgpdGSJ7rmQEtMcmFlvt15dFzpQilW8jUkTPSa06umSx4lWw_chWPJER_8SYKTBWhaY_kPuztFGKtcSErvSzHw6pYgNEx-jtrWqyRuAeHZLDVNVEPW3NSfl9xnTSescEQETwl1lMIEPBN1ouwv8Mc7OxOUuC5vScSHdJkAkt80GGX5ISxJzoNtRieU';
			

	        curl_setopt($curl, CURLOPT_HTTPHEADER, array(

				'Accept:application/json',
				'Authorization:'.$api_token.'',
			));
		}

		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

		curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		// EXECUTE:
		$result = curl_exec($curl);

		if(!$result){die("Connection Failure");}
		curl_close($curl);
		return json_decode($result);
	}

	public static function rechargeApi($method, $url, $data,$header=false){

	   $curl = curl_init();

	   switch ($method){

	      case "POST":
	         curl_setopt($curl, CURLOPT_POST, 1);

	         if ($data)

	            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

	         break;

	      case "PUT":

	         curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");

	         if ($data)
	            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

	         break;

	      default:
	         if ($data)
	            $url = sprintf("%s?%s", $url, http_build_query($data));

	   }

        curl_setopt($curl, CURLOPT_HTTPHEADER, array(

	           'Accept:application/json',

	           'User-Agent: Mozilla',

	           'Cookie: troute=t1;',

	        ));

	   curl_setopt($curl, CURLOPT_URL, $url);



	   if ($header==true){
               $api_token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjE5NDFhNzdjNDE0MmQ3MWU4OGI2MTFmY2Q5YjUyNjA2Yzc1NTQxZmUzYmY1NDQwNGEzNmI3ZTNlNjJjOGU3MDhhODEwODY0YmFkMjJhYjIxIn0.eyJhdWQiOiI1IiwianRpIjoiMTk0MWE3N2M0MTQyZDcxZTg4YjYxMWZjZDliNTI2MDZjNzU1NDFmZTNiZjU0NDA0YTM2YjdlM2U2MmM4ZTcwOGE4MTA4NjRiYWQyMmFiMjEiLCJpYXQiOjE1NDk5NTYyNTIsIm5iZiI6MTU0OTk1NjI1MiwiZXhwIjoxNTgxNDkyMjUyLCJzdWIiOiIxNjk4MiIsInNjb3BlcyI6W119.KPe4B_lm_qOgPk2SPrC8cyag7R6T5y7tGgj95n2ZExkzJdcvBvbVb7P_IhWMWLTADV_l--PYRil003sWCeIZ2j8pL0JRdPNdo6L5pMuvlY2rBJzmKazhajOXhd_9mhsoVxlBfeIzGgfptI_Cbm9bN8EAEYXRzACSAS3dW-eZthlktxJCy0tpktygVWO7VRJgj326awqQqJUoqEiNDouV4K7I7hZ1pFZIhnrvrJoe9Lwr0SNlcaoI5eVjy1Rb4FWlv3uXAUWE2QIHRHkGwa8Y4IieUkw3qhzJEoqFjpQ-ETf1OceFpdv_rjeCIWEw5P5aX08Nj4GXHWDJkutX0khHgtDt1l2w7L5kYH20SM7vgli8gwmJ3g9-iq532_7Uux_4KOG7ohR0BxfLRCLuZnMJfbhUlcnkejpFJTHJsvUrWG0ylyCTr3WBVAM85QmFDkwAwvOsbwd2tHgZUN3t_NBNB5BbBcfvbVJk884s31I0-A8m3uE2xrQSAsr0bF15nQXu_NFpPevOEvWeuXEO16MZoPIp_0MKcea5tvfaoaG3NW0Nw5MXh_knBqgv2spwpFHrKAsfeyAxnmTBoGshMkLgbzNaB3_BM_QuD9hmHb2LFDRkOLdXam0Vs7Pz-MC3eN7ymPNjNcfkfIgufGXULkNOTo6ymB6cD0QJe6DzrNvC8zo';
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
               'Accept:application/json',
               'Authorization:Bearer '.$api_token,
            ));
       }   

	   curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

	   curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

	   $result = curl_exec($curl);

	   if(!$result){die("Connection Failure");}

	   curl_close($curl);

	   return json_decode($result);

	}

}