@extends('admin.layout')
@section('content')
<!------------------------------------------------------------------------------------------------------------------------------------------
    AKDN MER Consultant Database Version 1.0
    this file handles existing languages and some quick actions (edit/delete)
-------------------------------------------------------------------------------------------------------------------------------------------->
<section class="content-header">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary box-solid">
                <div class="box-header">
                    <h3 class="box-title">Languages List</h3>
                    <div class="box-tools pull-right">
                        @if(CheckPermission::isPermitted('admin.language.create'))
                        <a href="{{URL::route('admin.language.create')}}" class="btn btn-primary btn-default">Add New Language</a>
                        @endif
                        @if(CheckPermission::isPermitted('admin.language.delete'))
                        <a id="adminuser_destroy_confirm" class="btn btn-primary">Delete All</a>
                        @endif
                        @if(CheckPermission::isPermitted('admin.language.excelexport'))
                        <a target="_blank" id="export_excel" href="javascript:void(0);" class="btn btn-sm btn-flat btn-primary" onclick="return ExportToExcel(this);"><i class="fa fa-file-excel-o"></i> Export To Excel</a>
                        @endif
                        <a href="javascript:void(0)" class="btn btn-default btn-sm dark" title="Refresh" onclick="javascript:dt_language.fnStandingRedraw();"><i class="fa fa-refresh"></i></a>
                        <!-- <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button> -->
                    </div>
                </div>
                <div class="box-body">
                    <table id="dt_language" class="table-condensed table" cellspacing="0" width="100%">
                        <thead>
                            <tr role="row">
                                <th style=""><input type="checkbox" value="CH" name="selectall"></th>
                                <th style="">Language</th>
                                <th style="">Language Description</th>
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
<!--Model for restoring single confirm-->
<div class="modal" id="div-language">
    <div class="modal-dialog">
        <div class="modal-content box">
            <form class="form-horizontal" name="" id="frm-award-consultant" method="post" action="{{ URL::route('admin.language.update') }}">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Edit Language </h4>
                </div>
                <div class="modal-body">
                    <div class="callout callout-success" id="award-consultant-success" style="display:none">
                        <p>You have successfully updated the Language details.</p>
                    </div>
                    <div class="callout callout-danger" id="award-consultant-danger" style="display:none">
                        <p>There were some errors.</p>
                    </div>
                    <div class="form-group">
                        <label for="name" class="control-label col-xs-4">Language Name</label>
                        <div class="col-xs-8">
                            <input type="text" name="lang_acr" class="form-control" id="aw-con-name" readonly="readonly" value="" />
                            <input type="hidden" name="id" id="" value=""/>
                            <span class="help-inline error"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="akdn_manager_name" class="control-label col-xs-4">Language Description</label>
                        <div class="col-xs-8">
                            <input type="text" name="lang_des" class="form-control" id="aw-con-akdn_manager_name" placeholder="Language Description"/>
                            <span class="help-inline error"></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left btn-flat" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-flat">Edit</button>
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
        console.log(query_string);
        $(ele).attr('href','{{ URL::route("admin.language.excelexport") }}?'+query_string);
        return true;
    }
    function editLanguage(dtRow){
        $('#div-language').modal('show');
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
                            dt_language.fnStandingRedraw();
                            $('#div-language').modal('hide');
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
        dt_language = $('#dt_language').dataTable({
            "bProcessing" : false,
            "bServerSide" : true,
            "sAjaxSource" : "{{ URL::route('admin.language.index') }}",
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
                { mData : "lang_acr"},
                { mData : "lang_des"},
                {
                    sWidth    : "120px",
                    sClass    :"text-right",
                    bSortable : false,
                    mData     : null,
                    mRender: function(v, t, o) {    
    
                        var data = JSON.stringify(o);
    
                        var act_html    =   "<div class='btn-group pr5'>"
                                        @if(CheckPermission::isPermitted('admin.language.edit'))
                                        +   "<button title='edit' class='btn btn-sm btn-warning btn-flat' type='button' onclick='editLanguage("+ data +")'><span class='fa fa-edit'></span></button>"
                                        @endif
                                        @if(CheckPermission::isPermitted('admin.language.delete'))
                                        +   "<button title='Delete' id='delete_language' class='btn btn-sm btn-danger btn-flat destroy_adminuser' type='button' value='"+o['id']+"'><span class='fa fa-remove'></span></button>"
                                        @endif
                                        +   "</div>";
    
                        return act_html;
                    }
                }
            ],
            fnPreDrawCallback : function() { $("div.overlay").show()},
            fnDrawCallback : function (oSettings) {
                $("div.overlay").hide();
            },
        });
        
        $("#dt_language_wrapper .dataTables_filter input").unbind().donetyping(function() {
    
            dt_language.api().search(this.value).draw();
            return;
        });
    
        $('#dt_language input:checkbox[name="selectall"]').click(function() {
            
            var is_checked = $(this).is(':checked');
            $(this).closest('table').find('tbody tr td:first-child input[type=checkbox], thead tr th:first-child input[type=checkbox], tfoot tr th:first-child input[type=checkbox]')
                                    .prop('checked', is_checked);
        });
    
        $("#dt_language thead#filters input:text").donetyping(function() {
    
                dt_language.fnFilter( this.value, $(" #dt_language input:text").index(this));
        });
    
        $('#adminuser_destroy_confirm').click(function() {
                
                var ele = $('#dt_language tbody input[type=checkbox]:checked');
                var btn_html = ele.html();

                var destroy_ele = $('#dt_language tbody input[type=checkbox]:checked');
                var destroy_id = [];
                $.each(destroy_ele, function(i, ele){
                    destroy_id.push($(ele).val());
                });
                if( destroy_id.length > 0 )
                {
                bootbox.confirm("Are you sure you want to delete the Language from the database? This action cannot be undone.", function(result) {
                    
                    if(result==true){
                        $.ajax({
                            url        : "{{URL::route('admin.language.delete')}}",
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
                                dt_language.fnStandingRedraw();
                                
                                $('#dt_language input:checkbox[name="selectall"]').prop('checked', false);
                            }
                        });
                    }
                    else{

                        dt_language.fnStandingRedraw();
                    }
                });

                }else{

                    bootbox.alert("Please Choose Any Recode to Delete!", function() {
                    dt_language.fnStandingRedraw();
                });
            }
        });
        
    });
    
    
    
    $(document).on('click', '#delete_language', function() {
    
        var id = $(this).val();
        bootbox.confirm("Are you sure you want to delete the Language from the database? This action cannot be undone.", function(result) {
    
        if( typeof id === 'undefined' ) return false;
    
        if(result==true){
    
        var ele = $('button.destroy_adminuser[value="'+ id +'"]');
        
        $.ajax({
           url        : "{{URL::to('/')}}/" + 'admin/language/' +id,
            type       : "delete",
            data       : {id : id, name : 'destroy'},
            dataType   : 'json',
            beforeSend : function() {
    
                ele.prop('disabled', true).html('<i class="fa fa-spin fa-spinner"></i>');
            },
            success    : function(resp) {
    
                dt_language.fnStandingRedraw();
                // showAlert('Success!', 'AdminUser has been deleted permanently!', 'success', '30%');
            }
        });
    
        }else{
    
                return true;
    
                dt_language.fnStandingRedraw();
            }
    
            });
            
        }); 
</script>
@stop