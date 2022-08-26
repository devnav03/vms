<?php



Route::get('/', function() {



    return redirect()->route('admin.login.form');



});

Route::get('/pre-invitations/join/{email}', 'PreInvitationController@invitationJoin');

Route::any('input-field/{id}', 'RegisterInputController@input_field')->name('input-field');

Route::group(['middleware' => 'admin.guest'], function() {



    Route::get('login', 'Auth\LoginController@showLoginForm')->name('admin.login.form');



    Route::post('login', 'Auth\LoginController@login')->name('admin.login.post');


	Route::post('two/factor', 'Auth\LoginController@twoFactorVerify')->name('admin.login.otp.verify');

});

Route::post('send-sos-alert', 'DashboardController@sendSosMessage')->name('admin.send.sos.alert');
Route::get('admins/bulk_insert', 'EmployeeController@bulk_insert')->name('admin.admins.bulk_insert');
Route::get('admins/bulk_insert_master', 'EmployeeController@bulk_insert_master')->name('admin.locations.bulk_insert_master');

Route::get('admins/sampleDownload', 'EmployeeController@sampleCsvDownload')->name('admin.admins.sampleDownload');
Route::post('admins/bulk_store', 'EmployeeController@employeeStore')->name('admin.admins.bulk_store');

Route::get('admins/sampleDownloadMaster', 'EmployeeController@sampleCsvDownload_master')->name('admin.admins.sampleDownloadMaster');
Route::post('admins/bulk_store_master', 'EmployeeController@employeeStore_master')->name('admin.admins.bulk_store_master');


Route::post('users/update', 'UserController@update')->name('change.status');
Route::get('send-panic/{id?}', 'UserController@panicAleart')->name('admin.users.panicAleart');

Route::get('revisit', 'DashboardController@reVisite')->name('user-revisit');
Route::get('check-revisit', 'DashboardController@checkVisit')->name('check.visit');
Route::get('add-check-revisit/{id?}', 'DashboardController@reVisitform')->name('add-revisit');

Route::post('add-revisit', 'DashboardController@addRevisit')->name('add-revisit-add');


Route::post('all-report', 'VisitorReportController@Show')->name('show.report');
Route::get('visitor_report_sync', 'VisitorReportController@reportSync');
Route::get('remove_visitor_entery', 'VisitorReportController@remove_visitor_entery');



Route::any('get-attendance-histories/{id?}', 'ManageMeetingController@viewAttendance');

//Route::any('manage-meetings-filters', 'ManageMeetingController@manage_meetings_filter')->name('manage-meetings-filter.create');



Route::get('deleteUserFromAmsToday', 'VisitorReportController@deleteUserFromAmsToday');

Route::any('all-history', 'VisitorHistoryController@Show')->name('show.history');

Route::get('product-inventories', 'ProductInventoryController@create')->name('admin.product-inventories.create');

Route::post('product-inventories-store', 'ProductInventoryController@store')->name('admin.product-inventories.store');

Route::get('product-inventories-edit/{invt_id?}', 'ProductInventoryController@edit')->name('admin.product-inventories.edit');

Route::put('product-inventories-update/{invt_id?}', 'ProductInventoryController@update')->name('admin.product-inventories-edit.update');



Route::get('getDesignation', 'DesignationController@getDesignation')->name('getDesignation');
Route::get('designation-departments/{id}', 'DesignationController@getDepartments_Designation')->name('designation-departments');

   Route::post('dropzone', 'ProductController@dropzone')->name('dropzone');


Route::group(['middleware' => 'admin', 'as' =>'admin.'], function(){
    


	Route::get('logout', 'Auth\LoginController@logout')->name('logout.get');
	Route::get('visitorDetails/{mobile}', 'RepeatVisitorController@visitorDetails')->name('repeat.visitor.details');

	




    Route::post('logout', 'Auth\LoginController@logout')->name('logout.post');



	Route::any('dashboard', 'DashboardController@index')->name('dashboard.index');



	Route::get('profile', 'ProfileController@index')->name('profile.show');



    // Route::post('image-editor', 'ImageController@editor')->name('image-editor.store');



    Route::put('change-password', 'ProfileController@update')->name('password.update');
    
    Route::any('meeting-reschedule/{id}', 'ManageMeetingController@meeting_reschedule')->name('meeting-reschedule');
    Route::any('save-meeting-reschedule', 'ManageMeetingController@save_meeting_reschedule')->name('meeting-reschedule.store');
	Route::any('remove-guest/{id}', 'ManageMeetingController@remove_guest')->name('remove-guest');
	


	Route::resource('/breads', 'BreadController');
    Route::get('/manage-meetings/get-user', 'ManageMeetingController@AjaxGetUsers')->name('ajax-get-users');
    //Route::get('/manage-meetings/get-user', 'ManageMeetingController@AjaxStoreUsers')->name('ajax-add-users');


	foreach (App\Model\Menu::whereNotNull('controller')->get() as $menu){



            Route::resource(str_slug(strtolower($menu->table_name)), $menu->controller);



            Route::patch(str_slug(strtolower($menu->table_name)),$menu->controller.'@index')->name($menu->table_name.'.index');



    }







    Route::get('user-login/{id}', 'UserController@userLogin')->name('user-login');



    Route::get('pre-invitations/{id}/re-invite', 'PreInvitationController@reInvite')->name('re-invite');



    Route::post('get-franchise-depo', 'FranchiseeMasterController@getDepoList')->name('get-franchise-depo');

    Route::get('/admin-employee-destroy/{id}', 'AdminController@trt')->name('admin.employee.destroy');

    Route::get('/visitor-report', 'VisitorReportController@visitorReport');

    Route::any('visitor-report/serach', 'VisitorReportController@visitorReportSearch')->name('visitor.search');
    Route::any('visitor-report/markIn', 'VisitorReportController@chackMarkInVisitor')->name('get.markInVisitor');
    Route::any('visitor-report/markout', 'VisitorReportController@markOutVisitor')->name('markoutvisitor');
    
    
    Route::any('setting/update', 'SettingController@amsSettingUpdate')->name('setting.update');
    

    


});
