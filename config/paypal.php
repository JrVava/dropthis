<?php 
return [ 
    // 'client_id' => 'ARpoKV_9Au87h81Tla-tzyu-eCSQNcXhZbbH94R7RdK-ej-VksF7qs_nshGTUHVxAqDnO7NM8nuiD9AQ',
    'client_id' => env('PAYPAL_CLIENT_ID'),
	// 'secret' => 'EG9vhBmyWVPSKvSMKyDHUFmDUJk5h6zTbWrM6Fo8P3VV14a485rGbQSpKCRXZR9jJWJOboFdNyBJDMZF',
    'secret' => env('PAYPAL_SECRET'),
    'settings' => array(
        // 'mode' => 'sandbox',
        'mode' => env('PAYPAL_MODE'),
        'http.ConnectionTimeOut' => 1000,
        'log.LogEnabled' => true,
        'log.FileName' => storage_path() . '/logs/paypal.log',
        'log.LogLevel' => 'FINE'
    ),
];