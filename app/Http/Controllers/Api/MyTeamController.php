<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;

class MyTeamController extends Controller
{
    public function myAllDownlines(Request $request){

    	$user_id = $request->user()->id;

    	$total_downlines = Controller::myDwonlines($user_id);

    	if(count($total_downlines['all_downline'])){
    		foreach ($total_downlines['all_downline'] as $downline_id) {
    			$downline_detail = User::select('id', 'first_name', 'last_name', 'mobile', 'refer_code', 'parent_id', 'created_at')->with(['parentDetail'=>function($query){
    				$query->select('id', 'first_name', 'last_name', 'refer_code');
    			}])->where('id', $downline_id)->first();

    			$downlines_detail[] = (object)[
    				// 'user_id'=>$downline_id,
    				'name'=>$downline_detail->first_name.' '.$downline_detail->last_name,
    				'refer_code'=>$downline_detail->refer_code,
    				'parent_name'=>$downline_detail->parentDetail->first_name.' '.$downline_detail->parentDetail->last_name,
                    'parent_refer'=>$downline_detail->parentDetail->refer_code,
    				'active_status'=>$downline_detail->userLevel->name??'NA',
    				'join_date'=>$downline_detail->created_at->toDateString(),
    			];
    		}
    	}else{
    		$downlines_detail = [];
    	}

    	return response()->json(['error'=>false, 'message'=>'My All Downlines', 'data'=>$downlines_detail]); 

    }

    public function myDirectTeam(Request $request){
    	$user_id = $request->user()->id;

    	$my_direct_team = User::select('id', 'parent_id', 'first_name', 'last_name', 'refer_code', 'activation_status', 'created_at')->where('sponsored_by_id', $user_id)->get();

    	return response()->json(['error'=>false, 'message'=>'My Direct Team', 'data'=>$my_direct_team]); 
    }

}


