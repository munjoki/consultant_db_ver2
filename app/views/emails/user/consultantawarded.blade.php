<!------------------------------------------------------------------------------------------------------------------------------------------
	AKDN MER Consultant Database Version 1.0
	this file handles the email sent to the user on successfully registering a consultancy
-------------------------------------------------------------------------------------------------------------------------------------------->
<p>Dear {{ $akdn_name }},</p>
<p>
You have successfully registered a consultancy to {{ $consultant_name }} starting from 
{{ $start_date }} to {{ $end_date }} for the following project: "{{$consultant_assignment_title}}".
<br/> 
You can always delete or edit the consultancy details by clicking the link below: </p>
<a href="{{ route('akdn.login') }}">Login Here</a>
<p>
<br/>
Sincerely,<br/>
The AKDN MER Consultant Database Team
</p>