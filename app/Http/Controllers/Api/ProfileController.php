<?php



namespace App\Http\Controllers\Api;



use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Http\Resources\UserDetailResource;

use Illuminate\Validation\Rule;

use App\User;

use App\Model\UserDetail;

use App\Model\BankList;

use App\Model\UserBank;

use App\Model\UserNomineeDetail;

use App\Model\UserKyc;



class ProfileController extends Controller

{

    public function changePassword(Request $request){

    	$this->validate($request, [

    		'old_password'=>'required',

    		'new_password'=>'required|min:6|max:14',

    	]);



        $profile_detail = UserDetail::select('id', 'plain_password')->where('user_id', $request->user()->id)->first();

    	

    	if($request->old_password != decrypt($profile_detail->plain_password)){

    		return response()->json(['error'=>true, 'message'=>'Incorrect old password']);

    	}



    	$user_detail = User::select('id', 'password')->where('id', $request->user()->id)->first();

    	$user_detail->password = bcrypt($request->new_password);

		$profile_detail->plain_password = encrypt($request->new_password);



		if($user_detail->save()){

            $profile_detail->save();

			return response()->json(['error'=>false, 'message'=>'Your password has been changed successfully, Thankyou!']);

		}

		return response()->json(['error'=>true, 'message'=>'Oops! something went wrong!']);

    }


    public function updateProfile(Request $request){

        $this->validate($request, [

            'pin_code'=>'required|digits:6',

            'state'=>'required|max:100',

            'city'=>'required|max:100',

            'address'=>'required|max:200',

            'image'=>'nullable',

        ]);



        $user_detail = UserDetail::select('id', 'image', 'address', 'state_name', 'city_name', 'pin_code')->firstOrNew(['user_id'=>$request->user()->id]);

        $user_detail->pin_code = $request->pin_code;

        $user_detail->state_name = $request->state;

        $user_detail->city_name = $request->city;

        $user_detail->address = $request->address;

        $user_detail->save();



        return response()->json(['error'=>false, 'message'=>'Profile Successfully updated']);

    }



    public function UserDetail(Request $request){



        $user = User::select('id', 'first_name', 'group_id', 'last_name', 'refer_code', 'mobile', 'email', 'current_level')->with(['userDetail'=>function($query){

            $query->select('user_id', 'zoom_user_name', 'zoom_user_password', 'state_name', 'city_name', 'pin_code', 'address');

        }, 'nomineeDetail'=>function($query){

            $query->select('user_id', 'nominee_name', 'relation', 'email', 'mobile');

        }, 'walletDetail'=>function($query){

            $query->select('user_id', 'amount');

        },  'DmtWalletDetail'=>function($query){

            $query->select('user_id', 'amount');

        },'outstandingBalanceDetail'=>function($query){

            $query->select('to_user_id', 'transferred_amount', 'status')->where('status', 1);

        }, 'userLevel'=>function($query){

            $query->select('id', 'name');

        }])->where('id', $request->user()->id)->first();



        $data = new UserDetailResource($user);



        return response()->json(['error'=>false, 'message'=>'user_detail', 'data'=>$data]);

    }



    public function addBankDetail(Request $request){

        $this->validate($request, [

            'ifsc_code'=>'required|max:15',

            'account_number'=>'required|numeric|max:25',

            'bank_name'=>'required',

            'account_name'=>'required|max:100',

        ]);



        $user_id = $request->user()->id;

        if(UserBank::select('user_id')->where('user_id', $user_id)->exists()){

            return response()->json(['error'=>true, 'message'=>'You have already added bank!']);

        }



        $bank = BankList::firstOrNew(['name'=>$request->bank_name]);

        $bank->save();



        $bank_detail = new UserBank();

        $bank_detail->user_id = $user_id;

        $bank_detail->ifsc_code = $request->ifsc_code;

        $bank_detail->bank_id = $bank->id;

        $bank_detail->account_name = $request->account_name;

        $bank_detail->account_number = $request->account_number;

        if($bank_detail->save()){

            return response()->json(['error'=>false, 'message'=>'Bank Successfully updated']);

        }else{

            return response()->json(['error'=>tru, 'message'=>'something went wrong']);

        }

    }



    public function addNominee(Request $request){

        $this->validate($request, [

            'nominee_name'=>'required|max:25',

            'relation'=>'required|numeric|digits:1',

            'email'=>'nullable|email|max:50',

            'mobile'=>'required|numeric|digits:10',

        ]);



        $user_id = $request->user()->id;

        // if(UserNomineeDetail::select('user_id')->where('user_id', $user_id)->exists()){

        //     return response()->json(['error'=>true, 'message'=>'You have already updated your nominee!']);

        // }



        $nominee = UserNomineeDetail::firstOrNew(['user_id'=>$user_id]);

        $nominee->user_id = $user_id;

        $nominee->nominee_name = $request->nominee_name;

        $nominee->relation = $request->relation;

        $nominee->email = $request->email;

        $nominee->mobile = $request->mobile;

        if($nominee->save()){

            return response()->json(['error'=>false, 'message'=>'Nominee Successfully updated']);

        }else{

            return response()->json(['error'=>tru, 'message'=>'something went wrong']);

        }

    }



    public function updateKyc(Request $request){

        $this->validate($request, [

            'pan_photo'=>'required|file|max:5000',

            'id_front'=>'required|file|max:5000',

            'id_back'=>'required|file|max:5000',

            'pan_number'=>'required',

            'id_type'=>'required|numeric',

        ]);



        $user_id = $request->user()->id;



        $kyc = UserKyc::firstOrNew(['user_id'=>$user_id]);

        $kyc->pan_number = $request->pan_number;

        $kyc->id_type = $request->id_type;

        $kyc->pan_photo = $request->file('pan_photo')->store('kyc/'.$user_id);

        $kyc->id_front = $request->file('id_front')->store('kyc/'.$user_id);

        $kyc->id_back = $request->file('id_back')->store('kyc/'.$user_id);

        $kyc->status = 0; //processed, 1=>Approved, 3=>Rejected

        $kyc->save();

        return response()->json(['error'=>false, 'message'=>'Your KYC will be verified by admin in few days Thank you!']);

    }

}





