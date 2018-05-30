<!------------------------------------------------------------------------------------------------------------------------------------------
	AKDN Consultant Database Version 1.0
	this file handles registration of AKDN staff
-------------------------------------------------------------------------------------------------------------------------------------------->
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>AKDN | Register</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- Bootstrap 3.3.2 -->
    {{HTML::style('assets/bootstrap/css/bootstrap.min.css')}}

    {{ HTML::style('assets/plugins/chosen/chosen.jquery.css') }}    

    {{ HTML::style('font-awesome-4.3.0/css/font-awesome.min.css') }}
    
    <!-- Theme style -->
    {{HTML::style('assets/dist/css/AdminLTE.min.css')}}

   	<!-- iCheck -->
    {{HTML::style('assets/plugins/iCheck/square/blue.css')}}
    
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        {{ HTML::script('assets/js/html5shiv.js') }}
        {{ HTML::script('assets/js/respond.min.js') }}
    <![endif]-->
    <style type="text/css">
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
  </head>
<body class="register-page">
    
	<div id="canvas-wrapper">
		<canvas id="demo-canvas"></canvas>
	</div>
	<div class="row">
		<div class="col-md-2"></div>
		<div class="col-md-8">
			<div class="register-logo">
				<a href=""><b>AKDN User</b> Registration</a>
			</div>
			<div class="box no-border">
				<div class="register-box-body">
					<div class="row">
						<div class="col-md-12">
							{{Form::open(array('route'=>'akdn.register.post','method'=>'post','id'=>'register_form','files' => true, 'class' => 'form-horizontal')); }}
								<fieldset>
									<legend> <h4> <i class="fa fa-user"></i> AKDN User Details <small class="text-danger pull-right">Fields marked with an asterisk (*) are required</small></h4></legend> 
									<div class="row">
										<div class="col-md-12">
											<div class="form-group">
												<label class="col-md-3 control-label" for="surname">Surname <span class="text-danger">*</span></label>
												<div class="col-md-8">
													
													{{ Form::text('surname', Input::old('surname'), array('id' => 'surname', 'class' => 'form-control', 'placeholder' => 'Enter surname')) }}
													{{ $errors->first('surname', '<span class="text-danger">:message</span>')}}
												</div>
											</div>
										</div>
										<div class="col-md-12">
											<div class="form-group">
												<label class="col-md-3 control-label" for="other_names">First/Given Name(s) <span class="text-danger">*</span></label>
												<div class="col-md-8">
													{{ Form::text('other_name', Input::old('other_names'), array('id' => 'other_names', 'class' => 'form-control', 'placeholder' => 'Enter first/given name(s)')) }}
													{{ $errors->first('other_name', '<span class="text-danger">:message</span>')}}
												</div>
											</div>
										</div>
										<div class="col-md-12">
											<div class="form-group">
												<label class="col-md-3 control-label" for="nationality[]">Country <span class="text-danger">*</span></label>
												<div class="col-md-8">
													{{ Form::select('nationality[]',array(""=>"Select the country where you are currently based")+$country, '0',array( 'id' => 'nationality','class'=>'form-control chosen-select','placeholder'=>'Select the country where you are currently based'))}}
					                      			{{ $errors->first('nationality', '<span class="text-danger">:message</span>')}}
												</div>
											</div>
										</div>
										<div class="col-md-12">
											<div class="form-group">
												<label class="col-md-3 control-label" for="email">AKDN Email <span class="text-danger">*</span></label>
												<div class="col-md-8">
													<div class="input-group">
														{{ Form::text('email', Input::old('email'), array('id' => 'email', 'class' => 'form-control', 'placeholder' => 'Enter the AKDN email address')) }}
														<span class="input-group-addon">@</span>
													</div>
													{{ $errors->first('email', '<span class="text-danger">:message</span>')}}
												</div>
											</div>
										</div>
										<div class="col-md-12">
											<div class="form-group">
												<label class="col-md-3 control-label" for="agency_affiliations[]">AKDN Agency Affiliation</label>
												<div class="col-md-8">
													{{ Form::select('consultant_agencies[]',array(""=>"")+$agencies, Input::old('consultant_agencies',[1]), array('id' => 'consultant_agencies', 'class'=>'form-control chosen-select', 'multiple' => 'multiple', 'data-placeholder' => 'Select the AKDN agency(ies) you currently work for'))}}
						                    		{{ $errors->first('consultant_agencies', '<span class="text-danger">:message</span>')}}
												</div>
											</div>	
										</div>
										<div class="col-md-12">
											<div class="form-group">
												<div class="col-md-8 col-md-offset-4">
													<!-- <div class="col-md-6"></div> -->
													<!-- <div class="col-md-6"> -->
								                  		{{ Form::checkbox('terms_conditions','1', (Input::old('terms_conditions') == '1'), array('id'=>'terms_conditions')) }} 
								                  		&nbsp; I agree to the
								                  		<a data-toggle="modal" href="javascript:void(0)" data-target="#consultantdb_terms">Terms and Conditions</a>
								                		{{ $errors->first('terms_conditions', '<span class="text-danger">:message</span>')}}
													<!-- </div> -->
													<!-- <label for="terms_conditions" class="text-center">
								                	</label> -->
								                </div>
								            </div>    
								        </div>
								    </div>
								</fieldset>
								<fieldset>
									<legend></legend>
									<div class="row">
										<div class="col-md-12 text-center">
										<button type="submit" class="btn btn-primary btn-flat">Register</button>
										<a href="javascript:void(0)" id="frm-cancel" class="btn btn-danger btn-flat">Cancel</a>
									</div>
								</fieldset>
								
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
    

	<span id="top-link-block" class="hidden">
	    <a href="#top" class="well well-sm"  onclick="$('html,body').animate({scrollTop:0},'slow');return false;">
	        <i class="glyphicon glyphicon-chevron-up"></i> Back to Top
	    </a>
	</span><!-- /top-link-block -->

	<!-- Modal -->
	<div class="modal fade" id="consultantdb_terms" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	    <div class="modal-dialog modal-lg">
	        <div class="modal-content">
	            <div class="modal-header">
	                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	                <h4 class="modal-title">Terms &amp; Conditions</h4>
	            </div>
	            <div class="modal-body text-justify"></div>
	            <!-- <div class="modal-footer">
	                <button type="button" class="btn btn-default" data-dismiss="modal">Ok</button>
	            </div> -->
	        </div>
	        <!-- /.modal-content -->
	    </div>
	    <!-- /.modal-dialog -->
	</div>
	<div class="modal modal-success" id="consultant_success">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            <h4 class="modal-title">Success</h4>
          </div>
          <div class="modal-body">
            <p>{{ Session::get('message') }}</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Close</button>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div>
	<!-- /.modal -->
	<div class="modal modal-danger" id="consultant_error">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            <h4 class="modal-title">Error</h4>
          </div>
          <div class="modal-body">
            <p>{{ Session::get('message') }}</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Close</button>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div>
    <!-- jQuery 2.1.3 -->
    {{ HTML :: script ('assets/plugins/jQuery/jQuery-2.1.3.min.js') }}

    <!-- Bootstrap 3.3.2 JS -->
    {{ HTML :: script ('assets/bootstrap/js/bootstrap.min.js') }}
    
    <!-- iCheck -->
    {{ HTML :: script ('assets/plugins/iCheck/icheck.min.js') }}
    
    {{ HTML::script('assets/js/jquery-dynamic-form.js') }}
    
    {{ HTML::script('assets/js/canvasbg/EasePack.min.js' ) }}
    {{ HTML::script('assets/js/canvasbg/rAF.js' ) }}
    {{ HTML::script('assets/js/canvasbg/TweenLite.min.js' ) }}
    {{ HTML::script('assets/js/canvasbg/login.js' ) }}
    {{ HTML::script('assets/js/canvasbg/canvasbg.js') }}

	{{ HTML::script('assets/plugins/chosen/chosen.jquery.js') }}    
    <script type="text/javascript">

    //when consultant_type is Independent consultant,company_name will be Read only.

	jQuery(document).ready(function() {
		

		$('#consultantdb_terms').on('shown.bs.modal', function(e){
			$(e.target).find('.modal-body').load("{{ URL::route('consultant.terms') }}", function(){

				$(e.target).find('.modal-backdrop').height( $(e.target).find('.modal-body').height() + 150 );
			});
		});
  			

		// Init CanvasBG and pass target starting location
        CanvasBG.init({
            Loc: {
                x: window.innerWidth / 2,
                y: window.innerHeight / 6
            },
        });


		
		@if(Session::has('message'))
				
			@if("success" == Session::get('message_type'))
				$('#consultant_success').modal('show');
				
			@endif
			@if( 'danger' == Session::get('message_type') )
				$('#consultant_error').modal('show');
			@endif
		@endif	

		$('#consultant_success').on('hidden.bs.modal', function () {
			//open(location, '_self').close();
			setTimeout(function(){
				window.location = "{{ route('akdn.login') }}"},2000
			);
		});

	    $('input[name="gender"]').iCheck({ radioClass: 'iradio_square-blue' });

   		$('#terms_conditions').iCheck({ checkboxClass: 'icheckbox_square-blue' });

        $('.chosen-select').chosen();

        $('#frm-cancel').click(function(){
        	$(this).html('<i class="fa fa-spin fa-spinner"></i> Please wait...');
        	$('#frm-loader').show();
        	window.location.reload(true);
        });

        
        $('form').submit(function(){
        	//function loadingBtn(button);
        });


});
	</script>
</body>
</html>