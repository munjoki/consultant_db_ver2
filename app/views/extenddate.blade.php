<!------------------------------------------------------------------------------------------------------------------------------------------
	AKDN MER Consultant Database Version 1.0
	this file handles extension of the consultancy dates i.e. prolonging the consultancy period
-------------------------------------------------------------------------------------------------------------------------------------------->
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <status>Consultant | Review</status>
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
												<label class="col-md-3 control-label" for="name">Consultant Name</label>
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
												<label class="col-md-3 control-label" for="startDate">Titale</label>
												<div class="col-md-3">
													<p class="form-control-static">{{$assignment->title_of_consultancy}}</p>
												</div>	
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<div class="form-group">
												<label class="col-md-3 control-label" for="startDate">Manager Name</label>
												<div class="col-md-3">
													<p class="form-control-static">{{$assignment->akdn_manager_name}}</p>
												</div>	
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<div class="form-group">
												<label class="col-md-3 control-label" for="startDate">Sart Date</label>
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
												<label class="col-md-3 control-label" for="ratings">Status</label>
												<div class="col-md-8">
													<label for="Status ">
														{{ Form::radio('status','1', (Input::old('status') == '1'), array('id'=>'com', 'class' => 'ratings')) }} Complete &nbsp;&nbsp;
														{{ Form::radio('status','2', (Input::old('status') == '2'), array('id'=>'ter', 'class' => 'ratings' )) }} Terminate &nbsp;&nbsp;
														{{ Form::radio('status','3', (Input::old('status') == '3'), array('id'=>'ex', 'class' => 'ratings')) }} ExtendDate &nbsp;&nbsp;
														
													</label>
													<br/>
													{{ $errors->first('status', '<span class="help-inline">:message</span>')}}
												</div>
											</div>
										</div>
									</div>
									<div class="row" id="end_date" style="display:none;">
										<div class="col-md-12">
											<div class="form-group">
												<label class="col-md-3 control-label" for="name"> Extend EndDate</label>
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

	// function changetextbox()
	// 	{	
	// 		var i = document.getElementById("ex").value;

	// 		console.log(i);

	// 	    if (document.getElementById("ex").value === "3") {
	// 	    	alert('hi');
	// 	        $('#end_date').show();
	// 	        // $('#company_span').hide();
	// 	    } else {
	// 	       $('#end_date').hide();
	// 	    }
	// 	}


	jQuery(document).ready(function() {

		// Init CanvasBG and pass target starting location
        CanvasBG.init({
            Loc: {
                x: window.innerWidth / 2,
                y: window.innerHeight / 6
            },
        });

         $("input[name$='status']").click(function() {
	        var test = $(this).val();
	        if (test === "3") {
		        $('#end_date').show();
		        // $('#company_span').hide();
		    } else {
		       $('#end_date').hide();
		    }
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