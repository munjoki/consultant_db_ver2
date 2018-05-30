<!------------------------------------------------------------------------------------------------------------------------------------------
	AKDN MER Consultant Database Version 1.0
	this file handles the email sent to the database administrator requesting for a consultant to be deleted from the database
-------------------------------------------------------------------------------------------------------------------------------------------->
<p>
Dear Administrator,<br/>
<p>
Kindly take this as a formal request from {{ $title }} {{ ucfirst($name) }}, who has been registered with your database as a consultant, to delete his/her profile. 
</p>
<br/>
<p>
Click the link below to proceed with deleting the consultant.
</p>
<a href="{{ URL::route('admin.login') }}" target="_blank">{{ URL::route('admin.login') }}</a><br/>
<p>
<br/>
Sincerely,<br/>
{{ $title }} {{ ucfirst($name) }}
</p>