<!------------------------------------------------------------------------------------------------------------------------------------------
	AKDN Consultant Database Version 1.0
	this file allows the user to extend the end of consultancy date
-------------------------------------------------------------------------------------------------------------------------------------------->
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>Consultant | Review</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- Bootstrap 3.3.2 -->
    {{HTML::style('assets/bootstrap/css/bootstrap.min.css')}}

    {{ HTML::style('font-awesome-4.3.0/css/font-awesome.min.css') }}
    
    <!-- Theme style -->
    {{HTML::style('assets/dist/css/AdminLTE.min.css')}}

    {{ HTML::style('assets/plugins/datepicker/datepicker3.css') }}
   	<!-- iCheck -->
    {{HTML::style('assets/plugins/iCheck/square/blue.css')}}
    
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        {{ HTML::script('assets/js/html5shiv.js') }}
        {{ HTML::script('assets/js/respond.min.js') }}
    <![endif]-->
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
		.form-horizontal .control-label { text-align: left !important; }
	</style>
  </head>
<body class="register-page">
    
	<div id="canvas-wrapper">
		<canvas id="demo-canvas"></canvas>
	</div>
	<div class="row">
		<div class="col-md-2"></div>
		<div class="col-md-8">
			
			<div class="register-logo">
				<a href=""><b>Consultant </b> Review</a>
			</div>
			@include('partials.alert')
			<?php

			$consultant_name = ucwords($assignment->title . ". " . $assignment->surname);

			?>
			<div class="box no-border">
				<div class="register-box-body">
					<div class="row">
						<div class="col-md-12">
							{{Form::open(array('method'=>'post','id'=>'register_form','files' => true, 'class' => 'form-horizontal')); }}
									<div class="row">
										<div class="col-md-12">
											<div class="form-group">
												<label class="col-md-3 control-label" for="name">Consultant's Name</label>
												<div class="col-md-9">
													<p class="form-control-static">{{$consultant_name}}</p>
													{{ Form::hidden('assignment_id', $assignment->id) }}
													{{ $errors->first('assignment_id', '<span class="help-inline">:message</span>')}}
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<div class="form-group">
												<label class="col-md-3 control-label" for="startDate">Title of Consultancy</label>
												<div class="col-md-3">
													<p class="form-control-static">{{$assignment->title_of_consultancy}}</p>
												</div>	
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<div class="form-group">
												<label class="col-md-3 control-label" for="startDate">AKDN's Manager Name</label>
												<div class="col-md-3">
													<p class="form-control-static">{{$assignment->akdn_manager_name}}</p>
												</div>	
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<div class="form-group">
												<label class="col-md-3 control-label" for="startDate">Start Date</label>
												<div class="col-md-3">
													<p class="form-control-static">{{date('d-m-Y',strtotime($assignment->start_date))}}</p>
												</div>	
												<div class="form-group">
												<label class="col-md-3 control-label" for="startDate">End Date</label>
												<div class="col-md-3">
													<p class="form-control-static">{{date('d-m-Y',strtotime($assignment->end_date))}}</p>
													{{ Form::hidden('before_date', $assignment->end_date) }}
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<div class="form-group">
												<label class="col-md-3 control-label" for="name"> Extend End Date</label>
												<div class="col-md-4">
													<input type="text" name="end_date" class="form-control date-picker" id="aw-con-end_date" placeholder="End Date">
												  <span class="help-inline error"></span>
													{{ $errors->first('end_date', '<span class="help-inline">:message</span>')}}
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12 text-right">
										<button type="submit" class="btn btn-primary btn-flat">Submit</button>
										</div>
									</div>
							{{ Form::close() }}
						</div>
					</div>
				</div>
				<div class="overlay" style="display:none;" id="frm-loader">
					<i class="fa fa-spinner fa-spin fa-lg"></i>
	            </div>
			</div>
		</div>
		<div class="col-md-2"></div>
	</div>
    
    <!-- jQuery 2.1.3 -->
    {{ HTML :: script ('assets/plugins/jQuery/jQuery-2.1.3.min.js') }}

    <!-- Bootstrap 3.3.2 JS -->
    {{ HTML :: script ('assets/bootstrap/js/bootstrap.min.js') }}
    
    <!-- iCheck -->
    {{ HTML :: script ('assets/plugins/iCheck/icheck.min.js') }}
    
    {{ HTML::script('assets/js/canvasbg/EasePack.min.js' ) }}
    {{ HTML::script('assets/js/canvasbg/rAF.js' ) }}
    {{ HTML::script('assets/js/canvasbg/TweenLite.min.js' ) }}
    {{ HTML::script('assets/js/canvasbg/login.js' ) }}
    {{ HTML::script('assets/js/canvasbg/canvasbg.js') }}
	{{ HTML::script('assets/plugins/datepicker/bootstrap-datepicker.js') }}
	{{ HTML::script('assets/plugins/chosen/chosen.jquery.js') }}    
    <script type="text/javascript">

	jQuery(document).ready(function() {

		// Init CanvasBG and pass target starting location
        CanvasBG.init({
            Loc: {
                x: window.innerWidth / 2,
                y: window.innerHeight / 6
            },
        });

	    $('input[name="ratings"]').iCheck({ radioClass: 'iradio_square-blue' });

	    $('input[name="future_repeat"]').iCheck({ radioClass: 'iradio_square-blue' });

        $('form').submit(function(){
        	$(this).find('button:submit').html('<i class="fa fa-spin fa-spinner"></i> Please wait...');
        	$('#frm-loader').show();
        });

		$('input[name="end_date"]').datepicker({ format : 'dd-mm-yyyy'});
	});	

	</script>
</body>
</html>