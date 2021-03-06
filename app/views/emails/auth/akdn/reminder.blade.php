<!------------------------------------------------------------------------------------------------------------------------------------------
	AKDN MER Consultant Database Version 1.0
	this file handles the reset password email sent to AKDN user
-------------------------------------------------------------------------------------------------------------------------------------------->
Dear {{ $user->other_name }},
<br/>
<p>
Please click on the following link to reset your password:  
<a href="{{ URL::to('akdn/password/reset', array($token)) }}">{{ URL::to('akdn/password/reset', array($token)) }}</a>.
<br/><br/>
This link will expire in {{ Config::get('auth.reminder.expire', 60) }} minutes.
<br/><br/>
Sincerely,<br/>
The AKDN MER Consultant Database Team
</p>
