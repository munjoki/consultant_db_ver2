<!------------------------------------------------------------------------------------------------------------------------------------------
	AKDN Consultant Database Version 1.0
	this file handles the invitation email sent to the consultant for them to register with the database
-------------------------------------------------------------------------------------------------------------------------------------------->

<br/>

@if(!empty($message_by))

{{$message_by}}
<br/>
<br/>
<hr>
@endif
<br/>
Dear {{ $name }},
<br/>

<br/>
You have been invited by {{ $akdn_name }}  to register with our Consultant Database.  You have been identified as a consultant with
valuable skills and relevant experience and joining the Database is by invitation only. The
purpose of this database is to provide AKDN staff members with a pool of qualified MER
consultants who are interested in being considered for consulting opportunities with AKDN
agencies. Registration makes your profile visible to a large number of AKDN staff who use
consultants.
<br/>
<br/>
The simple registration process will take only 5 minutes of your time, so please click on the
link below if you are interested in joining this Database. Once you have registered, you can
update or delete your profile at any time. 


<br/>
<br/>
Your Registration Token is: {{ $token }}.
<br/>
<br/>
Please click <a href="{{ URL::route('verifyregister',array('token' => $token))}}" target="_blank">HERE</a> to register with the AKDN MER Consultant Database.
<br/>

</p>
<br/>
Sincerely,<br/>
The AKDN Consultant Database Team