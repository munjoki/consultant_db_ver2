<!------------------------------------------------------------------------------------------------------------------------------------------
	AKDN MER Consultant Database Version 1.0
	this file manages the current session and forks alerts as necessary
-------------------------------------------------------------------------------------------------------------------------------------------->
@if(Session::has('message'))
	@if( 'danger' == Session::get('message_type') )

	<div class="alert alert-danger alert-dismissable">
	    <button type="button" class="close" data-dismiss="alert">×</button>
	    <b>ERROR!</b> {{ Session::get('message') }}
	</div>

	@elseif( 'success' == Session::get('message_type') )
	<div class="alert alert-success alert-dismissable">
	    <button type="button" class="close" data-dismiss="alert">×</button>
	    <b>SUCCESSFUL!</b> {{ Session::get('message') }}
	</div>

	@elseif( 'warning' == Session::get('message_type') )
	<div class="alert alert-warning alert-dismissable">
	    <button type="button" class="close" data-dismiss="alert">×</button>
	    <b>ALERT!</b> {{ Session::get('message') }}
	</div>
	@endif

@endif