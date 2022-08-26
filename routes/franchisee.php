<?php

Route::get('/', function() {

    return view('web.home');

});

Route::group(['middleware' => 'franchisee.guest'], function() {

    Route::get('login', 'Auth\LoginController@showLoginForm')->name('franchisee.login.form');

    Route::post('login', 'Auth\LoginController@login')->name('franchisee.login.post');

});



Route::post('franchisee-detail', 'Auth\LoginController@franchiseeDetail')->name('franchisee.get-detail');





Route::group(['middleware' => 'franchisee'], function(){

	

    Route::get('dashboard', 'DashboardController@index')->name('franchisee.dashboard');

   

    Route::group(['prefix'=>'order/'], function(){

        Route::get('all', 'OrderController@getOrders')->name('franchisee.orders');

        Route::get('invoice/{id}', 'OrderController@orderInvoice')->name('franchisee.order-detail');

        Route::get('detail/{id}', 'OrderController@orderDetail')->name('franchisee.order-detail');

        Route::post('update', 'OrderController@updateOrder')->name('franchisee.update-order');

        Route::get('create', 'OrderController@createOrderPage')->name('franchisee.create-order.get');

        Route::post('create', 'OrderController@createOrder')->name('franchisee.create-order.post');

    });



    Route::group(['prefix'=>'inventory'], function(){



        Route::get('record', 'InventoryController@getRecords')->name('franchisee.inventory-records');



        Route::get('order-detail/{id}', 'InventoryController@orderDetail')->name('franchisee.inventory-purchase-deatil');



        Route::post('update-order', 'InventoryController@updateOrder')->name('franchisee.update-inventory-order');



        Route::get('request', 'InventoryController@requestInventory')->name('franchisee.request-inventory');



        Route::post('submit-request', 'InventoryController@orderInventory')->name('franchisee.request-inventory.post');



        Route::get('available', 'InventoryController@myInventories')->name('franchisee.available-inventories');

    });



    Route::get('/services', 'ServiceController@allServices')->name('available-services');

    



    Route::group(['prefix'=>'service'], function(){

        

        Route::get('/statement', 'ServiceController@gteAllStatement')->name('franchisee.service-statement');



        Route::get('/{service_type?}', 'ServiceController@allServices')->name('franchisee.selected-service');



        Route::post('/bill-payment', 'ServiceController@payBill')->name('franchisee.service.bill-pay');



        Route::post('/check-bill-amount', 'ServiceController@checKbillAmount')->name('franchisee.service.check-bill-amount');





    });



    ## flight Route

    Route::group(['prefix'=>'flight'],function(){

        Route::get('/','FlightBookingController@flightDetails')->name('franchisee.flight-page');

        Route::get('/all','FlightBookingController@flightDetails')->name('franchisee.flight-booking');

        Route::get('/search','FlightBookingController@flightsearch')->name('franchisee.flight-search');

        Route::get('/review','FlightBookingController@flightRreview')->name('franchisee.flight-review');

        Route::post('/flight-baggage','FlightBookingController@flightPassenger')->name('franchisee.flight-passanger');



        Route::post('/flight-payment','FlightBookingController@flightPayment')->name('franchisee.flight-payment');



        Route::get('/Calendar-Fare','FlightBookingController@getCalendarFare')->name('franchisee.Calendar-Fare');



        Route::get('/ticket/{booking_id?}', 'FlightBookingController@fetchTicketDetail')->name('franchisee.flight-ticket');



        Route::post('/city-search','FlightBookingController@searchCity')->name('franchisee.flight-city-search');



        Route::post('/ticket-cancel', 'FlightBookingController@cancleTicket')->name('franchisee.flight-ticket-cancel');

        

        Route::post('/ccavenue-payment-flight-response', 'FlightBookingController@ccavenuePayResponse'); 

       

    });



    ## Bus Booking route

    Route::group(['prefix'=>'bus'],function(){

        Route::get('/','BusController@busDetails')->name('franchisee.bus-page');

        Route::get('/search','BusController@busSearch')->name('franchisee.bus-search');

        Route::post('/seat-layout','BusController@seatLayout')->name('franchisee.bus-seatlayout');

        Route::get('/seat-bordingpoint','BusController@bordingPoints')->name('franchisee.bus-boardingpoint');

        Route::get('/bus-review','BusController@busReview')->name('franchisee.bus-review');

        Route::GET('/bus-ticket/{id}', 'BusController@ticketDetail')->name('franchisee.bus.ticket-invoice');



        Route::post('/bus-block','BusController@blockTicket')->name('franchisee.bus-seatblock');



        Route::post('/city-search','BusController@searchCity')->name('franchisee.bus-city-search');



        Route::post('/ccavenue-payment-response', 'BusController@ccavenuePayResponse');

       

    });



    Route::get('/all-transaction', 'StatementController@allTransactions')->name('franchisee.all-transaction');



     Route::get('statement/flight', 'StatementController@flightStatement')->name('franchisee.flight.statement'); 



     Route::get('statement/bus', 'StatementController@busStatement')->name('franchisee.bus.statement'); 



           Route::post('/available-pins', 'FranchiseePinController@getAvailablePins')->name('franchisee.get-available-pins');



     Route::get('/transfer-pin', 'FranchiseePinController@pinTransferPage')->name('franchisee.transfer-pin.get');



     Route::post('/transfer-pin', 'FranchiseePinController@transferPin')->name('franchisee.transfer-pin.post');

     Route::get('/pin-transfer', 'FranchiseePinController@pinTransferStatement')->name('franchisee.pin-transfer-statement');



    Route::get('/activation-panel', 'FranchiseePinController@idActivationPage')->name('franchisee.activation-panel.get');



    Route::post('/activate-id', 'FranchiseePinController@activateIdByPin')->name('franchisee.activate-id.post');





	Route::get('logout', 'Auth\LoginController@logout')->name('logout.get');



});