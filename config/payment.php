<?php

return [

    /*
    |--------------------------------------------------------------------------
    | CCAvenue configuration file
    |--------------------------------------------------------------------------
    |   gateway = CCAvenue
    |   view    = File
     */

    'gateway' => 'CCAvenue', // Making this option for implementing multiple gateways in future

    'testMode' => true, // True for Testing the Gateway [For production false]

    'ccavenue' => [ // CCAvenue Parameters
        'merchantId'  => env('CCAVENUE_MERCHANT_ID', '241336'),
        'accessCode'  => env('CCAVENUE_ACCESS_CODE', 'AVMI03GL01BL00IMLB'),
        'workingKey' => env('CCAVENUE_WORKING_KEY', 'C6B5FD90F688DE972C9A3213C8D46421'),

        // Should be route address for url() function
        'redirectUrl' => env('CCAVENUE_REDIRECT_URL', ''),
        'cancelUrl' => env('CCAVENUE_CANCEL_URL', ''),

        'currency' => env('CCAVENUE_CURRENCY', 'INR'),
        'language' => env('CCAVENUE_LANGUAGE', 'EN'),
    ],

    // Add your response link here. In Laravel 5.* you may use the api middleware instead of this.
    'remove_csrf_check' => [
        '/franchisee/bus/ccavenue-payment-response', '/franchisee/flight/ccavenue-payment-flight-response'
    ],

];
