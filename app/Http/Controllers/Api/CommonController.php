<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\ApiVersion;
use App\User;
use App\Franchisee;

class CommonController extends Controller
{
   public function ApiVersion(Request $request){

      $data = ApiVersion::select('version', 'status', 'created_at')->where('status', 1)->first();
      
      return response()->json(['error'=>false, 'message'=>'Current Version', 'data'=>$data]);
   }

   public function referDetail(Request $request){
        $this->validate($request, [
            'refer_code'=>'required'
        ]);
        if(strlen($request->refer_code) > 4){
            $user = User::select('first_name', 'last_name', 'activation_status')->where('refer_code', $request->refer_code)->first();
            if($user){
                if($user->activation_status == 1){
                  return response()->json(['error'=>true, 'message'=>'Used referal is not active.']);  
                }
                return response()->json(['error'=>false, 'message'=>'Refer Code Detail', 'data'=>$user->first_name.' '.$user->last_name]);
            }
            return response()->json(['error'=>true, 'errors'=>['refer_code'=>['No Record Found']]], 422);
        }
    }

    public function franchiseeList(Request $request){

      $franchisee = Franchisee::select('id', 'name')->get();

      return response()->json(['error'=>false, 'message'=>'Franchisee List', 'data'=>$franchisee]);
    }
}
