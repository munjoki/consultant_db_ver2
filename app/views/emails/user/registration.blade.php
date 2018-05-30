<!------------------------------------------------------------------------------------------------------------------------------------------
	AKDN MER Consultant Database Version 1.0
	this file handles the confirmation email sent on successfully registering with the database
-------------------------------------------------------------------------------------------------------------------------------------------->
<p>
Dear {{ $user_name }},<br/>
<p>
Thank you for joining our Monitoring, Evaluation and Research Consultant Database. Your account has been created successfully. 
Please click on the link below in order to verify your account. 
You will be prompted to enter the system-generated password, but after that you will be able to create your own password. 
Passwords must be at least 8 characters long and contain at least one upper-case letter, one lower-case letter, one digit and one special character (e.g. ! & $ #). 
</p>

<p>
Username: {{ $email }}<br/>
System-generated password: {{ $password }}<br/>
</p>      
<a href="{{ URL::to('login',array($token)) }}" target="_blank">{{ URL::to('login', array($token)) }}</a><br/>
<p>
<br/>
Sincerely,<br/>
The AKDN MER Consultant Database Team
</p>