<!------------------------------------------------------------------------------------------------------------------------------------------
	AKDN MER Consultant Database Version 1.0
	this old (25.06.2015) registration page for a consultant
-------------------------------------------------------------------------------------------------------------------------------------------->
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>Consultant | Register</title>
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
  </head>
<body class="register-page">
    
	<div id="canvas-wrapper">
		<canvas id="demo-canvas"></canvas>
	</div>
	<div class="row">
		<div class="col-md-2"></div>
		<div class="col-md-8">
			
			<div class="register-logo">
				<a href=""><b>Consultant</b> Registration</a>
			</div>
			@include('partials.alert')
			
			<div class="box no-border">
				<div class="register-box-body">
					<div class="row">
						<div class="col-md-12">
							{{Form::open(array('route'=>'register.post','method'=>'post','id'=>'register_form','files' => true, 'class' => 'form-horizontal')); }}
								<fieldset>
									<legend> <h4> <i class="fa fa-user"></i> Personal details <small class="help-inline pull-right">Fields marked by * are mandatory.</small></h4></legend> 
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label class="col-md-4 control-label" for="surname">Surname <span class="help-inline">*</span></label>
												<div class="col-md-8">
													{{ Form::text('surname', Input::old('surname'), array('id' => 'surname', 'class' => 'form-control', 'placeholder' => 'Enter surname')) }}
													{{ $errors->first('surname', '<span class="help-inline">:message</span>')}}
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label class="col-md-4 control-label" for="other_names">Given Names <span class="help-inline">*</span></label>
												<div class="col-md-8">
													{{ Form::text('other_names', Input::old('other_names'), array('id' => 'other_names', 'class' => 'form-control', 'placeholder' => 'Enter other names')) }}
													{{ $errors->first('other_names', '<span class="help-inline">:message</span>')}}
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label class="col-md-4 control-label" for="title">Title <span class="help-inline">*</span></label>
												<div class="col-md-8">
													{{ Form::select('title', array('' => 'Select your title', 'Dr' => 'Dr.','Mr' => 'Mr.', 'Miss' => 'Miss.'), null, array('id' => 'title', 'class' => 'form-control')) }}
													{{ $errors->first('title', '<span class="help-inline">:message</span>')}}
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label class="col-md-4 control-label" for="gender">Gender <span class="help-inline">*</span></label>
												<div class="col-md-8"> 
													<div class="row">
														<div class="col-md-4">
															<label for="gender">
																{{ Form::radio('gender','1', (Input::old('gender') == '1'), array('id'=>'male', 'class' => 'gender')) }} Male</i>
															</label>
														</div>
														<div class="col-md-6">
															<label for="gender">
																{{ Form::radio('gender','2', (Input::old('gender') == '2'), array('id'=>'female', 'class' => 'gender')) }} Female</i>
															</label>
														</div>
														<div class="clearfix"></div>
														{{ $errors->first('gender', '<span class="help-inline">:message</span>')}}
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label class="col-md-4 control-label" for="consultant type">Consultant type <span class="help-inline">*</span></label>
												<div class="col-md-8">
													{{ Form::select('consultant_type', array( '' => 'Select consultant type' ,'Independent consultant' => 'Independent consultant', 'Company' => 'Company','Both' => 'Both'), Input::old('consultant_type'), array('class' => 'form-control','id' => 'consultant_type')) }}
													{{ $errors->first('consultant_type', '<span class="help-inline">:message</span>')}}
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label class="col-md-4 control-label" for="companyname">Company name <span class="help-inline">*</span></label>
												<div class="col-md-8">
													{{ Form::text('company_name', Input::old('company_name'), array('id' => 'companyname', 'class' => 'form-control', 'placeholder' => 'Enter Company name')) }}
													{{ $errors->first('company_name', '<span class="help-inline">:message</span>')}}
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label class="col-md-4 control-label" for="nationality[]">Nationality <span class="help-inline">*</span></label>
												<div class="col-md-8">
													{{ Form::select('nationality[]',array(""=>"")+$country, '0',array( 'id' => 'nationality','class'=>'form-control chosen-select', 'multiple' => true,'data-placeholder' => 'Select your nationality'))}}
					                      			{{ $errors->first('nationality', '<span class="help-inline">:message</span>')}}
												</div>
											</div>	
										</div>					                      
									</div>
								</fieldset>
								<br/>
								<fieldset>
									<legend> <h4> <i class="fa fa-envelope"></i> Contact details</h4></legend> 
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label class="col-md-4 control-label" for="email">Email <span class="help-inline">*</span></label>
												<div class="col-md-8">
													<div class="input-group">
														{{ Form::text('email', Input::old('email'), array('id' => 'email', 'class' => 'form-control', 'placeholder' => 'Enter the primary email address')) }}
														<span class="input-group-addon">@</span>
													</div>
													{{ $errors->first('email', '<span class="help-inline">:message</span>')}}
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label class="col-md-4 control-label" for="alternate_email">Alternate Email </label>
												<div class="col-md-8">
													<div class="input-group">
														{{ Form::text('alternate_email', Input::old('alternate_email'), array('id' => 'alternate_email', 'class' => 'form-control', 'placeholder' => 'Enter an alternative email address')) }}
														<span class="input-group-addon">@</span>
													</div>
													{{ $errors->first('alternate_email', '<span class="help-inline">:message</span>')}}
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label class="col-md-4 control-label" for="telno">Tel. </label>
												<div class="col-md-8">
													<div class="input-group">
														<div class="input-group-addon">
															<i class="fa fa-plus"></i>
														</div>
														{{ Form::text('telno', Input::old('telno'), array('id' => 'telno', 'class' => 'form-control', 'placeholder' => 'Enter Tel No e.g. +41229097300')) }}
														<div class="input-group-addon">
															<i class="fa fa-mobile"></i>
														</div>
													</div>
													{{ $errors->first('telno', '<span class="help-inline">:message</span>')}}
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label class="col-md-4 control-label" for="alternate_telno">Alternate Tel. </label>
												<div class="col-md-8">
													<div class="input-group">
														<div class="input-group-addon">
															<i class="fa fa-plus"></i>
														</div>
														{{ Form::text('alternate_telno', Input::old('alternate_telno'), array('id' => 'alternate_telno', 'class' => 'form-control', 'placeholder' => 'Enter Tel No e.g. +41229097300')) }}
														<div class="input-group-addon">
															<i class="fa fa-phone"></i>
														</div>
													</div>
													{{ $errors->first('alternate_telno', '<span class="help-inline">:message</span>')}}
												</div>
											</div>
										</div>
									</div>
								</fieldset>
								<br/>
								<fieldset>
									<legend> <h4> <i class="fa fa-mortar-board"></i> Skills and competencies</h4></legend> 
									<div class="box box-default box-solid">
										<div class="box-header with-border">
											<h5 class="box-title">Language(s) Details</h5>
										</div>
										<div class="box-body" style="display: block;">
											{{ $errors->first('languages', '<p><span class="help-inline text-center">:message</span></p>')}}
											<div id="language_row" class="language_row row border-bottom">
												<div class="col-md-6">
													<div class="form-group">
														<label class="col-md-4 control-label" for="language">Language </label>
														<div class="col-md-8">
															{{ Form::select('language',array(""=>"--select Language---") + $language , '0', array('class'=>'form-control', 'id' => 'language'))}}
														</div>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label class="col-md-3 control-label" for="lang_level"> Level </label>
														<div class="col-md-6">
															{{ Form::select('lang_level', Config::get('language-level'), null, array('class'=>'form-control', 'id' => 'lang_level'))}}
														</div>
														<div class="col-md-3">
															<div class="input-group">
																<a id="plus_language" class="btn btn-success btn-flat btn-sm"><i class="fa fa-plus"></i></a>
										                		<a id="minus_language" class="btn btn-danger btn-flat btn-sm" style="display:none"><i class="fa fa-times"></i></a>
									                		</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label class="col-md-4 control-label" for="skills[]">Skill(s) </label>
												<div class="col-md-8">
													{{ Form::select('skill[]',array(""=>"")+$skills,'0',array('id' => 'skills', 'class'=>'form-control chosen-select', 'multiple' => 'true', 'data-placeholder' => 'Select relevant skills'))}}
						                     		{{ $errors->first('skill', '<span class="help-inline">:message</span>')}}
												</div>
											</div>	
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label class="col-md-4 control-label" for="specialization[]">Thematic Area(s)</label>
												<div class="col-md-8">
													{{ Form::select('specialization[]',array(""=>"")+$specialization,0,array('id' => 'specialization', 'class'=>'form-control chosen-select', 'multiple' => 'true', 'data-placeholder' => 'Select relevant thematic areas'))}}
						                     		{{ $errors->first('specialization', '<span class="help-inline">:message</span>')}}
												</div>
											</div>	
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label class="col-md-4 control-label" for="country_worked[]">Countries worked</label>
												<div class="col-md-8">
													{{ Form::select('country_worked[]',array(""=>"")+$country_worked, 0, array('id' => 'country_worked', 'class'=>'form-control chosen-select', 'multiple' => 'multiple', 'data-placeholder' => "Select the countries you've worked in"))}}
						                    		{{ $errors->first('country_worked', '<span class="help-inline">:message</span>')}}
												</div>
											</div>	
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label class="col-md-4 control-label" for="agency_affiliations[]">AKDN Agency Experience</label>
												<div class="col-md-8">
													{{ Form::select('consultant_agencies[]',array(""=>"")+$agencies, 0, array('id' => 'consultant_agencies', 'class'=>'form-control chosen-select', 'multiple' => 'multiple', 'data-placeholder' => 'Select agencies you have previously worked with'))}}
						                    		{{ $errors->first('consultant_agencies', '<span class="help-inline">:message</span>')}}
												</div>
											</div>	
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label class="col-md-4 control-label" for="linkedin_url">LinkedIn URL</label>
												<div class="col-md-8">
							                      	{{ Form::text('linkedin_url', Input::old('linkedin_url'), array('id' => 'linkedin_url', 'class' => 'form-control', 'placeholder' => 'Enter LinkedIn URL')) }}
							                    	{{ $errors->first('linkedin_url', '<span class="help-inline">:message</span>')}}
												</div>
											</div>	
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label class="col-md-4 control-label" for="website_url">Personal website/blog</label>
												<div class="col-md-8">
							                      	{{ Form::text('website_url', Input::old('website_url'), array('id' => 'website_url', 'class' => 'form-control', 'placeholder' => 'Enter website/blog URL')) }}
							                    	{{ $errors->first('website_url', '<span class="help-inline">:message</span>')}}
												</div>
											</div>	
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label class="col-md-4 control-label" for="resume">Upload Resume</label>
												<div class="col-md-8">
													{{ Form::file('resume') }}
						                     		{{ $errors->first('resume', '<span class="help-inline">:message</span>')}}
												</div>
											</div>	
										</div>
									</div>
								</fieldset>
								<fieldset>
									<legend></legend>
									<div class="row">
										<div class="col-md-12">
											<div class="form-group">
												<div class="col-md-8">
													<label for="terms_conditions">
								                  		{{ Form::checkbox('terms_conditions','1', (Input::old('terms_conditions') == '1'), array('id'=>'terms_conditions')) }} 
								                  		&nbsp; I agree to the <a href="#">terms</a>
								                	</label>
								                	{{ $errors->first('terms_conditions', '<span class="help-inline">:message</span>')}}
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

	jQuery(document).ready(function() {

		// Init CanvasBG and pass target starting location
        CanvasBG.init({
            Loc: {
                x: window.innerWidth / 2,
                y: window.innerHeight / 6
            },
        });

		var dynamic_languages = $("#language_row").dynamicForm("#plus_language", "#minus_language", {
	        limit:100,
	        formPrefix :'languages',
	        normalizeFullForm: false
	    });

		$('#consultant_type').on('change', function() {
  			// alert( this.value );  
  			// alert($(this).val());
  			if($(this).val()=="Independent consultant")
  			{
  				$('#company_name').attr('disabled','disabled');
  			}
		});

		// $( "#consultant_type option:selected" ).val();

	    // @if(Input::old('consultant_type')=='Independent consultant')
	    // 	$('#company_name').attr('readonly', true);
	    // @endif

		@if(Input::old('languages.languages') && count(Input::old('languages.languages')) > 0)
		dynamic_languages.inject( {{ json_encode(Input::old('languages.languages')) }} )
		@endif

	    $('input[name="gender"]').iCheck({ radioClass: 'iradio_square-blue' });

   		$('#terms_conditions').iCheck({ checkboxClass: 'icheckbox_square-blue' });

        $('.chosen-select').chosen();

        $('#frm-cancel').click(function(){
        	$(this).html('<i class="fa fa-spin fa-spinner"></i> Please wait...');
        	$('#frm-loader').show();
        	window.location.reload(true);
        });

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

		$(document).on('change', '#title', function(){

	        if($('#title').val() == 'Mr') {
	        	$('#male').iCheck('check');
	            
	        	$("#female").iCheck('uncheck');

	        } 
	        else if($('#title').val() == 'Miss')  {

	        	$('#female').iCheck('check');
	            
	        	$("#male").iCheck('uncheck');
	        } else {

	        	$("#male").iCheck('uncheck');
	        	$("#female").iCheck('uncheck');
	        }
		});
	});	

	</script>
</body>
</html>