<!------------------------------------------------------------------------------------------------------------------------------------------
	AKDN MER Consultant Database Version 1.0
	this file handles the consultant review levels
-------------------------------------------------------------------------------------------------------------------------------------------->
<div class="rating inline" data-score="{{ $user->consultant->ratings }}"></div>
@if($avg_ratings == 'Very poor')
<span class="badge bg-red">{{ $avg_ratings }}</span>
@elseif($avg_ratings == 'Poor')
<span class="badge bg-gray">{{ $avg_ratings }}</span>
@elseif($avg_ratings == 'Average')
<span class="badge bg-yellow">{{ $avg_ratings }}</span>
@elseif($avg_ratings == 'Good')
<span class="badge bg-blue">{{ $avg_ratings }}</span>
@elseif($avg_ratings == 'Excellent')
<span class="badge bg-green">{{ $avg_ratings }}</span>
@else
{{ $avg_ratings }}
@endif
