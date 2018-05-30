@extends('admin.layout')
@section('content')
<!------------------------------------------------------------------------------------------------------------------------------------------
	AKDN MER Consultant Database Version 1.0
	this file handles adding a new language
-------------------------------------------------------------------------------------------------------------------------------------------->
	<section class="content-header">
	    <h1>Add New Language</h1>
	</section>

	<section class="content">
		<div class="content">
			<div class="row">
				<div class="col-md-12">
				<div class="box box-primary">
						<div class="box-header">
							{{Form::open(array('route'=>'admin.language.store','method'=>'post','id'=>'akdn_form','files' => true ,'class'=>'form-horizontal')); }}
								<div class="box-body">
									@include('partials.alert')
									<div class="col-md-12">
										<div class="form-group">
											<label for="exampleInputEmail1" class="col-md-4 ">Language Name <sup>*</sup></label>
						                    <div class="col-md-6">
						                    	{{ Form::text('lang_acr', Input::old('lang_acr'), array('id' => 'lang_acr', 'class' => 'form-control ', 'placeholder' => 'Enter Language Name')) }}
												{{ $errors->first('lang_acr', '<span class="help-inline">:message</span>')}}
											</div>
										</div>
									</div>
									<div class="col-md-12">
										<div class="form-group">
											<label for="exampleInputEmail1" class="col-md-4 ">Language Description<sup>*</sup></label>
						                    <div class="col-md-6">
						                    	{{ Form::text('lang_des', Input::old('lang_des'), array('id' => 'lang_des', 'class' => 'form-control ', 'placeholder' => 'Enter Language Description')) }}
												{{ $errors->first('lang_des', '<span class="help-inline">:message</span>')}}
											</div>
										</div>
									</div>
									<div class="col-md-8 col-md-offset-4"> 											
												<button type="submit" id="frm-submit" class="btn btn-primary">Add Language </button> 
							        			<a class="btn btn-info" href="{{URL::route('admin.language.index')}}">Back to Languages List </a>  
							            	</div>
								        </div>  
							        </div>
									<div class="overlay" style="display:none;" id="frm-loader">
										<i class="fa fa-spinner fa-spin fa-lg"></i>
						            </div>
							    </div>         	
								{{Form::close();}}
							</div>
						</div>
					</div>	
				</div>
			</div>
	</section>	
@stop

@section('script')
	<script type="text/javascript">
	jQuery(document).ready(function() {

		$('.chosen-select').chosen();

        $('#frm-submit').click(function(){
        	$(this).html('<i class="fa fa-spin fa-spinner"></i> Please wait...');
        	$('#frm-loader').show();
        	window.location.reload(true);
        });

        
        $('form').submit(function(){
        	//function loadingBtn(button);
        });

    });    
	</script>
	
@stop