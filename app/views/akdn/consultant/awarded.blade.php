@extends('akdn.layout')
@section('content')
<!------------------------------------------------------------------------------------------------------------------------------------------
	AKDN MER Consultant Database Version 1.0
	this file handles all the consultancies registered by a specific AKDN staff
-------------------------------------------------------------------------------------------------------------------------------------------->
<section class="content">
<div class="row">
	<div class="col-md-12">
		<div class="box box-primary box-solid">
			<div class="overlay">
			    <i class="fa fa-spin fa-spinner"></i>
			</div>
			<div class="box-header with-border">
				<h3 class="box-title">Registered Consultancies</h3>
				<label>(these are the consultancies that you have previously registered using this database. You can edit/update accordingly) </label>
				<div class="box-tools pull-right">
				</div>
			</div>
			<div class="box-body">
				<table id="dt_awarded_consultants" class="table table-bordered table-striped" cellspacing="0" width="100%">
                    <thead>
                        <tr role="row">
                            <th style=""></th>
                            <th style="">Surname</th>
                            <th style="">First/Given Names</th>
                            <th style="">Title of Project</th>
                            <th style="">AKDN Contact</th>
                            <th style="">AKDN Contact's Email</th>
                            <th style="">Start Date</th>
                            <th style="">End Date</th>
                            <th style="">Status</th>
                            <th style="">Action</th>
                        </tr>
                    </thead>
                     <thead>
                        <tr>
                            <!-- <th></th>
                            <th></th>
                            <th><input type="text" class="form-control" placeholder="Title of Project" name="title"></th>
                            <th><input type="text" class="form-control" placeholder="AKDN Contact" name="colfilter_manager_name"></th>
                            <th> <input type="text" class="form-control" placeholder="AKDN Contact's email" name="colfilter_manager_name"></th>
                            <th><input type="text" class="form-control" placeholder="start date" name="colfilter_start_date" style="width:100px;"> </th>
                            <th><input type="text" class="form-control" placeholder="End date" name="colfilter_end_date" style="width:100px;"></th>
                        	<th></th>
                        	<th></th> -->
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
			</div>
		</div>
	</div>
</div>
</section>

<!-- Edit Modal -->
<div class="modal" id="div-award-consultant">
	<div class="modal-dialog">
		<div class="modal-content box">
			<form class="form-horizontal" name="form_simple" id="frm-award-consultant" method="post" action="{{ URL::route('akdn.consultantassignment.update') }}">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">Edit Registered Consultancy</h4>
				</div>
				<div class="modal-body">
					<div class="callout callout-success" id="award-consultant-success" style="display:none">
						<p>You have successfully updated the registered consultancy details.</p>
					</div>
					<div class="callout callout-danger" id="award-consultant-danger" style="display:none">
						<p>There were some errors registering.</p>
					</div>
					<div class="form-group">
						<label for="name" class="control-label col-xs-4">Consultant Name</label>
						<div class="col-xs-8">
							<input type="text" name="other_names" class="form-control" id="aw-con-name" readonly="readonly" value="" />
							<input type="hidden" name="consultant_id" id="aw-con-consultant_id" value=""/>
							<input type="hidden" name="id" id="aw-con-consultant_id" value=""/>
							
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
						  <input type="text" name="start_date_dmy" class="form-control date-picker" id="aw-con-start_date" placeholder="Start Date">
						  <span class="help-inline error"></span>
						</div>
						<div class="col-md-4">
						  <input type="text" name="end_date_dmy" class="form-control date-picker" id="aw-con-end_date" placeholder="End Date">
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
							<input type="text" name="akdn_manager_email" class="form-control" id="aw-con-akdn_manager_email" placeholder="AKDN Contact's Email"/>
							<span class="help-inline error"></span>
						</div>
					</div>
				</div>

				<input name="consultant_assignment_id" type="hidden" value="" id="consultant_assignment_id">
				<div class="modal-footer">
					<button type="button" class="btn btn-default pull-left btn-flat" data-dismiss="modal">Cancel</button>
					<button type="submit" class="btn btn-primary btn-flat">Edit</button>
				</div>
			</form>
			<div class="overlay">
				<i class="fa fa-spin fa-spinner"></i>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- Delete Modal -->

<div id="modal-destroy-single" class="modal hide fade">
    <!-- dialog contents -->
    <div class="modal-body">Hello world!</div>
    <!-- dialog buttons -->
    <div class="modal-footer"><a href="#" class="btn primary" id="">OK</a></div>
</div>
@stop

@section('style')
{{ HTML::style('assets/plugins/datatables/dataTables.bootstrap.css') }}
{{ HTML::style('assets/plugins/iCheck/square/blue.css') }}
{{ HTML::style('assets/plugins/datepicker/datepicker3.css') }}
@stop

@section('script')

	{{ HTML::script('assets/plugins/datatables/jquery.dataTables.js') }}
	{{ HTML::script('assets/plugins/datatables/dataTables.bootstrap.js') }}
    {{ HTML :: script ('assets/plugins/iCheck/icheck.min.js') }}
	{{ HTML::script('assets/plugins/datepicker/bootstrap-datepicker.js') }}
	{{ HTML::script('assets/js/bootbox.min.js') }}
	{{ HTML::script('assets/js/donetyping.js') }}
	{{ HTML::script('assets/js/jquery.populate.pack.js') }}

    <script type="text/javascript">

    function editAwardedConsultancy(dtRow){
		
    	$('#div-award-consultant').modal('show');
    	$('#frm-award-consultant').populate(dtRow,{resetForm: true, debug: true});
    	$('.date-picker').datepicker('update');
    	//start_date_picker.datepicker('update');
    }

	$(document).on('click', '.popup-modal-dismiss', function (e) {

        e.preventDefault();
        $.magnificPopup.close();
    });

	jQuery(document).ready(function() {

		$('input[name="start_date_dmy"]').datepicker({ format : 'dd-mm-yyyy'}).on('changeDate', function(e){
			$('input[name="end_date_dmy"]').datepicker('setStartDate', e.date);
	    });

		$('input[name="end_date_dmy"]').datepicker({ format : 'dd-mm-yyyy'});

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
							dt_awarded_consultants.fnStandingRedraw();
							$('#div-award-consultant').modal('hide');

						});
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
         /* Awarded Consultant */
        dt_awarded_consultants = $('#dt_awarded_consultants').dataTable({
        	"oLanguage": {
		        "sEmptyTable": "No registered consultancies available"
		    },
        	"bPaginate" : true,
			"bLengthChange" : false,
			"bSort" : true,
			"bInfo" : true,
			"bAutoWidth" : true,
            "bProcessing" : false,
            "bServerSide" : true,
            "sAjaxSource" : "{{ URL::route('akdn.consultantassignment.index') }}",
            "aaSorting"   : [[1, "desc"]],
            "aoColumns"   : [
                {
                    mData     : "id",
                    sWidth    : "30px",
                    sClass    : 'text-center',
                    bVisible  : false,
                    bSortable : false
                },
                { 	mData : "surname",sClass   : 'text-left' },
                { 	mData : "other_names",sClass    : 'text-left' },
                { 	mData : "title_of_consultancy",sClass 	: "text-left", },
                { 	mData : "akdn_manager_name",sClass 	: "text-left", },
                { 	mData : "akdn_manager_email",sClass 	: "text-left", },
                { 	mData : "start_date_dmy",sClass 	: "text-center", },
                { 	mData : "end_date_dmy",sClass 	: "text-center", },
                { 	
                	mData : "status",
                	sClass 	: "text-center",
                	mRender: function(v,t,o){

                        if(v==1)
                        {
                            return "<span class='label label-success'> Complete</span>";
                        }else if(v==2){

                            return "<span class='label label-danger'>Terminated</span>";
                        }else if(v==3){
                            return "<span class='label label-warning'>On-going</span>";
                        }
                    } 

            	},
                {
                    sClass    :"text-right",
                    bSortable : false,
                    mData     : null,
                    mRender: function(v, t, o) {

                    	var data = JSON.stringify(o);
                    	var act_html    =   "<div class='btn-group pr5'>"
                                        +        "<button class='btn btn-sm btn-info btn-flat' type='button' onclick='editAwardedConsultancy("+ data +")'><span class='fa fa-edit'></span></button>"
                                        +        "<button id='awarded_consultancy_destroy_confirm' class='btn btn-sm btn-danger btn-flat ' type='button' value='"+o['id']+"'><span class='fa fa-remove'></span></button>"
                                        +   "</div>";

                        return act_html;
                    }
                }
            ],
            fnPreDrawCallback : function() { 
            	$("div.overlay").show();
            },
            fnDrawCallback : function (oSettings) {
                $("div.overlay").hide();
            },
        });

		$("div.overlay").hide();

		$('#frm-consultant-search').submit(function(){

        	dt_awarded_consultants.api().draw();
        	return false;
        });

	});

	$(document).on('click', '#awarded_consultancy_destroy_confirm', function() {
		var id = $(this).val();
		
	   bootbox.confirm("Are you sure you want to delete the registered consultancy? This action cannot be undone.", function(result) {

	        if( typeof id === 'undefined' ) return false;

	        if(result == true){

		        var ele = $('button.destroy_awarded_consultant[value="'+ id +'"]');

		        $.ajax({
		            url        : "{{URL::to('/')}}/" + 'akdn/consultantassignment/' +id,
		            type       : "DELETE",
		            data       : {id : id, name : 'destroy'},
		            dataType   : 'json',
		            beforeSend : function() {

		                ele.prop('disabled', true).html('<i class="fa fa-spin fa-spinner"></i>');
		            },
		            success    : function(resp) {

		                dt_awarded_consultants.fnStandingRedraw();
		            }
		        });
	    	} else {
	    		return true;
	    		dt_awarded_consultants.fnStandingRedraw();
	    	}

	    });
  		
	}); 
	
     
	$("#dt_awarded_consultants thead input").donetyping( function () {
        /* Filter on the column (the index) of this element */
        dt_awarded_consultants.fnFilter( this.value, $("#dt_awarded_consultants thead input").index(this));
    } );
    
    $("#dt_awarded_consultants thead input").focus( function () {
        if ( this.className == "form-control" )
        {
            //this.className = "";
            this.value = "";
        }
    });

</script>

@stop
