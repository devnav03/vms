<?php

use Illuminate\Http\Request;

/*

|--------------------------------------------------------------------------

| Web Routes

|--------------------------------------------------------------------------

|

| Here is where you can register web routes for your application. These

| routes are loaded by the RouteServiceProvider within a group which

| contains the "web" middleware group. Now create something great!

|

*/


Route::get('/reset', function (){
    Artisan::call('route:clear');
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('config:cache');
});
Route::get('/pre-invitations/join/{email?}/{id?}', 'VisitorController@invitationJoin');

Route::get('/sendtofacechk/{id?}', 'VisitorController@sendFaceCheck');

Route::get('/pre-invitations/verify-details/{email?}/{id?}', 'VisitorController@invitationVerify');

Route::get('/', 'VisitorController@viewHome')->name('web.home');
Route::get('/re-visit', 'VisitorController@reVisit')->name('web.re-visit');
Route::get('/status', 'VisitorController@status')->name('web.status');
Route::get('/download', 'VisitorController@download')->name('web.download');
Route::get('/re-visit-registration', 'VisitorController@reViitRegistration')->name('web.re-visit-registration');
Route::get('/otp-verify', 'VisitorController@otpVerify')->name('web.otp-verify');
Route::get('/privacy-policy', 'VisitorController@privacyPolicy')->name('web.privacy-policy');
Route::get('/qr-scan', 'VisitorController@qrScan')->name('web.qr-code');
Route::get('/qr-scan_back', 'VisitorController@qrScanBack')->name('web.qr-code_back');
Route::get('/guard/login', 'VisitorController@gaurdLogin')->name('web.gaurd.login');
Route::POST('/guard/validate', 'VisitorController@gaurdValidate')->name('web.gaurd.validate');
Route::get('/guard/dashboard', 'VisitorController@gaurdDashboard');
Route::get('/guard/visitor/in/{id}', 'VisitorController@visitorIn')->name('web.visitor.in');
Route::get('/guard/visitor/out/{id}', 'VisitorController@visitorOut')->name('web.visitor.out');
Route::get('/guard/logout', 'VisitorController@gaurdLogout')->name('web.gaurd.logout');
Route::get('/visitor/registration/building/{building}', 'VisitorController@viewVisitorForm');
Route::post('/visitot/registration/building/store', 'VisitorController@viewVisitorStore');


Route::post('/checkMobileDetails', 'VisitorController@checkMobileDetails')->name('web.get.mobiledetails');
Route::post('/checkAdharDetails', 'VisitorController@checkAdharDetails')->name('web.get.adhardetails');
Route::post('/checkemailDetails', 'VisitorController@checkemailDetails')->name('web.get.emaildetails');
Route::post('/post-query', 'GrievanceQueryController@postQuery')->name('web.post-query');

Route::get('/send','MailController@html_email');


Route::get('/sendmail','VisitorController@sendEmailtesting');

Route::get('/self-registration', 'VisitorController@create')->name('web.self-registration');

#----------Start MeetingController-----------#
Route::get('/registration-conference-meeting', 'MeetingController@create')->name('web.conference-meeting');
Route::post('/registration-conference', 'MeetingController@conferenceMeetingStore')->name('add.conference.registration');
Route::any('/registration-conference-successfully', 'MeetingController@conferenceMeetingSuccessfully')->name('conferenceMeetingSuccessfully');
Route::any('/meeting-reminder', 'MeetingController@meetingReminder');
#----------End MeetingController-----------#

Route::any('/vms-management-type/{id}', 'MeetingController@vms_management_type')->name('vms-management-type');

Route::post('/self-registration', 'VisitorController@addVisitor')->name('add.self.registration');
Route::any('/profile-image', 'VisitorController@profile_image')->name('profile-image');
Route::any('/information-registration', 'VisitorController@information_registration')->name('information-registration');
Route::any('/business-information', 'VisitorController@business_information')->name('business-information');
Route::any('/identity-proof', 'VisitorController@identity_proof')->name('identity-proof');
Route::any('/identity-confirmation', 'VisitorController@identity_confirmation')->name('identity-confirmation');
Route::any('/whom-to-meet', 'VisitorController@whom_to_meet')->name('whom-to-meet');
Route::any('/purpose-of-visit', 'VisitorController@purpose_of_visit')->name('purpose-of-visit');
Route::any('/meeting-time', 'VisitorController@meeting_time')->name('meeting-time');
Route::get('otp-sent', 'VisitorController@otp_sent')->name('otp-sent');
Route::get('otp-match', 'VisitorController@otp_match')->name('otp-match');
Route::get('resend-otp', 'VisitorController@resend_otp')->name('resend-otp');
Route::get('getState', 'VisitorController@getState_list')->name('getState');
Route::get('getCity', 'VisitorController@getCity_list')->name('getCity');


Route::post('/self-registration-successfully', 'VisitorController@addVisitorAfter')->name('add.self.registration.success');

Route::get('/dashboard', 'DashboardController@dashboardData')->name('user.dashboard');

Route::post('/revisit-check', 'VisitorController@checkVisitor')->name('web.check.visitor');

Route::get('/revisit-check', 'VisitorController@checkVisitor')->name('web.check.visitor');


Route::post('/previsit-check', 'VisitorController@checkPreVisitor')->name('web.check.previsitor');

Route::get('/previsit-check', 'VisitorController@checkPreVisitor')->name('web.check.previsitor');

Route::post('/re-visit-registration', 'VisitorController@addReVisit')->name('add.re-visit-registration');

Route::post('/verified-preinvite', 'VisitorController@addPreInvite')->name('verified.preinvite.submit');
Route::post('/pre-visit-registration', 'VisitorController@addPreVisit')->name('add.pre-visit-registration');

Route::post('/status-check', 'VisitorController@checkStatus')->name('web.check.status');

Route::post('/slip-download', 'VisitorController@slipDownload')->name('web.slip.download');

Route::get('/visitor-approve/{id?}/{officer_id?}', 'VisitorController@visitorApprovePage')->name('visitor.approve');

Route::get('/visitor-approve-success/{id?}/{officer_id?}', 'VisitorController@visitorApprove')->name('visitor.approve.success');
Route::get('/visitor-approve-reject/{id?}/{officer_id?}', 'VisitorController@visitorApproveReject')->name('visitor.reject');

Route::get('/officer-panic-alert/{id?}/{officer_id?}', 'VisitorController@sendPanicAlertPage')->name('visitor.panic.alert.page');
Route::get('/officer-panic-alert-send/{id?}/{officer_id?}', 'VisitorController@sendPanicAlert')->name('visitor.panic.alert');



Route::post('/get-department', 'VisitorController@getOfficer')->name('web.get.officer');
Route::post('/get-device', 'VisitorController@deviceDepartments')->name('web.get.device');



Route::post('/get-state', 'VisitorController@getState')->name('web.get.state');
Route::post('/get-city', 'VisitorController@getCity')->name('web.get.city');

Route::get('/generate-slip/{id?}', 'VisitorController@generateSlip')->name('generate.slip');
Route::get('/generate-slips/{id?}', 'VisitorController@generateSlipBase64')->name('generate.slips');
Route::get('/generate-print/{id?}', 'VisitorController@PrintgenerateSlip')->name('generate.slip.small');

Route::post('web-get-department', 'DepartmentController@getDepartmentBybuildingId')->name('web-get-department');



Route::post('web-get-device', 'ManageDeviceController@getDeviceByDepartmentId')->name('web-get-device');
Route::post('web-get-device-front', 'ManageDeviceController@getDeviceByDepartmentId_front')->name('web-get-device-front');
Route::post('web-get-designation', 'ManageDeviceController@getdesignationByRoom')->name('web-get-designation');

Route::post('web-get-designation-checkbox', 'ManageDeviceController@getdesignationByRoom_checkbox')->name('web-get-designation-checkbox');



Route::any('reg/{id}', 'MeetingController@reg_link')->name('reg');

Route::post('web-get-building', 'BuildingController@getBuilding')->name('web.get.building');
Route::post('web-get-building-front', 'BuildingController@getBuilding_front')->name('web.get.building.front');
Route::post('web-get-location', 'LocationController@getLocation')->name('web.get.location');

Route::get('admin-employee-destroy/{id}', 'DepartmentController@deleteEmployee');
Route::get('admin-guard-destroy/{id}', 'DepartmentController@admin_guard_destroy');





