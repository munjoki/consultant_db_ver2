@extends('user.userlayout')
@section('content')
<!------------------------------------------------------------------------------------------------------------------------------------------
	AKDN MER Consultant Database Version 1.0
	A file for the user to be able to edit/update their password
-------------------------------------------------------------------------------------------------------------------------------------------->
<h2 class="page-header">Change Password</h2>
<!-- Main content -->
<section class="content">

@include('partials.alert')

<div class="box">
	<div class="register-box-body">
		<div class="row">
			<div class="col-md-12">
				{{ Form::open(array('route' => 'user.changepassword.post', 'id' => 'frm-changepassword','role' => 'form', 'class' => 'form-horizontal')) }}
					<fieldset>
						<legend> <h4> <i class="fa fa-lock"></i> Change Password</h4></legend> 
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label class="col-md-4 control-label" for="">Current Password <span class="text-danger">*</span></label>
									<div class="col-md-8">
										{{ Form::password('old_password', array('id' => 'old_password', 'class' => 'form-control', 'placeholder' => 'Enter the current password')) }}
										{{ $errors->first('old_password', '<span class="help-inline">:message</span>')}}
									</div>
								</div>
							
								<div class="form-group">
									<label class="col-md-4 control-label" for="">New password <span class="text-danger">*</span></label>
									<div class="col-md-8">
										{{ Form::password('password', array('id' => 'password', 'class' => 'form-control', 'placeholder' => 'Enter the new password')) }}
										{{ $errors->first('password', '<span class="help-inline">:message</span>')}}
									</div>
								</div>
							
								<div class="form-group">
									<label class="col-md-4 control-label" for="">Confirm Password <span class="text-danger">*</span></label>
									<div class="col-md-8">
										{{ Form::password('password_confirmation', array('id' => 'password_confirmation', 'class' => 'form-control', 'placeholder' => 'Confirm the new password')) }}
										{{ $errors->first('password_confirmation', '<span class="help-inline">:message</span>')}}
									</div>
								</div>
							</div>
						</div>
						
						<div class="row">
							<div class="col-md-6 text-center">
							<button type="submit" class="btn btn-primary btn-flat">Update</button>
						</div>
					</fieldset>
				{{ Form::close() }}
			</div>
		</div>
	</div>
	<div class="overlay" id="frm-loader" style="display:none">
		<i class="fa fa-spinner fa-spin fa-lg"></i>
    </div>
</div>
</section>
@stop

@section('script')

<script type="text/javascript">

jQuery(document).ready(function() {

    $('form').submit(function(){
    	$(this).find('button:submit').html('<i class="fa fa-spin fa-spinner"></i> Please wait...');
    	$('#frm-loader').show();
    });

    if ( ($(window).height() + 100) < $(document).height() ) {
	    $('#top-link-block').removeClass('hidden').affix({
	        // how far to scroll down before link "slides" into view
	        offset: {top:100}
	    });
	}
});	

</script>
@stop

@section('style')

<style type="text/css">
   
    .help-inline { color: red; }
    .box .overlay>.fa { font-size: 50px; }
    body #canvas-wrapper {
		position: fixed;
		top: 0;
		left: 0;
		right: 0;
		bottom: 0;
		width: 100%;
		height: 100%;
	}
	.login-page, .register-page { background: #7E94A2; }
	#top-link-block.affix-top {
	    position: absolute; /* allows it to "slide" up into view */
	    bottom: -82px; /* negative of the offset - height of link element */
	    right: 10px; /* padding from the left side of the window */
	}
	#top-link-block.affix {
	    position: fixed; /* keeps it on the bottom once in view */
	    bottom: 18px; /* height of link element */
	    right: 10px; /* padding from the left side of the window */
	}
</style>
@stop