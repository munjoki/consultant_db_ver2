@extends('admin.layout')
@section('content')
<!------------------------------------------------------------------------------------------------------------------------------------------
    AKDN MER Consultant Database Version 1.0
    this file handles viewing of awarded consultancies and performing some quick actions (edit/delete)
-------------------------------------------------------------------------------------------------------------------------------------------->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">Registered Consultancies</h3>
                    <div class="box-tools pull-right">
                        <div class="box-tools pull-right">
                            @if(CheckPermission::isPermitted('admin.awarded.create'))
                            <a href="{{URL::route('admin.awarded.create')}}" class="btn btn-primary btn-default">Register New Consultancy</a>
                            @endif
                            @if(CheckPermission::isPermitted('admin.awarded.delete'))
                            <a id="adminuser_destroy_confirm" class="btn btn-primary">Delete All</a>
                            @endif
                            @if(CheckPermission::isPermitted('admin.awarded.excelexport'))
                            <a target="_blank" id="export_excel" href="javascript:void(0);" class="btn btn-sm btn-flat btn-primary" onclick="return ExportToExcel(this);"><i class="fa fa-file-excel-o"></i> Export To Excel</a>
                            @endif
                            <a href="javascript:void(0)" class="btn btn-default btn-sm dark" title="Refresh" onclick="javascript:dt_awarded.fnStandingRedraw();"><i class="fa fa-refresh"></i></a>
                        </div>
                    </div>
                </div>
                <div class="box-body">
                    <table id="dt_awarded" class="table-condensed table" cellspacing="0" width="100%">
                        <thead>
                            <tr role="row">
                                <th style=""><input type="checkbox" value="CH" name="selectall"></th>
                                <th style="">Consultant Name</th>
                                <th style="">Title of Project</th>
                                <th style="">AKDN Contact</th>
                                <th style="">AKDN Contact Email</th>
                                <th style="">Start Date</th>
                                <th style="">End Date</th>
                                <th style="">Registered by</th>
								<th style="">Status</th>
                                <th style="">Action</th>
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
<!-- Edit Modal -->
<div class="modal" id="div-award-consultant">
    <div class="modal-dialog">
        <div class="modal-content box">
            <form class="form-horizontal" name="form_simple" id="frm-award-consultant" method="post" action="{{ URL::route('admin.awarded.update') }}">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Edit Registered Consultancy</h4>
                </div>
                <div class="modal-body">
                    <div class="callout callout-success" id="award-consultant-success" style="display:none">
                        <p>You have successfully updated the registered consultancy details.</p>
                    </div>
                    <div class="callout callout-danger" id="award-consultant-danger" style="display:none">
                        <p>There were some errors.</p>
                    </div>
                    <div class="form-group">
                        <label for="name" class="control-label col-xs-4">Consultant Name</label>
                        <div class="col-xs-8">
                            <input type="text" name="consultant_full_name" class="form-control" id="aw-con-name" readonly="readonly" value="" />
                            <input type="hidden" name="consultant_id" id="aw-con-consultant_id" value=""/>
                            <input type="hidden" name="id" id="aw-con-consultant_id" value=""/>
                            <span class="help-inline error"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="title_of_consultancy" class="control-label col-xs-4">Title of Project</label>
                        <div class="col-xs-8">
                            <textarea name="title_of_consultancy" class="form-control" id="aw-con-title_of_consultancy" placeholder="Title of project"></textarea>
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
                        <label for="full_name" class="control-label col-xs-4">AKDN</label>
                        <div class="col-xs-8">
                            <input type="text" name="full_name" class="form-control" id="aw-con-akdn_name" placeholder="AKDN Name"/>
                            <input type="hidden" name="akdn_id" id="aw-con-akdn_id" value=""/>
                            <span class="help-inline error"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="akdn_manager_name" class="control-label col-xs-4">AKDN Contact</label>
                        <div class="col-xs-8">
                            <input type="text" name="akdn_manager_name" class="form-control" id="aw-con-akdn_manager_name" placeholder="AKDN Contact Name"/>
                            <span class="help-inline error"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="akdn_manager_email" class="control-label col-xs-4">AKDN Contact Email</label>
                        <div class="col-xs-8">
                            <input type="text" name="akdn_manager_email" class="form-control" id="aw-con-akdn_manager_email" placeholder="AKDN Contact Email"/>
                            <span class="help-inline error"></span>
                        </div>
                    </div>
                </div>
                <input name="consultant_assignment_id" type="hidden" value="" id="consultant_assignment_id">
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left btn-flat" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-flat">Update</button>
                </div>
            </form>
            <div class="overlay">
                <i class="fa fa-spin fa-spinner"></i>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<!-- Delete Modal -->
@stop

@section('script')
{{ HTML :: script ('assets/plugins/iCheck/icheck.min.js') }}
<script type="text/javascript">

    var server_params = '';
    function ExportToExcel(ele){
        var query_string = decodeURIComponent($.param(server_params));
        $(ele).attr('href','{{ URL::route("admin.awarded.excelexport") }}?'+query_string);
        return true;
    }

    function editAwardedConsultancy(dtRow){
    	$('#div-award-consultant').modal('show');
    	$('#frm-award-consultant').populate(dtRow,{resetForm: true, debug: true});
    	$('.date-picker').datepicker('update');
    	//start_date_picker.datepicker('update');
    }
    
    jQuery(document).ready(function() {
    
        $('input[name="start_date_dmy"]').datepicker({ format : 'dd-mm-yyyy'}).on('changeDate', function(e){
            $('input[name="end_date"]').datepicker('setStartDate', e.date);
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
                console.log('hello');
        		$('#frm-award-consultant')[0].reset();
        		$('#award-consultant-success').show().delay(3000).fadeOut('slow', function(){
        			dt_awarded.fnStandingRedraw();
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
            dt_awarded = $('#dt_awarded').dataTable({
            	"bProcessing" : false,
                "bServerSide" : true,
                "sAjaxSource" : "{{ URL::route('admin.awarded.index') }}",
                "aaSorting"   : [[1, "desc"]],
                "fnServerParams": function ( aoData ) {
                    aoData.push({ "name": "act", "value": "fetch" });
                    server_params = aoData;
                },
                "aoColumns"   : [
                    {
                        mData     : "id",
                        sWidth    : "10px",
                        bVisible  : true,
                        bSortable : false,
                        mRender   : function (v, t, o) {
        
                           return '<input type="checkbox" id="chk_'+v+'" name="id[]" value="'+v+'" class="tickbox">';
                        }
                    },
                    { 	mData : "consultant_full_name",sClass    : 'text-left' },
                    { 	mData : "title_of_consultancy",sClass 	: "text-left", },
                    {   mData : "full_name",sClass   : "text-center", },
                    { 	mData : "akdn_manager_email",sClass 	: "text-left", },
                    { 	mData : "start_date_dmy",sClass 	: "text-center", },
                    { 	mData : "end_date_dmy",sClass 	: "text-center", },
                    {   mData : "akdn_manager_name",sClass  : "text-left", },
                    {   
                        mData : "status",
                        sClass  : "text-left",
                        bVisible  : true,
                        bSortable : false,
                        mRender : function (v,t,o)
                        {
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
                        sWidth : "10%",
                        bSortable : false,
                        mData     : null,
                        mRender: function(v, t, o) {
        
                        	var data = JSON.stringify(o);
                        	var act_html    =   "<div class='btn-group-pr5'>"
                                            @if(CheckPermission::isPermitted('admin.awarded.edit'))
                                            +        "<button title='edit' class='btn btn-sm btn-warning btn-flat' type='button' onclick='editAwardedConsultancy("+ data +")'><span class='fa fa-edit'></span></button>"
                                            @endif
                                            @if(CheckPermission::isPermitted('admin.awarded.delete'))
                                            +        "<button title='Delete' id='awarded_consultancy_destroy_confirm' class='btn btn-sm btn-danger btn-flat ' type='button' value='"+o['id']+"'><span class='fa fa-remove'></span></button>"
                                            @endif
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
                }
            });
        
        $("div.overlay").hide();
        
        $('#frm-consultant-search').submit(function(){
        
            	dt_awarded.api().draw();
            	return false;
            });
    
        $('#adminuser_destroy_confirm').click(function() {
                
            var ele = $('#dt_awarded tbody input[type=checkbox]:checked');
            var btn_html = ele.html();

            var destroy_ele = $('#dt_awarded tbody input[type=checkbox]:checked');
            var destroy_id = [];
            $.each(destroy_ele, function(i, ele){
                destroy_id.push($(ele).val());
            });
            if(destroy_id.length > 0){

            bootbox.confirm("Are you sure you want to delete the Awarded Consultancy from the database? This action cannot be undone.", function(result) {

                if( result==true )
                {
                    $.ajax({
                        url        : "{{URL::route('admin.awarded.delete')}}",
                        type       : 'delete',
                        dataType   : 'json',
                        data       : { id : destroy_id, name : 'destroyall'},
                        beforeSend : function() {

                            ele.prop('disabled', true).html('<i class="fa fa-spin fa-spinner"></i>');
                        },
                        complete   : function() {

                            ele.prop('disabled', false).html(btn_html); 
                        },
                        success    : function(resp) {

                            //showAlert('Success!','Admin Users have been deleted permanently!','success','30%');
                            dt_awarded.fnStandingRedraw();
                            
                            $('#dt_awarded input:checkbox[name="selectall"]').prop('checked', false);
                        }
                    });
                }

                else
                {

                    dt_awarded.fnStandingRedraw();
                }
             });
            }
            else
            {
                bootbox.alert("Please Choose Any Recode to Delete!", function() {
                  dt_awarded.fnStandingRedraw();
                });
            }   
        });
        
    });
    
    $("#dt_awarded_wrapper .dataTables_filter input").unbind().donetyping(function() {

        dt_awarded.api().search(this.value).draw();
        return;
    });
    
    $('#dt_awarded input:checkbox[name="selectall"]').click(function() {
        
        var is_checked = $(this).is(':checked');
        $(this).closest('table').find('tbody tr td:first-child input[type=checkbox], thead tr th:first-child input[type=checkbox], tfoot tr th:first-child input[type=checkbox]')
                                .prop('checked', is_checked);
    });
    
    $("#dt_awarded thead#filters input:text").donetyping(function() {

        dt_awarded.fnFilter( this.value, $(" #dt_awarded input:text").index(this));
    });
    
    $(document).on('click', '#awarded_consultancy_destroy_confirm', function() {
        var id = $(this).val();
        
        bootbox.confirm("Are you sure you want to delete the Awarded Consultancy from the database? This action cannot be undone.", function(result) {
        
        if( typeof id === 'undefined' ) return false;
        
        if(result == true){
    
        var ele = $('button.destroy_awarded_consultant[value="'+ id +'"]');
    
		$.ajax({
			url        : "{{URL::to('/')}}/" + 'admin/awarded/' +id,
			type       : "DELETE",
			data       : {id : id, name : 'destroy'},
			dataType   : 'json',
			beforeSend : function() {
	    
			ele.prop('disabled', true).html('<i class="fa fa-spin fa-spinner"></i>');
			},
			success    : function(resp) {
	    
				dt_awarded.fnStandingRedraw();
			}
		});

		}else{
    
			return true;
    
			dt_awarded.fnStandingRedraw();
			}
    
		});
    
    }); 
    
     
    $("#dt_awarded thead input").donetyping( function () {
        /* Filter on the column (the index) of this element */
        dt_awarded.fnFilter( this.value, $("#dt_awarded thead input").index(this));
    } );
    
    $("#dt_awarded thead input").focus( function () {
        if ( this.className == "form-control" )
        {
            //this.className = "";
            this.value = "";
        }
    });
    
</script>
@stop