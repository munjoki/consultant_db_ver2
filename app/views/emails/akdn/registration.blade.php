<!------------------------------------------------------------------------------------------------------------------------------------------
	AKDN Consultant Database Version 1.0
	this file handles the registration email sent to the AKDN staff
-------------------------------------------------------------------------------------------------------------------------------------------->  
<p>
	Dear {{ $user_name }},<br/>
</p>
<p>
	Thank you for joining our Consultant Database. Your account has been created successfully. 
	Please click on the link below in order to verify your account. 
	You will be prompted to enter the system-generated password and then change your password to that of your choice. 
	Passwords must be at least 8 characters long and contain at least one upper-case letter, one lower-case letter, one digit and one special character (e.g. ! & $ #).
</p>
<p>
Username: {{ $email }}<br/>
System-generated password: {{ $password }}<br/>
</p>
<a href="{{ URL::to('akdn/login',array($token)) }}" target="_blank">{{ URL::to('akdn/login', array($token)) }}</a><br/>
<p>
<br/>
Sincerely,<br/>
The AKDN Consultant Database Team
</p>
			