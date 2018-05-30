@extends('admin.layout')
@section('content')
<!------------------------------------------------------------------------------------------------------------------------------------------
	AKDN MER Consultant Database Version 1.0
	this file handles adding new skill
-------------------------------------------------------------------------------------------------------------------------------------------->
	<section class="content-header">
	    <h1>Add New Skill</h1>
	</section>

	<section class="content">
		<div class="content">
			<div class="row">
				<div class="col-md-12">
					<div class="box box-primary">
						<div class="box-header">
							{{Form::open(array('route'=>'admin.skill.store','method'=>'post','id'=>'akdn_form','files' => true ,'class'=>'form-horizontal')); }}
							<div class="box-body">
								@include('partials.alert')
								<div class="col-md-12">
									<div class="form-group">
										<label for="exampleInputEmail1" class="col-md-2 ">Skill Name<sup>*</sup></label>
										<div class="col-md-6">
											{{ Form::text('skills_des', Input::old('skills_des'), array('id' => 'skills_des', 'class' => 'form-control ', 'placeholder' => 'Enter Skill Name')) }}
											{{ $errors->first('skills_des', '<span class="help-inline">:message</span>')}}
										</div>
									</div>
								</div>
								<div class="col-md-10 col-md-offset-2">
									<button type="submit" id="frm-submit" class="btn btn-primary">Add Skill </button> 
									<a class="btn btn-info" href="{{URL::route('admin.skill.index')}}">Back to Skills List </a>
								</div>  
							</div>
						</div>         	
						{{Form::close();}}
						<div class="overlay" style="display:none;" id="frm-loader">
							<i class="fa fa-spinner fa-spin fa-lg"></i>
						</div>			
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