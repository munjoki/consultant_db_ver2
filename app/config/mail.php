<?php

return array(

	/*
	|--------------------------------------------------------------------------
	| Mail Driver
	|--------------------------------------------------------------------------
	|
	| Laravel supports both SMTP and PHP's "mail" function as drivers for the
	| sending of e-mail. You may specify which one you're using throughout
	| your application here. By default, Laravel is setup for SMTP mail.
	|
	| Supported: "smtp", "mail", "sendmail", "mailgun", "mandrill", "log"
	|
	*/

	'driver' => 'smtp',
	'host' => 'smtp.ibpmail.com',
	'port' => 465, 
	'from' => array('address' => 'consultants@akdn.org', 'name' => 'AKDN Consultant Database'),
	'encryption' => 'ssl',
	'username' => 'consultants@akdn.org',
	'password' => 'C0n5LtTs',
	'sendmail' => '/usr/sbin/sendmail -bs',
	'pretend' => false,
);
