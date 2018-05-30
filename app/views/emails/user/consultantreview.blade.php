<!------------------------------------------------------------------------------------------------------------------------------------------
	AKDN Consultant Database Version 1.0
	this file handles the email sent to AKDN manager to review the consultant. It is triggered at the expiry of the consultancy end date
-------------------------------------------------------------------------------------------------------------------------------------------->
Dear {{ $manager_name }},
<br/>
<p>
We kindly request that you review the work of {{ $consultant_name }} who has been working with you as a
consultant from {{ $start_date }} to {{ $end_date }} on the following project: "{{ $title_of_consultancy }}".
  <br/>
  <br/>
This review should take only a few minutes and will help you and your colleagues choose the most
appropriate consultants in the future. Please click on the link below to access the review page.
<br/> 
<br/>
<a href="{{ URL::route('consultant.review.get', array($token)) }}" target="_blank">{{ URL::route('consultant.review.get', array($token)) }}</a><br/>
</p> 
<br/>
<p>
If you wish to extend the consultancy period, please click on the link below to provide the new consultancy end date.
<a href="{{ URL::route(consultant.extenddate.get,array($token))}}" target="_blank" >Extend</a>
</p>
<br/>

Sincerely,<br/>
The AKDN Consultant Database Team
