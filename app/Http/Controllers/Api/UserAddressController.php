<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\UserAddress;

class UserAddressController extends Controller
{
    public function addAddress(Request $request){
    	$this->validate($request, [
            'name'=>'required|regex:/^[a-zA-Z ]+$/u|max:100',
            'mobile'=>'required|numeric|digits:10',
            'pin_code'=>'required|digits:6|max:6',
            'state'=>'required|max:100',
            'city'=>'required|max:100',
            'address'=>'required|max:150',
        ]);
            $user_id = $request->user()->id;

    		$address = new UserAddress();
            $address->user_id = $user_id;
            $address->name = $request->name;
            $address->mobile = $request->mobile;
            $address->pin_code = $request->pin_code;
            $address->state_name = $request->state;
            $address->city_name = $request->city;
            $address->address = $request->address;
            if($address->save()){
                return response()->json(['error'=>false, 'message'=>'address saved successfully']);
            }
            return response()->json(['error'=>true, 'message'=>'oops! Something went wrong']);
    }

    public function addressList(Request $request){
    	
        $user_id = $request->user()->id;
        
		$user_address = UserAddress::where('user_id', $user_id)->get();

        if(count($user_address)){
            return response()->json(['error'=>false, 'message'=>'Address List', 'data'=>$user_address]);
        }
        return response()->json(['error'=>false, 'message'=>'No record Found', 'data'=>[]]);
    }
}
