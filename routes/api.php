<?php



use Illuminate\Http\Request;



/*

|--------------------------------------------------------------------------

| API Routes

|--------------------------------------------------------------------------

|

| Here is where you can register API routes for your application. These

| routes are loaded by the RouteServiceProvider within a group which

| is assigned the "api" middleware group. Enjoy building your API!

|

*/



#Authentication

Route::post('/register-user', 'Auth\RegisterController@registerUser');



Route::any('/user-login', 'Auth\LoginController@userLogin');



Route::post('/resend-otp', 'Auth\ForgotPasswordController@resendOtp');



Route::post('/forgot-password', 'Auth\ForgotPasswordController@forgotPassword');



Route::post('/api-version', 'CommonController@ApiVersion');





Route::group(['middleware'=>['auth:api'], 'prefix'=>'user'], function(){

	Route::post('/add-address', 'UserAddressController@addAddress');
	Route::post('/address-list', 'UserAddressController@addressList');
	Route::post('/change-password', 'ProfileController@changePassword');
	Route::post('/user-logout', 'Auth\LoginController@userLogout');
	Route::post('/update-profile', 'ProfileController@updateProfile');
	Route::post('/user-detail', 'ProfileController@UserDetail');	

});







	// Pramod 
	Route::post('/newVisit', 'VisitorController@newVisit');

	Route::post('/getVisitorSlip', 'VisitorController@getVisitorSlip');
	
	// Done
	Route::post('/my_visit_list', 'VisitorController@know_status');
	Route::post('/generateSlip', 'VisitorController@generateSlip');
	Route::post('/otpSend', 'VisitorController@otpSend');
	Route::post('/otpVerify', 'VisitorController@otpVerify');
	Route::post('/getCountry', 'VisitorController@getCountry');
	Route::post('/getState', 'VisitorController@getState');
	Route::post('/getCity', 'VisitorController@getCity');
	Route::post('/getLocation', 'VisitorController@getLocation');
	Route::post('/getBuilding', 'VisitorController@getBuilding');
	Route::post('/getDepartment', 'VisitorController@getDepartment');
	Route::post('/getOfficer', 'VisitorController@getOfficer');
	Route::post('/getVisitPurpose', 'VisitorController@getVisitPurpose');
	Route::post('/getSymptoms', 'VisitorController@getSymptoms');
	Route::post('/markIn', 'VisitorController@markIn');
	Route::post('/markOut', 'VisitorController@markOut');
	Route::any('/superUserCreate', 'AdminController@superUserCreate');
	Route::any('/gaurdLogin', 'VisitorController@guardLogin');
	Route::any('/get_data_verify', 'VisitorController@preInvitationVerify');
	Route::any('/pre_visit_verify', 'VisitorController@addPreInvite');
	
	
	Route::any('/getCompany', 'ApiController@getCompany');
	Route::any('/staffLogin', 'Auth\StaffLoginController@loginStaff');
	Route::any('/visitor_recoard', 'ApiController@getVisitorRecard');
	Route::any('/over_staiyng', 'ApiController@OverStaiyng');

	
	Route::any('/preinvitation_list', 'ApiController@getPreInviteVisitor');

	Route::any('/add_preinvitation', 'ApiController@preInvitationAdd');

	Route::any('/visitor_detail', 'ApiController@visitorDetails');

	Route::any('/visitor_report', 'ApiController@getVisitorReport');

	Route::any('/blocked_visitor', 'ApiController@blockedVisitor');
	Route::any('/unblockvisitor', 'ApiController@UnblockedVisitor');
	Route::any('/block_visitor_list', 'ApiController@blockVisitorList');
	Route::any('/all_visitor_reports', 'ApiController@allVisitorReports');
	Route::any('/all_in_visitor', 'ApiController@getAllInVisitor');

	Route::any('/send_panic_alert', 'ApiController@sendPanicAlert');
	Route::any('/panic_alerts', 'ApiController@panicAlerts');

	Route::any('/emergency_contact_list', 'ApiController@emergencyList');
	Route::any('/add_emergency_contact', 'ApiController@addEmergencyContact');
	Route::any('/update_emergency_contact', 'ApiController@updateEmergencyContact');


	Route::any('/visitorIn', 'VisitorController@visitorIn');
	Route::any('/visitorOut', 'VisitorController@visitorOut');


	
	Route::any('/visitor_approve', 'VisitorController@visitorApprove');
	Route::any('/visitor_reject', 'VisitorController@visitorApproveReject');
	Route::any('/visitor_rescheduled', 'VisitorController@visitRescheduled');





