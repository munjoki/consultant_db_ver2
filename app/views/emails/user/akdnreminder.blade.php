<!------------------------------------------------------------------------------------------------------------------------------------------
	AKDN MER Consultant Database Version 1.0
	this file sends occasional emails to database users so that they can register consultancies
-------------------------------------------------------------------------------------------------------------------------------------------->
Dear {{ $name }},
<br/>
<p>
You have recently used the AKDN Monitoring, Evaluation and Research Consultant database to search for consultants. We however notice you have gone ahead to register any consultancy
 to any of the consultants in our database. 
  <br/>
  <br/>
If still interested in registering the consultancy, please click on the link below to proceed to our database. 
<br/>
<a href="{{ URL::to('/login') }}" target="_blank">Login</a>.<br/>
</p>
<br/>
<br/>
Sincerely,<br/>
The AKDN MER Consultant Database Team