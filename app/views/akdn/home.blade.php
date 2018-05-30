@extends('akdn.layout')
@section('content')
<!------------------------------------------------------------------------------------------------------------------------------------------
	AKDN MER Consultant Database Version 1.0
	this file handles searching of relevant consultants by AKDN staff
-------------------------------------------------------------------------------------------------------------------------------------------->
<section class="content">
<div class="row">
	<div class="col-md-12">
		<form id="frm-consultant-search" class="form-horizontal">
			<div class="box box-primary box-solid">
				<div class="box-header with-border">
					<h3 class="box-title">Search for a consultant</h3>
					<label>(by one or more more of the following criteria) </label>
					<div class="box-tools pull-right">
						<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
					</div>
				</div>
				<div class="box-body">
					
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label class="col-md-4 control-label" for="surname">Consultant's Name </label>
								<div class="col-md-8">
									{{ Form::text('name', Input::old('name'), array('id' => 'name', 'class' => 'form-control', 'placeholder' => 'First/Given name, last name or full name')) }}
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="col-md-4 control-label" for="languages[]">Language(s) *</label>
								<div class="col-md-8">
									{{ Form::select('languages[]',array(""=>"") + $languages , '0', array('class'=>'form-control chosen-select', 'id' => 'language', 'multiple' => 'multiple', 'data-placeholder' => 'Choose the language(s) you are looking for'))}}
								</div>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label class="col-md-4 control-label" for="specialization[]">Thematic Area(s)</label>
								<div class="col-md-8">
									{{ Form::select('specialization[]',array(""=>"")+$specialization,0,array('id' => 'specialization', 'class'=>'form-control chosen-select', 'multiple' => 'true', 'data-placeholder' => 'Choose area(s) of expertise'))}}
								</div>
							</div>	
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="col-md-4 control-label" for="skills[]">Skill(s) </label>
								<div class="col-md-8">
									{{ Form::select('skill[]',array(""=>"")+$skills,'0',array('id' => 'skills', 'class'=>'form-control chosen-select', 'multiple' => 'true', 'data-placeholder' => 'Choose the skill(s) you are looking for'))}}
								</div>
							</div>	
						</div>	
						<div class="col-md-6">
							<div class="form-group">
								<label class="col-md-4 control-label" for="countries_worked[]">International Experience</label>
								<div class="col-md-8">
									{{ Form::select('countries_worked[]',array(""=>"")+$countries_worked, 0, array('id' => 'country_worked', 'class'=>'form-control chosen-select', 'multiple' => 'multiple', 'data-placeholder' => 'Country(ies) where the consultant has previously worked'))}}
								</div>
							</div>	
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="col-md-4 control-label" for="countries_worked[]">Consultant Type</label>
								<div class="col-md-8">
									{{ Form::select('consultant_type',array(""=>"",'Independent Consultant'=>'Independent Consultant','Institution-affiliated'=>'Institution-affiliated','Both independent and Institution-affiliated'=>'Both independent and Institution-affiliated'), 0, array('id' => 'consultant_type', 'class'=>'form-control chosen-select', 'data-placeholder' => 'Consultant Type'))}}
								</div>
							</div>	
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="col-md-4 control-label" for="countries_worked[]">Last Consultancy Within</label>
								<div class="col-md-8">
									{{ Form::select('last_consultancy',array(""=>"","1"=>"1 Year","3"=>"3 Years","5"=>"5 Years"), 0, array('id' => 'last_consultancy', 'class'=>'form-control chosen-select', 'data-placeholder' => 'Last Consultancy Within'))}}
								</div>
							</div>	
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="col-md-4 control-label" for="countries_worked[]"></label>
								<div class="col-md-8">
									<label><h5>* Returns results for any level of proficiency or ability area</h5></label>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
					</div>
				</div>
				<div class="box-footer">
					<div class="text-right">
						<button class="btn btn-primary btn-flat"><i class="fa fa-search"></i> Search</button>
						<a class="btn btn-primary btn-flat" href="{{ URL::route('akdn.home') }}" id="frm-search-reset"><i class="fa fa-refresh"></i> Reset</a>
					</div>
				</div>
				<div class="overlay" style="display:none;">
					<i class="fa fa-spinner fa-spin"></i>
		        </div>
			</div>
		</form>
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<div class="box no-border">
			<div class="box-header">
				<div class="col-md-12">
					<div class="col-md-4">
						<h3 class="box-title">Consultant Search Results</h3>
					</div>
					<div class="col-md-4">
						<h5>Your search criteria returned <span id="search_criteria"></span> consultants</h5>
					</div>
				</div>
				<div class="box-tools pull-right">
					<a target="_blank" id="export_excel" href="javascript:void(0);" class="btn btn-sm btn-flat btn-primary" onclick="return ExportToExcel(this);"><i class="fa fa-file-excel-o"></i> Export To Excel</a>
				</div>
			</div><!-- /.box-header -->
			<div class="box-body">
				<table id="dt_consultants" class="table-condensed table" cellspacing="0" width="100%">
                    <thead>
                        <tr role="row">
                            <th style="width:1%"><i class="fa fa-search-plus"></i></th>
                            <th style="width:15%; text-align:left">Surname</th>
                            <th style="width:15%; text-align:left">First/Given Name(s)</th>
                            <th style="width:20%">Primary Email</th>
                            <!-- <th style="width:10%">Tel. No.</th> -->
                            <th style="width:10%">LinkedIn <br/>Profile</th>
                            <th style="width:5%">Website/ Blog</th>
							<th style="width:1%">Resume/ CV</th>
                            <th style="width:1%">Previous Consultancies</th>
                            <th style="width:1%">Invited By</th>
                            <th style="width:5%">Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
			</div>
			<div class="overlay">
                <i class="fa fa-spin fa-spinner"></i>
            </div>
		</div>
	</div>
</div>
</section>
@stop

@section('modal')

<div class="modal" id="div-award-consultant">
	<div class="modal-dialog">
		<div class="modal-content box">
			<form class="form-horizontal" id="frm-award-consultant" method="post" action="{{ URL::route('akdn.consultantassignment.store') }}">
				
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">Register a consultancy</h4>
				</div>
				<div class="modal-body">
					<div class="callout callout-success" id="award-consultant-success" style="display:none">
						<p>You have successfully registered a consultancy.</p>
					</div>
					<div class="callout callout-danger" id="award-consultant-danger" style="display:none">
						<p>There were some errors.</p>
					</div>
					<div class="form-group">
					
						<label for="name" class="control-label col-xs-4">Consultant's Name</label>
						<div class="col-xs-8">
							<input type="text" name="name" class="form-control" id="aw-con-name" readonly="readonly" />
							<input type="hidden" name="consultant_id" id="aw-con-consultant_id" value=""/>
							<span class="help-inline error"></span>
						</div>
					</div>
					<div class="form-group">
						<label for="title_of_consultancy" class="control-label col-xs-4">Title of Project</label>
						<div class="col-xs-8">
							<textarea name="title_of_consultancy" class="form-control" id="aw-con-title_of_consultancy" placeholder="Title of Project"></textarea>
							<span class="help-inline error"></span>
						</div>
					</div>
					<div class="form-group">
						<label for="inputPassword" class="control-label col-md-4">Duration</label>
						<div class="col-md-4">
						  <input type="text" name="start_date" class="form-control date-picker" id="aw-con-start_date" placeholder="Start Date">
						  <span class="help-inline error"></span>
						</div>
						<div class="col-md-4">
						  <input type="text" name="end_date" class="form-control date-picker" id="aw-con-end_date" placeholder="End Date">
						  <span class="help-inline error"></span>
						</div>
					</div>
					<div class="form-group">
						<label for="akdn_manager_name" class="control-label col-xs-4">AKDN Contact</label>
						<div class="col-xs-8">
							<input type="text" name="akdn_manager_name" class="form-control" id="aw-con-akdn_manager_name" placeholder="AKDN Contact"/>
							<span class="help-inline error"></span>
						</div>
					</div>
					<div class="form-group">
						<label for="akdn_manager_email" class="control-label col-xs-4">AKDN Contact's Email</label>
						<div class="col-xs-8">
							<input type="text" name="akdn_manager_email" class="form-control" id="aw-con-akdn_manager_email" placeholder="AKDN's Contact's Email"/>
							<span class="help-inline error"></span>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default pull-left btn-flat" data-dismiss="modal">Cancel</button>
					<button type="submit" class="btn btn-primary btn-flat">Register</button>
				</div>
			</form>
			<div class="overlay">
				<i class="fa fa-spin fa-spinner"></i>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div id="modal-consultant-profile" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Consultant Profile</h4>
			</div>
			<div class="modal-body box no-border">
				<div id="modal-consultant-profile-info"></div>
				<div class="overlay" id="modal-consultant-profile-loader">
					<i class="fa fa-spin fa-spinner"></i>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal" id="div-password">
    <div class="modal-dialog">
        <div class="modal-content box">
            <form class="form-horizontal" name="" id="frm-password" method="post" action="{{ URL::route('akdn.changepassword.post') }}">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Change Password</h4>
                </div>
                <div class="modal-body">
                    <div class="callout callout-success" id="password-success" style="display:none">
                        <p>Success! Your password has been changed successfully.</p>
                    </div>
                    <div class="callout callout-danger" id="password-danger" style="display:none">
                        <p>There were some errors.</p>
                    </div>
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="">Current Password <span class="text-danger">*</span></label>
                        <div class="col-md-8">
                            {{ Form::password('old_password', array('id' => 'aw-con-old_password', 'class' => 'form-control', 'placeholder' => 'Enter the current password')) }}
                            <span class="help-inline text-danger error"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="">New password <span class="text-danger">*</span></label>
                        <div class="col-md-8">
                            {{ Form::password('password', array('id' => 'aw-con-password', 'class' => 'form-control', 'placeholder' => 'Enter the new password')) }}
                            <span class="help-inline text-danger error"></span>
                        </div>
                    </div>
                
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="">Confirm Password <span class="text-danger">*</span></label>
                        <div class="col-md-8">
                            {{ Form::password('password_confirmation', array('id' => 'aw-con-password_confirmation', 'class' => 'form-control', 'placeholder' => 'Confirm the new password')) }}
                            <span class="help-inline text-danger error"></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left btn-flat" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-flat">Edit</button>
                </div>
            </form>
            <div class="overlay" id="password_spin" style="display:none;">
                <i class="fa fa-spin fa-spinner"></i>
            </div>
        </div>
    </div>
</div>

@stop

@section('style')

{{ HTML::style('vendorlib/plugins/datatables/media/css/dataTables.bootstrap.css') }}
<!-- iCheck -->
{{ HTML::style('assets/plugins/iCheck/square/blue.css') }}
<!-- date picker -->
{{ HTML::style('assets/plugins/datepicker/datepicker3.css') }}
{{ HTML::style('assets/plugins/rating/css/jquery.raty.css') }}
@stop

@section('script')

    <!-- iCheck -->
{{ HTML::script ('assets/plugins/iCheck/icheck.min.js') }}
{{ HTML::script('vendorlib/plugins/datatables/media/js/jquery.dataTables.min.js') }}
{{ HTML::script('vendorlib/plugins/datatables/media/js/dataTables.bootstrap.js') }}
<!-- date picker -->
{{ HTML::script('assets/plugins/datepicker/bootstrap-datepicker.js') }}
{{ HTML::script('assets/plugins/rating/js/jquery.raty.js') }}

    <script type="text/javascript">

   


	// To maintain server post params for export excel
	var server_params = '';

    function ExportToExcel(ele){

		var query_string = decodeURIComponent($.param(server_params));
		$(ele).attr('href','{{ URL::route("akdn.constant.excelexport") }}?'+query_string);
		return true;
	}

    function awardConsultancy(dtRow){

    	if (dtRow.other_names == '' || dtRow.other_names == null) {
    		var name = dtRow.surname;
    	} else {

    		var name = dtRow.other_names + " " + dtRow.surname;
    	}

    	$('#frm-award-consultant').find('input[name="name"]').val(name);
    	$('#aw-con-consultant_id').val(dtRow.id);
    	$('#div-award-consultant').modal('show');
    }

    function showProfile(id){

    	$('#modal-consultant-profile-loader').show();
    	$('#modal-consultant-profile').modal('show');

    	$.post( "{{ URL::route('akdn.constant.quickview') }}", { id: id }, function(resp) {
			$('#modal-consultant-profile-loader').hide();
			$('#modal-consultant-profile-info').html(resp);
		});
    }

    function PreviousConsultancies(id){

    	$.ajax({
                type:"get",
                url: "{{URL::action('AkdnAssignmentController@index')}}",
                data:{"id" : id},
                dataType:"html",
				beforeSend: function(){
					// form.next('div.overlay').show();
				},
				complete: function(){
					// form.next('div.overlay').hide();
				},
				success: function(resp){
					if(resp.success==true){
						alert('yes');
					}else{
						alert('no');
					}
				}
			});
			
    }


	jQuery(document).ready(function() {

		$('#rating_click').raty({
			path : "{{ asset('assets/plugins/rating/images') }}",
			click: function(score, evt) {
				$('#input_rating').val(score);
			}
		});

		$('#frm-search-reset').click(function(){

			$('#frm-award-consultant')[0].reset();
			$(this).html('<i class="fa fa-spinner fa-spin"></i> Wait');
		});

		$('#frm-award-consultant').submit(function(){
		
			form = $(this);
			form.ajaxSubmit({
				beforeSend: function(){
					form.next('div.overlay').show();
				},
				complete: function(){
					form.next('div.overlay').hide();
				},
				success: function(resp){

					form.find('.callout').hide();
					form.find('span.error').text('');

					if(resp.success){
						$('#frm-award-consultant')[0].reset();
						$('#award-consultant-success').show().delay(3000).fadeOut('slow', function(){
							$('#div-award-consultant').modal('hide');
						});
						location.reload();
					}else{
						$('#award-consultant-danger').show().delay(3000).fadeOut();
						$.each(resp.errors, function(key, val){
							form.find('#aw-con-'+key).closest('div').find('span.error').text(val[0]);
						});
					}
				}
			});
			
			return false;
		});


		$('#div-award-consultant').on('hidden.bs.modal', function () {
			$('#frm-award-consultant')[0].reset();
			$('#aw-con-consultant_id').val('');

			$('#frm-award-consultant').find('span.error').text('');
			$('.callout').hide();
		});

		$('input[name="start_date"]').datepicker({ format : 'dd-mm-yyyy'}).on('changeDate', function(e){
			$('input[name="end_date"]').datepicker('setStartDate', e.date);
	    });

		$('input[name="end_date"]').datepicker({ format : 'dd-mm-yyyy'});

	    $('input[name="gender"]').iCheck({ radioClass: 'iradio_square-blue' });


        $('.chosen-select').chosen();

        dt_consultants = $('#dt_consultants').dataTable({
        	"oLanguage": {
		        "sEmptyTable": "No results found"
		    },
        	"bPaginate" : true,
			"bLengthChange" : false,
			"bFilter" : false,
			"bSort" : true,
			"bInfo" : true,
			"bAutoWidth" : false,
            "bProcessing" : false,
            "bServerSide" : true,
            "sAjaxSource" : "{{ URL::route('akdn.consultant.search') }}",
	        "fnServerParams": function ( aoData ) {
	        	
		    	var form_data = $('#frm-consultant-search').serializeArray();
			    $.each(form_data, function(i, val){
		    		aoData.push(val);
			    });
			    server_params = aoData;
		    },
            "aaSorting"   : [[3, "desc"]],
            "aoColumns"   : [
                {
                    mData     : "id",
                    sWidth    : "30px",
                    sClass    : 'text-center',
                    bVisible  : true,
                    bSortable : false,
                    mRender   : function (v, t, o) {

                       //return '<input type="checkbox" id="chk_'+v+'" name="id[]" value="'+v+'" class="tickbox">';
                       return '<button type="button" id="chk_'+v+'" name="id[]" value="'+v+'" class="btn btn-xs btn-flat btn-primary" onclick="showProfile('+v+')"><i class="fa fa-search-plus"></i></button>';
                    }
                },
                { 	mData : "surname",sClass    : 'text-left' },
                { 	mData : "other_names",sClass    : 'text-left' },
                { 	mData : "email",sWidth 	: "50px",bSortable : false },
                //{ 	mData : "telno",bSortable : false },
                { 
                    mData : "linkedin_url",
                    "bSortable": false,
                    sClass    :"text-center",
                    mRender: function(v,t,o){
                        if(v !=""){
                            return '<a href="'+ v +'" class="btn btn-primary btn-xs btn-flat" target="_blank"><i class="fa fa-linkedin"></i></a>'
                        }else{
                            return " ";
                        }
                    }
                },
                { 
                    mData : "website_url",
                    sClass    :"text-center",
                    "bSortable": false,
                    mRender: function(v,t,o){
                        if(v !=""){
                        	if(v.indexOf("http://") < 0){
   								v = "http://" + v;
							}
                            return '<a href="'+ v +'" class="btn btn-primary btn-xs btn-flat" target="_blank"><i class="fa fa-globe"></i></a>'
                        }
                        else{
                            return " ";
                        }
                    }
                },
				{ 
                    "mData": "resume",
                    "bSortable": false,
                    sClass    :"text-center",
                    mRender: function(v,t,o){
                        if(v!== '')
                        {  
                            return '<a href="{{ URL::to("/upload/resume") }}/'+v+' " class="btn btn-success btn-xs btn-flat" target="_blank"><i class="fa fa-download"></i></a>';
                        }
                        else{
                            return "  ";
                        }
                    }
                },
                {
                	mData     : "id",
                    sWidth    : "30px",
                    sClass    : 'text-center',
                    bVisible  : true,
                    bSortable : false,
                    mRender   : function (v, t, o) {
                    	if(o['c']!==null){

                       		return "<a href='{{URL::to('/')}}/akdn/assignment/"+v+" ' class='btn btn-xs  btn-info btn-flat' data-toggle='tooltip' title='Previous Consultancies' data-placement='top'>View</a>";
                    		
                    	}	
                    	return "None";
                    }

                },
                { 	mData     : "invited_on_behalf",
                	sClass    : 'text-left',
                	bSortable : false, 
                	mRender: function(v, t, o) {

                		if (v==null || v=='') {
                			return o.full_name;
                		}
                		else
                		{
                			return v;
                		}
                    }
                },
                {
                    sWidth    : "120px",
                    sClass    :"text-right",
                    bSortable : false,
                    mData     : null,
                    mRender: function(v, t, o) {

                    	var data = JSON.stringify(o);
                        var act_html    =   "<button class='btn btn-xs  btn-primary btn-flat' type='button' onclick='awardConsultancy("+ data +")'><span class='fa fa-check'></span> Register Consultancy</button>";

                        return act_html;
                    }
                }
            ],
            fnPreDrawCallback : function() { 
            	$("div.overlay").show();
            },

            fnDrawCallback : function (oSettings) {

            	var search_criteria =  dt_consultants.fnSettings()._iRecordsTotal;
            	console.log(search_criteria);
            	$('#search_criteria').text(search_criteria);


                $("div.overlay").hide();
                
				$('div.rating').raty({
					path : "{{ asset('assets/plugins/rating/images') }}",
					readOnly: true,
					score: function() {
					    return $(this).attr('data-score');
					}
				});
            }
        });

		$("div.overlay").hide();

		$('#frm-consultant-search').submit(function(){

        	dt_consultants.api().draw();
        	return false;
        }); 
	});	
	
	</script>

@stop
