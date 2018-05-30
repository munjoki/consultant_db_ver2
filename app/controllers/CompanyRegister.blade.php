<!------------------------------------------------------------------------------------------------------------------------------------------
	AKDN Consultant Database Version 1.0
	this file the registration page for a consultant
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
    <style type="text/css">nationality
	   
	    
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
									<legend class="pull-left"> 
										<h4 class="pull-left"> Introduction </h4>
										<a class="pull-right" tabindex="-1" href="{{ asset('upload/downloads/consultant-manual.pdf') }}" target="_blank"></i><h4>User Manual</h4></a>
									</legend>
									<div class="clearfix"></div>
									<label><h5>
											You have been sponsored by an Aga Khan Development Network (AKDN) staff member to register with our Consultant Database. 
											This database is maintained by the AKDN Quality of Life MER Support Unit. 
											The purpose of this database is to provide AKDN staff members with a pool of qualified consultants who are interested in being considered for consulting opportunities with AKDN agencies. 
											Please complete the short registration form below if you are interested in joining this database. Once you are a registered member, you can update or delete your profile at any time. 
										</h5>
									</label>
									</fieldset>
								<fieldset>
									<legend> <h4> Personal Details <small class="text-danger pull-right">Fields marked with an asterisk (*) are required</small></h4></legend> 
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label class="col-md-4 control-label" for="surname">Company name <span class="text-danger">*</span></label>
												<div class="col-md-8">
													{{ Form::text('surname', Input::old('surname'), array('id' => 'surname', 'class' => 'form-control', 'placeholder' => 'Enter surname')) }}
													{{ $errors->first('surname', '<span class="text-danger">:message</span>')}}
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label class="col-md-4 control-label" for="Location address">Location/address <span class="text-danger">*</span></label>
												<div class="col-md-8">
													{{ Form::text('other_names', Input::old('other_names'), array('id' => 'other_names', 'class' => 'form-control', 'placeholder' => 'Enter first/given name(s)')) }}
													{{ $errors->first('other_names', '<span class="text-danger">:message</span>')}}
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<!-- <div class="col-md-6">
											<div class="form-group">
												<label class="col-md-4 control-label" for="title">Title <span class="text-danger">*</span></label>
												<div class="col-md-8">
													{{ Form::select('title', array('' => 'Select your title', 'Dr' => 'Dr.','Mr' => 'Mr.', 'Ms' => 'Ms.'), null, array('id' => 'title', 'class' => 'form-control')) }}
													{{ $errors->first('title', '<span class="text-danger">:message</span>')}}
												</div>
											</div>
										</div> -->

										<div class="col-md-6">
											<div class="form-group">
												<label class="col-md-4 control-label" for="country">Country <span class="text-danger">*</span></label>
												<div class="col-md-8">
													{{ Form::select('nationality[]',array(""=>"")+$country, '0',array( 'id' => 'nationality','class'=>'form-control chosen-select','multiple'=>'true', 'data-placeholder' => "Select your nationality(ies)"))}}
					                      			{{ $errors->first('nationality', '<span class="text-danger">:message</span>')}}
												</div>
											</div>	
										</div>	
										<div class="col-md-6">
											<div class="form-group">
												<label class="col-md-4 control-label" for="Telephone number">Telephone number <span class="text-danger">*</span></label>
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
														{{ $errors->first('gender', '<span class="text-danger">:message</span>')}}
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label class="col-md-4 control-label" for="consultant registration number">Company registration number <span class="text-danger">*</span></label>
												<div class="col-md-8">
													{{ Form::select('consultant_type', array( '' => 'Select consultant type' ,'Independent consultant' => 'Independent consultant', 'company' => 'Institution-affiliated','Both' => 'Both independent and institution-affiliated'), Input::old('consultant_type'), array('class' => 'form-control','id' => 'consultant_type','onChange'=>'changetextbox();')) }}
													{{ $errors->first('consultant_type', '<span class="text-danger">:message</span>')}}
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label class="col-md-4 control-label" for="Company contact">Company contact</label>
												<div class="col-md-8">
													{{ Form::text('company_name', Input::old('company_name'), array('id' => 'company_name', 'class' => 'form-control', 'placeholder' => 'Enter institution name')) }}
													{{ $errors->first('company_name', '<span class="text-danger">:message</span>')}}
												</div>
											</div>
										</div>
									</div>
									<!-- <div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label class="col-md-4 control-label" for="nationality[]">Nationality(ies) <span class="text-danger">*</span></label>
												<div class="col-md-8">
													{{ Form::select('nationality[]',array(""=>"")+$country, '0',array( 'id' => 'nationality','class'=>'form-control chosen-select','multiple'=>'true', 'data-placeholder' => "Select your nationality(ies)"))}}
					                      			{{ $errors->first('nationality', '<span class="text-danger">:message</span>')}}
												</div>
											</div>	
										</div>					                      
									</div> -->
								</fieldset>
								<br/>
								<fieldset>
									<legend> <h4> Contact Details</h4></legend> 
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label class="col-md-4 control-label" for="email">Email <span class="text-danger">*</span></label>
												<div class="col-md-8">
													<div class="input-group">
														{{ Form::text('email', Input::old('email'), array('id' => 'email', 'class' => 'form-control', 'placeholder' => 'Enter primary email address')) }}
														<span class="input-group-addon">@</span>
													</div>
													{{ $errors->first('email', '<span class="text-danger">:message</span>')}}
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label class="col-md-4 control-label" for="Bio description of services">Bio/description of services </label>
												<div class="col-md-8">
													<div class="input-group">
														{{ Form::text('alternate_email', Input::old('alternate_email'), array('id' => 'alternate_email', 'class' => 'form-control', 'placeholder' => 'Enter alternative email address')) }}
														<span class="input-group-addon">@</span>
													</div>
													{{ $errors->first('alternate_email', '<span class="text-danger">:message</span>')}}
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label class="col-md-4 control-label" for="telno">Tel. (inc. country code) </label>
												<div class="col-md-8">
													<div class="input-group">
														<div class="input-group-addon">
															<i class="fa fa-plus"></i>
														</div>
														{{ Form::text('telno', Input::old('telno'), array('id' => 'telno', 'class' => 'form-control', 'placeholder' => 'Enter Tel No. e.g. +41229097300')) }}
														<div class="input-group-addon">
															<i class="fa fa-mobile"></i>
														</div>
													</div>
													{{ $errors->first('telno', '<span class="text-danger">:message</span>')}}
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label class="col-md-4 control-label" for="alternative tel">Alternative Tel. (inc. country code) </label>
												<div class="col-md-8">
													<div class="input-group">
														<div class="input-group-addon">
															<i class="fa fa-plus"></i>
														</div>
														{{ Form::text('alternate_telno', Input::old('alternate_telno'), array('id' => 'alternate_telno', 'class' => 'form-control', 'placeholder' => 'Enter Tel No. e.g. +41229097300')) }}
														<div class="input-group-addon">
															<i class="fa fa-phone"></i>
														</div>
													</div>
													{{ $errors->first('alternate_telno', '<span class="text-danger">:message</span>')}}
												</div>
											</div>
										</div>
									</div>
								</fieldset>
								<br/>
								div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label class="col-md-4 control-label" for="Thematic Area">Thematic Area(s)</label>
												<div class="col-md-8">
													{{ Form::select('specialization[]',array(""=>"")+$specialization,0,array('id' => 'specialization', 'class'=>'form-control chosen-select', 'multiple' => 'true', 'data-placeholder' => 'Select relevant field(s) of expertise'))}}
						                     		{{ $errors->first('specialization', '<span class="text-danger">:message</span>')}}
												</div>
											</div>	
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label class="col-md-4 control-label" for="skills[]">Skill(s) </label>
												<div class="col-md-8">
													{{ Form::select('skill[]',array(""=>"")+$skills,'0',array('id' => 'skills', 'class'=>'form-control chosen-select', 'multiple' => 'true', 'data-placeholder' => 'Select applicable skills'))}}
						                     		{{ $errors->first('skill', '<span class="text-danger">:message</span>')}}
												</div>
											</div>	
										</div>										
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label class="col-md-4 control-label" for="International Experience">International Experience</label>
												<div class="col-md-8">
													{{ Form::select('country_worked[]',array(""=>"")+$country_worked, 0, array('id' => 'country_worked', 'class'=>'form-control chosen-select', 'multiple' => 'multiple', 'data-placeholder' => "Select the countries in which you have worked"))}}
						                    		{{ $errors->first('country_worked', '<span class="text-danger">:message</span>')}}
												</div>
											</div>	
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label class="col-md-4 control-label" for="AKDN Agency Experience">AKDN Agency Experience</label>
												<div class="col-md-8">
													{{ Form::select('consultant_agencies[]',array(""=>"")+$agencies, 0, array('id' => 'consultant_agencies', 'class'=>'form-control chosen-select', 'multiple' => 'multiple', 'data-placeholder' => 'Select AKDN agencies previously worked'))}}
						                    		{{ $errors->first('consultant_agencies', '<span class="text-danger">:message</span>')}}
												</div>
											</div>	
										</div>
									</div>
								</fieldset>
								<br/>
								<fieldset>
									<legend> <h4> Professional History</h4></legend> 	
									<label><h5><span class="text-danger">* </span> Please provide at least one of the following (LinkedIn Profile, Personal Website/Blog, or Resume/CV)</h5></label>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label class="col-md-4 control-label" for="LinkedIn">LinkedIn Profile</label>
												<div class="col-md-8">
							                      	{{ Form::text('linkedin_url', Input::old('linkedin_url'), array('id' => 'linkedin_url', 'class' => 'form-control', 'placeholder' => 'Enter LinkedIn profile')) }}
							                    	{{ $errors->first('linkedin_url', '<span class="text-danger">:message</span>')}}
												</div>
											</div>	
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label class="col-md-4 control-label" for="Personal website blog">Personal Website/Blog</label>
												<div class="col-md-8">
							                      	{{ Form::text('website_url', Input::old('website_url'), array('id' => 'website_url', 'class' => 'form-control', 'placeholder' => 'Enter website/blog URL')) }}
							                    	{{ $errors->first('website_url', '<span class="text-danger">:message</span>')}}
												</div>
											</div>	
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label class="col-md-4 control-label" for="resume">Upload Resume/CV </label>
												<div class="col-md-8">
													{{ Form::file('resume') }}
						                     		{{ $errors->first('resume', '<span class="text-danger">:message</span>')}}
												</div>
												<div class="col-md-8">
													<label><h5>(doc, docx or pdf format)</h5></legend>
												<div>
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
								                  		&nbsp; I agree to the
								                  		<a data-toggle="modal" href="javascript:void(0)" data-target="#consultantdb_terms">Terms and Conditions</a><span class="text-danger"> * </span>
								                	</label>
								                	{{ $errors->first('terms_conditions', '<span class="text-danger">:message</span>')}}
								                </div>
								            </div>    
								        </div>
								    </div>
								</fieldset>
								<fieldset>
									<legend><h5><span class="text-danger">* </span>You can save and finish your profile later before registering</h5></legend>
									<div class="row">
										<div class="col-md-12 text-center">
										<button type="button" class="btn btn-info btn-flat" onclick="SaveForLater()">Save</button>
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
	        </div>
	        <!-- /.modal-content -->
	    </div>
	    <!-- /.modal-dialog -->
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
	        </div>
	        <!-- /.modal-content -->
	    </div>
	    <!-- /.modal-dialog -->
	</div>
	<div class="modal modal-info" id="modal_filled_form_data">
		<div class="modal-dialog">
		    <div class="modal-content">
		        <div class="modal-header">
		            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
		            <h4 class="modal-title">Alert!</h4>
		        </div>
		        <div class="modal-body">
		            <p>There is some previously provided data for this page. Would you like to re-load it?</p>
		        </div>
		        <div class="modal-footer">
		            <button type="button" class="btn btn-outline pull-left" onclick="FillFormData();">Yes</button>
		            <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">No</button>
		        </div>
		    </div>
		    <!-- /.modal-content -->
		</div>
	</div>

	<div class="modal modal-warning" id="modal_no_form_data">
		<div class="modal-dialog">
		    <div class="modal-content">
		        <div class="modal-header">
		            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
		            <h4 class="modal-title">Alert!</h4>
		        </div>
		        <div class="modal-body">
		            <p>No information has been provided. Please provide some to be able to save.</p>
		        </div>
		        <div class="modal-footer">
		            <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Ok</button>
		        </div>
		    </div>
		    <!-- /.modal-content -->
		</div>
	</div>

	<div class="modal modal-success" id="modal_partial_data_saved">
		<div class="modal-dialog">
		    <div class="modal-content">
		        <div class="modal-header">
		            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
		            <h4 class="modal-title">Saved!</h4>
		        </div>
		        <div class="modal-body">
		            <p>Your filled data has been saved. You can always revisit this page later to complete your registration.</p>
		        </div>
		        <div class="modal-footer">
		            <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Ok</button>
		        </div>
		    </div>
		    <!-- /.modal-content -->
		</div>
	</div>
	<!-- /.modal-dialog -->
    <!-- jQuery 2.1.3 -->
    {{ HTML :: script ('assets/plugins/jQuery/jQuery-2.1.3.min.js') }}

    <!-- Bootstrap 3.3.2 JS -->
    {{ HTML :: script ('assets/bootstrap/js/bootstrap.min.js') }}
    
    <!-- iCheck -->
    {{ HTML :: script ('assets/plugins/iCheck/icheck.min.js') }}
    
    {{ HTML::script('assets/js/jquery-dynamic-form.js') }}
    {{ HTML::script('assets/js/jquery.serialize-object.js') }}
    {{ HTML::script('assets/js/jquery.populate.pack.js') }}
    
    {{ HTML::script('assets/js/canvasbg/EasePack.min.js' ) }}
    {{ HTML::script('assets/js/canvasbg/rAF.js' ) }}
    {{ HTML::script('assets/js/canvasbg/TweenLite.min.js' ) }}
    {{ HTML::script('assets/js/canvasbg/login.js' ) }}
    {{ HTML::script('assets/js/canvasbg/canvasbg.js') }}

	{{ HTML::script('assets/plugins/chosen/chosen.jquery.js') }}    
    <script type="text/javascript">

    var dynamic_languages;

    function FillFormData(){

    	var filled_form_data = localStorage.getItem("form_data");
    	filled_form_data = $.parseJSON(filled_form_data);

    	var languages = new Array();
    	
    	$.each(filled_form_data['languages']['languages'], function(key, val){
    		languages[key] = val;
    	});

    	dynamic_languages.inject( languages );

    	delete filled_form_data['languages'];
    	//console.log(filled_form_data);
    	//console.log(filled_form_data.languages);
    	$('#register_form').populate(filled_form_data, {phpIndices: true});
    	
    	// update chosen selection options
    	$('.chosen-select').trigger("chosen:updated");

    	// update icheck elements 
    	$('input[name="gender"]').iCheck('update');
   		$('#terms_conditions').iCheck('update');
   		
   		// setting up company name inputbox status 
   		changetextbox();

   		// setting up gender status 
   		setGenderStatus();
    	$('.modal').modal('hide');
    }
    
    function setGenderStatus(){

    	if($('#title').val() == 'Mr') {
        	$('#male').iCheck('check');
        	$("#female").iCheck('uncheck');

        }  else if($('#title').val() == 'Ms')  {
        	$('#female').iCheck('check');
        	$("#male").iCheck('uncheck');
        } else {
        	$("#male").iCheck('uncheck');
        	$("#female").iCheck('uncheck');
        }
    }

	//when consultant_type is Independent consultant,company_name will be Read only.
    function changetextbox()
	{
	    if (document.getElementById("consultant_type").value === "Independent consultant") {
	        $('#company_name').attr('readonly', true);
	        $('#company_span').hide();
	    } else {
	        $('#company_name').attr('readonly', false);
	       $('#company_span').show();
	    }
	}

	function SaveForLater(){

		if($('#register_form').isBlank()){

			localStorage.removeItem('form_data');
			$('#modal_no_form_data').modal('show');
		}else{

			$.extend(FormSerializer.patterns, {
				validate: /^[a-z][a-z0-9_-]*(?:\[(?:\d*|[a-z0-9_-]+)\])*$/i,
				key:      /[a-z0-9_-]+|(?=\[\])/gi,
				named:    /^[a-z0-9_-]+$/i
			});

			var filled_form_data = $('#register_form').serializeObject();
			//console.log(filled_form_data);
			localStorage.setItem("form_data", JSON.stringify(filled_form_data));

			$('#modal_partial_data_saved').modal('show');
		}
	}

	$.fn.isBlank = function() {
	    var fields = $('input[name!=_token]',this).serializeArray();

	    for (var i = 0; i < fields.length; i++) {
	        if (fields[i].value) {
	            return false;
	        }
	    }

	    return true;
	};

	jQuery(document).ready(function() {

		dynamic_languages = $("#language_row").dynamicForm("#plus_language", "#minus_language", {
	        limit:100,
	        formPrefix :'languages',
	        normalizeFullForm: false
	    });

		/*setInterval(function(){ 
			var filled_form_data = $('#register_form').serializeObject();
			console.log(filled_form_data);
			localStorage.setItem("form_data", JSON.stringify(filled_form_data));
			
		}, 10000);*/

		$('#consultantdb_terms').on('shown.bs.modal', function(e){
			$(e.target).find('.modal-body').load("{{ URL::route('consultant.terms') }}", function(){

				$(e.target).find('.modal-backdrop').height( $(e.target).find('.modal-body').height() + 150 );
			});
		})
  			

		// Init CanvasBG and pass target starting location
        CanvasBG.init({
            Loc: {
                x: window.innerWidth / 2,
                y: window.innerHeight / 6
            },
        });

		@if(Input::old('languages.languages') && count(Input::old('languages.languages')) > 0)
		dynamic_languages.inject( {{ json_encode(Input::old('languages.languages')) }} );
		//console.log( {{ json_encode(Input::old('languages.languages')) }} );
		@endif
		
		@if( Session::has('message') )
				
			@if("success" == Session::get('message_type'))
				$('#consultant_success').modal('show');
				localStorage.removeItem('form_data');
			@endif
			@if( 'danger' == Session::get('message_type') )
				$('#consultant_error').modal('show');
				localStorage.removeItem('form_data');
			@endif

		@else
			if ( localStorage.getItem("form_data") !== null ) {
				$('#modal_filled_form_data').modal('show');
			}
		@endif

		$('#consultant_success').on('hidden.bs.modal', function () {
			//open(location, '_self').close();
			setTimeout(function(){
				window.location = "{{ route('user.login.get') }}"},2000
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

        if ( ($(window).height() + 100) < $(document).height() ) {
		    $('#top-link-block').removeClass('hidden').affix({
		        // how far to scroll down before link "slides" into view
		        offset: {top:100}
		    });
		}

		$(document).on('change', '#title', function(){

	   		setGenderStatus();
		});
	});	

	</script>
</body>
</html>