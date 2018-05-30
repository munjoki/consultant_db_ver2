@extends('admin.layout')
@section('content')
<!------------------------------------------------------------------------------------------------------------------------------------------
	AKDN MER Consultant Database Version 1.0
	this file renders the existing consultants from the admin module
-------------------------------------------------------------------------------------------------------------------------------------------->
<section class="content-header">
    <h1>{{ ucfirst($user->consultant->surname); }}'s Profile  <small> Detailed View</small></h1>
</section>

<section class="content">

@include('partials.alert')
<div class="box">
	<div class="register-box-body">
		<div class="row">
			<div class="col-md-12">
				<fieldset>
					<legend> <h4> <i class="fa fa-user"></i> Personal Details </h4></legend> 
					<div class="row">
						<div class="col-md-6">
							<table class="table table-bordered">
								<tr>
									<td style="width:30%">Surname</td>
									<td>{{ $user->consultant->surname; }}</td>
								</tr>
								<tr>
									<td>Other Names</td>
									<td>{{ $user->consultant->other_names; }}</td>
								</tr>
								<tr>
									<td>Nationality(ies)</td>
									<td>
										@if(count($nationalities) > 0)
										<span class="label label-info tags"> {{ implode('</span> <span class="label label-info tags">', $nationalities); }} </span>
										@endif	
									</td>
								</tr>
								<tr>
									<td>Gender</td>
									<td>{{ (1 == $user->consultant->gender) ? 'Male' : 'Female' }}</td>
								</tr>
								<tr>
									<td>Consultant Type</td>
									<td>{{ $user->consultant->consultant_type; }}</td>
								</tr>
								<tr>
									<td>Institution Name</td>
									<td>{{ $user->consultant->company_name; }}</td>
								</tr>
							</table>
						</div>
					</div>
				</fieldset>
				<br/>
				<fieldset>
					<legend> <h4> <i class="fa fa-envelope"></i> Contact Details</h4></legend> 
					<div class="row">
						<div class="col-md-6">
							<table class="table table-bordered">
								<tr>
									<td style="width:30%">Primary Email</td>
									<td>{{ $user->email; }}</td>
								</tr>
								<tr>
									<td>Alternate Email</td>
									<td>{{ $user->consultant->alternate_email; }}</td>
								</tr>
								<tr>
									<td>Primary Tel No.</td>
									<td>{{ $user->consultant->telno; }}</td>
								</tr>
								<tr>
									<td>Skype Id</td>
									<td>{{ $user->consultant->skypeid; }}</td>
								</tr>
							</table>
						</div>
					</div>
				</fieldset>
				<br/>
				<fieldset>
					<legend> <h4> <i class="fa fa-mortar-board"></i> Skills and Competencies</h4></legend> 
					<div class="row">
						<div class="col-md-6">
							<table class="table table-bordered">
								<tr>
									<td  style="width:30%">Language Details </td>
									<td>
									@if($languages)
										<table class="table table-bordered text-center">
											<tr>
												<th>Language</th>
												<th>Speaking Level</th>
												<th>Writing Level</th>
												<th>Reading Level</th>
												<th>Understanding Level</th>
											</tr>
										@foreach($languages as $language)
											<tr>
												<td>{{ ucfirst($language['lang_des']) }}</td>
												<td>{{ ucfirst($language['speaking_level']) }}</td>
												<td>{{ ucfirst($language['writing_level']) }}</td>
												<td>{{ ucfirst($language['reading_level']) }}</td>
												<td>{{ ucfirst($language['understanding_level']) }}</td>
											</tr>
										@endforeach
										</table>
									@endif
									</td>
								</tr>
								<tr>
									<td>Thematic Area(s)</td>
									<td>
										@if(count($specialization) > 0)
										<span class="label label-info tags"> {{ implode('</span> <span class="label label-info tags">', $specialization); }} </span>
										@endif
									</td>
								</tr>
								<tr>
									<td>Skill(s)</td>
									<td><span class="label label-info tags"> {{ implode('</span> <span class="label label-info tags">', $skills); }} </span></td>
								</tr>
								
								<tr>
									<td>International Experience</td>
									<td>
										@if(count($workedcountries) > 0)
										<span class="label label-info tags"> {{ implode('</span> <span class="label label-info tags">', $workedcountries); }} </span>
										@endif
									</td>
								</tr>
								
								<tr>
									<td>AKDN Agency Experience</td>
									<td>
										@if(count($agencies) > 0)
										<span class="label label-info tags"> {{ implode('</span> <span class="label label-info tags">', $agencies); }} </span>
										@endif
									</td>
								</tr>
								<tr>
									<td>LinkedIn Profile</td>
									<td>
										@if($user->consultant->linkedin_url != "")
										<a href="{{ $user->consultant->linkedin_url }}" class="btn btn-primary btn-flat  btn-xs" target="_blank"><i class="fa fa-linkedin"></i> LinkedIn Profile</a>
										@else
										-
										@endif
									</td>
								</tr>
								<tr>
									<td>Personal Website/Blog</td>
									<td>
										@if($user->consultant->website_url != "")
										<a href="{{ $user->consultant->website_url }}" class="btn btn-primary btn-flat btn-xs" target="_blank"><i class="fa fa-globe"></i> Personal website/blog</a>
										@else
										-
										@endif
										
									</td>
								</tr>
								<tr>
									<td>Resume</td>
									<td>
										@if($user->consultant->resume != "")
										<a href="{{ URL::route('user.resume.download') }}" target="_blank" class="btn btn-success btn-flat btn-xs"><i class="fa fa-download"></i> Download Resume</a>
										@endif
									</td>
								</tr>
								<tr>
									<td>Privious Consutancies</td>
									<td>
										@if($privious_consutancies[0] != "" and $privious_consutancies[0] != null)
											<a href='{{URL::route("admin.showPreviousConsultancies",array($user->id))}}' class='btn btn-xs  btn-info btn-flat' data-toggle='tooltip' title='Previous Consultancies' data-placement='top'>View</a>
										@else
											None
										@endif

									</td>
								</tr>
							</table>
						</div>
					</div>
				</fieldset>
				<a href="{{ URL::route('admin.user.index') }}" id="frm-cancel" class="btn btn-primary btn-flat">Back to Consultants List</a>
			</div>
		</div>
		
	</div>
	<div class="overlay" style="display:none;" id="frm-loader">
		<i class="fa fa-spinner fa-spin fa-lg"></i>
    </div>
</div>
</section><!-- /.content -->
@stop

@section('script')
<script type="text/javascript">
$(document).ready(function(){
	$('#btn-edit-profile').click(function(){
    	$(this).html('<i class="fa fa-spin fa-spinner"></i> Please wait...');
    	$('#frm-loader').show();
    });
})
</script>
@stop

@section('style')
<style type="text/css">
table tr td:first-child{
	font-weight: bold;
	text-align: right;
}

table table tr td:first-child{
	font-weight: normal;
	text-align: center;
}
</style>
@stop