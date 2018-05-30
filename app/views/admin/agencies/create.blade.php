@extends('admin.layout')
@section('content')
<!------------------------------------------------------------------------------------------------------------------------------------------
	AKDN MER Consultant Database Version 1.0
	this file handles awarding consultancies from the admin module
-------------------------------------------------------------------------------------------------------------------------------------------->
<section class="content-header">
    <h1>Add New Agency</h1>
</section>
<section class="content">
	<div class="content">
		<div class="row">
			<div class="col-md-12">
			<div class="box box-primary">
					<div class="box-header">
						{{Form::open(array('route'=>'admin.agencies.store','method'=>'post','id'=>'agencies_form','files' => true ,'class'=>'form-horizontal')); }}
							<div class="box-body">
								@include('partials.alert')
								<div class="form-group">
										
									<div class="form-group">
										<label for="akdn_manager_name" class="control-label col-xs-4">Agency Acronym</label>
										<div class="col-xs-6">
											<input type="text" name="acronym" class="form-control" id="acronym" placeholder="Acronym of Agency"/>
											{{ $errors->first('acronym', '<span class="help-inline">:message</span>')}}
										</div>
									</div>

									<div class="form-group">
										<label for="fullname" class="control-label col-xs-4">Agency Full Name</label>
										<div class="col-xs-6">
											<input type="text" name="fullname" class="form-control" id="fullname" placeholder="Full Name of Agency"/>
											{{ $errors->first('fullname', '<span class="help-inline">:message</span>')}}
										</div>
									</div>
								</div>
								<hr>
								<div class="col-md-8 col-md-offset-4"> 
									<button type="submit" id="frm-submit" class="btn btn-primary">Add Agency</button> 
				        			<a class="btn btn-info" href="{{URL::route('admin.agencies.index')}}">Back to Agencies </a>  
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
	 
	</script>
	
@stop