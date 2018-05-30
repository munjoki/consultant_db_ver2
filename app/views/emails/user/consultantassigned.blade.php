<!------------------------------------------------------------------------------------------------------------------------------------------
	AKDN MER Consultant Database Version 1.0
	this file handles the email sent to a consultant once they have been sponsored by an AKDN staff
-------------------------------------------------------------------------------------------------------------------------------------------->
Dear {{ $name }},
<br/>
<p>
You have been invited by an AKDN employee to register with the AKDN Monitoring, Evaluation and Research 
(MER) Consultant Database. This database serves as a centralized location for all AKDN agencies to search for the most appropriate MER consultants, 
and its simplistic and user-friendly design allows consultants interested in AKDN to easily keep their profiles up-to-date. 
  <br/>
  <br/>
Please click on the link below to proceed with the registration.
<br/>
<a href="{{ URL::to('/register') }}" target="_blank">Register</a><br/>
</p>
<br/>
<br/>
Sincerely,<br/>
The AKDN MER Consultant Database Team