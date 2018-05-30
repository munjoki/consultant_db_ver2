@extends('admin.layout')
@section('content')
<!------------------------------------------------------------------------------------------------------------------------------------------
    AKDN Consultant Database Version 1.0
    this file handles viewing existing AKDN users and performing some quick actions (edit/delete)
-------------------------------------------------------------------------------------------------------------------------------------------->
<section class="content-header">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary box-solid">
                <div class="box-header">
                    <h3 class="box-title">AKDN Users List</h3>
                    <div class="box-tools pull-right">
                        @if(CheckPermission::isPermitted('admin.akdn.create'))
                        <a href="{{URL::route('admin.akdn.create')}}" class="btn btn-primary btn-default">Add New AKDN User</a>
                        @endif
                        @if(CheckPermission::isPermitted('admin.akdn.delete'))
                        <a id="adminakdn_destroy_confirm" class="btn btn-primary btn-sm popup-modal-dismiss">Delete All</a>
                        @endif
                        @if(CheckPermission::isPermitted('admin.akdn.excelexport'))
                        <a target="_blank" id="export_excel" href="javascript:void(0);" class="btn btn-sm btn-flat btn-primary" onclick="return ExportToExcel(this);"><i class="fa fa-file-excel-o"></i> Export To Excel</a>
                        @endif
                        <a href="javascript:void(0)" class="btn btn-default btn-sm dark" title="Refresh" onclick="javascript:dt_akdn.fnStandingRedraw();"><i class="fa fa-refresh"></i></a>
                        <!-- <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button> -->
                    </div>
                </div>
                <div class="box-body">
                    <table id="dt_akdn" class="table-condensed table" cellspacing="0" width="100%">
                        <thead>
                            <tr role="row">
                                <th style=""><input type="checkbox" value="CH" name="selectall"></th>
                                <th>Surname</th>
                                <th>Other Names</th>
                                <th>Email</th>
                                <th>Country</th>
                                <th>Agency</th>
                                <th>Verified</th>
                                <th>Action</th>
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
<div class="modal" id="div-akdn">
    <div class="modal-dialog">
        <div class="modal-content box">
            <form class="form-horizontal" name="" id="frm-award-consultant" method="post" action="{{ URL::route('admin.akdn.update') }}">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Edit AKDN </h4>
                </div>  
                <div class="modal-body">
                    <div class="callout callout-success" id="award-consultant-success" style="display:none">
                        <p>You have successfully updated the registered AKDN details.</p>
                    </div>
                    <div class="callout callout-danger" id="award-consultant-danger" style="display:none">
                        <p>There were some errors.</p>
                    </div>
                    <div class="form-group">
                        <label for="name" class="control-label col-xs-4">Surname</label>
                        <div class="col-xs-8">
                            <input type="text" name="surname" class="form-control" id="aw-con-name"  value="" />
                            <input type="hidden" name="id" id="" value=""/>
                            <span class="help-inline error"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="name" class="control-label col-xs-4">Other Names</label>
                        <div class="col-xs-8">
                            <input type="text" name="other_name" class="form-control" id="aw-con-name"  value="" />
                            <span class="help-inline error"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="name" class="control-label col-xs-4">Email</label>
                        <div class="col-xs-8">
                            <input type="text" name="email" class="form-control" id="aw-con-name"  value="" />
                            <span class="help-inline error"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="name" class="control-label col-xs-4">Country of Residence</label>
                        <div class="col-xs-8">
                            {{ Form::select('nationality[]',array(""=>"")+$country, '0',array( 'id' => 'nationalities','class'=>'form-control chosen-select'))}}
                            {{ $errors->first('nationality', '<span class="help-inline">:message</span>')}}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="name" class="control-label col-xs-4">Agency</label>
                        <div class="col-xs-8">
                            {{ Form::select('agencies[]',array(""=>"")+$agencies, '0',array( 'id' => 'agencies','class'=>'form-control chosen-select'))}}
                            {{ $errors->first('agencies', '<span class="help-inline">:message</span>')}}
                        </div>
                    </div>
                </div>
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
@stop
@section('script')
<script type="text/javascript">

    var server_params = '';
    function ExportToExcel(ele){
        var query_string = decodeURIComponent($.param(server_params));
        $(ele).attr('href','{{ URL::route("admin.akdn.excelexport") }}?'+query_string);
        return true;
    }
    
    function editakdn(dtRow){
        
        $('#div-akdn').modal('show');
        $('#frm-award-consultant').populate(dtRow,{resetForm: true, debug: true});
        
    }
    
    $(document).ready(function(){
    
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
                            dt_akdn.fnStandingRedraw();
                            $('#div-akdn').modal('hide');
    
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
    
        /* Applicant */
        dt_akdn = $('#dt_akdn').dataTable({
            "bProcessing" : false,
            "bServerSide" : true,
            "sAjaxSource" : "{{ URL::route('admin.akdn.index') }}",
            "aaSorting"   : [[1, "asc"]],
            "fnServerParams": function ( aoData ) {
                aoData.push({ "name": "act", "value": "fetch" });
                server_params = aoData;
            },
            "aoColumns"   : [
                {
                    mData     : "id",
                    sWidth    : "30px",
                    // sClass    : 'text-center',
                    bVisible  : true,
                    bSortable : false,
                    mRender   : function (v, t, o) {
    
                       return '<input type="checkbox" id="chk_'+v+'" name="id[]" value="'+v+'" class="tickbox">';
                    }
                },
                { mData : "surname",sClass    : 'text-left',bSortable : true },
                { mData : "other_name",sClass    : 'text-left',bSortable : true },
                { mData : "email",sClass    : 'text-left',bSortable : true },
                {
                    mData: "akdnnationality", 
                    bSortable : true,
                    sClass    : 'text-left'
                },
                { 
                    mData: "agency",
                    bSortable : true,
                    sClass    : 'text-left'
                },
                
                { 
                    mData : "is_verified",
                    sClass    :"text-center",
                    bSortable : true,
                    mRender: function(v,t,o){
                        if('1' == v ){
                            return '<div class="badge bg-green">Yes</div>';
                        }else{
                            return '<div class="badge bg-red">No</div>';
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
    
                        var act_html    =   "<div class='btn-group pr5'>"
                                        @if(CheckPermission::isPermitted('admin.akdn.edit'))
                                        +    "<a title='Edit' href='{{URL::to('/')}}/admin/akdn/"+ o['id']+"/edit' class='btn btn-sm btn-warning btn-flat'><i class='fa fa-edit'></i></a>"
                                        @endif
                                        @if(CheckPermission::isPermitted('admin.akdn.delete'))
                                        +   "<button title='Delete' id='delete_akdn' class='btn btn-sm btn-danger btn-flat destroy_adminuser' type='button' value='"+o['id']+"'><span class='fa fa-remove'></span></button>"
                                        @endif
                                        +   "</div>";
    
                        return act_html;
                    }
                }
            ],
            fnPreDrawCallback : function() { $("div.overlay").show(); },
            fnDrawCallback : function (oSettings) {
                $("div.overlay").hide();
            }
        });
        
        $("#dt_akdn_wrapper .dataTables_filter input").unbind().donetyping(function() {
    
            dt_akdn.api().search(this.value).draw();
            return;
        });
    
        $('#dt_akdn input:checkbox[name="selectall"]').click(function() {
            
            var is_checked = $(this).is(':checked');
            $(this).closest('table').find('tbody tr td:first-child input[type=checkbox], thead tr th:first-child input[type=checkbox], tfoot tr th:first-child input[type=checkbox]')
                                    .prop('checked', is_checked);
        });
    
        $("#dt_akdn thead#filters input:text").donetyping(function() {
    
                dt_akdn.fnFilter( this.value, $(" #dt_akdn input:text").index(this));
        });
        
        $('#adminakdn_destroy_confirm').click(function() {
            
            var ele = $('#dt_contest tbody input[type=checkbox]:checked');
            var btn_html = ele.html();

            var destroy_ele = $('#dt_akdn tbody input[type=checkbox]:checked');
            var destroy_id = [];
             $.each(destroy_ele, function(i, ele){
                    destroy_id.push($(ele).val());
                });
            if( destroy_id.length > 0){
                
                bootbox.confirm("Are you sure you want to delete the current user from the database? This action cannot be undone.", function(result) {
                
                if( result==true )
                {
                    $.ajax({
                        url        : "{{URL::route('admin.akdn.delete')}}",
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
                            dt_akdn.fnStandingRedraw();
                            
                            $('#dt_akdn input:checkbox[name="selectall"]').prop('checked', false);
                        }
                    });

                }else
                {
                    dt_akdn.fnStandingRedraw();
                }
                 
           
                });
            }

            else
            {

                bootbox.alert("Please Choose Any Recode to Delete!", function() {
                  dt_akdn.fnStandingRedraw();
                });
            }   
        });
     
    });   
    
    $(document).on('click', '#delete_akdn', function() {
    
        var id = $(this).val();
    
        bootbox.confirm("Are you sure you want to delete the current user from the database? This action cannot be undone.", function(result) {
    
        if( typeof id === 'undefined' ) return false;
        if(result == true){
        var ele = $('button.destroy_adminuser[value="'+ id +'"]');
        
        $.ajax({
           url        : "{{URL::to('/')}}/" + 'admin/akdn/' +id,
            type       : "delete",
            data       : {id : id, name : 'destroy'},
            dataType   : 'json',
            beforeSend : function() {
    
                ele.prop('disabled', true).html('<i class="fa fa-spin fa-spinner"></i>');
            },
            success    : function(resp) {
    
                dt_akdn.fnStandingRedraw();
                // showAlert('Success!', 'AdminUser has been deleted permanently!', 'success', '30%');
            }
        
    
            });
    
            }else{
    
                return true;
    
                dt_akdn.fnStandingRedraw();
            }
    
        });
        
    }); 
</script>
@stop