@extends('admin.layout')
@section('content')
<!------------------------------------------------------------------------------------------------------------------------------------------
	AKDN MER Consultant Database Version 1.0
	this file handles awarding consultancies from the admin module
-------------------------------------------------------------------------------------------------------------------------------------------->
<section class="content-header">
    <h1>Registered Consultancies</h1>
</section>
<section class="content">
	<div class="content">
		<div class="row">
			<div class="col-md-12">
			<div class="box box-primary">
					<div class="box-header">
						{{Form::open(array('route'=>'admin.awarded.store','method'=>'post','id'=>'akdn_form','files' => true ,'class'=>'form-horizontal')); }}
							<div class="box-body">
								@include('partials.alert')
								<div class="form-group">
										<label for="name" class="control-label col-xs-4">Consultant's Name</label>
										<div class="col-xs-8">
					                    	{{ Form::select('consultant_id',array(""=>"")+$consultant, '0',array( 'id' => 'consultant','class'=>'form-control chosen-select'))}}
											{{ $errors->first('consultant_id', '<span class="help-inline">:message</span>')}}
										</div>
									</div>
									<div class="form-group">
										<label for="title_of_consultancy" class="control-label col-xs-4">Title of Project</label>
										<div class="col-xs-8">
											<textarea name="title_of_consultancy" class="form-control" id="aw-con-title_of_consultancy" placeholder="Title of Project"></textarea>
											{{ $errors->first('title_of_consultancy', '<span class="help-inline">:message</span>')}}
										</div>
									</div>
									<div class="form-group">
										<label for="inputPassword" class="control-label col-md-4">Duration</label>
										<div class="col-md-4">
										  <input type="text" name="start_date" class="form-control date-picker" id="aw-con-start_date" placeholder="Start Date">
											{{ $errors->first('start_date', '<span class="help-inline">:message</span>')}}
										</div>
										<div class="col-md-4">
										  <input type="text" name="end_date" class="form-control date-picker" id="aw-con-end_date" placeholder="End Date">
											{{ $errors->first('end_date', '<span class="help-inline">:message</span>')}}
										</div>
									</div>
									<div class="form-group">
										<label for="akdn_manager_name" class="control-label col-xs-4">AKDN Contact Name</label>
										<div class="col-xs-8">
											<input type="text" name="akdn_manager_name" class="form-control" id="aw-con-akdn_manager_name" placeholder="AKDN Contact Name"/>
											{{ $errors->first('akdn_manager_name', '<span class="help-inline">:message</span>')}}
										</div>
									</div>
									<div class="form-group">
										<label for="akdn_manager_email" class="control-label col-xs-4">AKDN Contact Email</label>
										<div class="col-xs-8">
											<input type="text" name="akdn_manager_email" class="form-control" id="aw-con-akdn_manager_email" placeholder="AKDN Contact Email"/>
											{{ $errors->first('akdn_manager_email', '<span class="help-inline">:message</span>')}}
										</div>
									</div>
									<div class="form-group">
										<label for="name" class="control-label col-xs-4">Registered By</label>
										<div class="col-xs-8">
					                    	{{ Form::select('akdn_id',array(""=>"")+$akdn, '0',array( 'id' => 'akdn','class'=>'form-control chosen-select'))}}
											{{ $errors->first('akdn_id', '<span class="help-inline">:message</span>')}}
											
										</div>
									</div>
								</div>
								<div class="col-md-8 col-md-offset-4"> 
									<button type="submit" id="frm-submit" class="btn btn-primary">Register Consultant</button> 
				        			<a class="btn btn-info" href="{{URL::route('admin.awarded.index')}}">Back to Registered Consultancies </a>  
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

		$('input[name="start_date"]').datepicker({ format : 'dd-mm-yyyy'}).on('changeDate', function(e){
			$('input[name="end_date"]').datepicker('setStartDate', e.date);
	    });

		$('input[name="end_date"]').datepicker({ format : 'dd-mm-yyyy'});

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