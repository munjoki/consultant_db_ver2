@extends('admin.layout')
@section('content')
<!------------------------------------------------------------------------------------------------------------------------------------------
    AKDN MER Consultant Database Version 1.0
    this file shows the current user roles available
-------------------------------------------------------------------------------------------------------------------------------------------->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">Roles List</h3>
                    <div class="box-tools pull-right">
                        <div class="box-tools pull-right">
                            @if(CheckPermission::isPermitted('admin.role.create'))
                            <a href="{{URL::route('admin.role.create')}}" class="btn btn-primary btn-default">Add New Role</a>
                            @endif
                            @if(CheckPermission::isPermitted('admin.role.delete'))
                            <a id="adminuser_destroy_confirm" class="btn btn-primary">Delete All</a>
                            @endif
                            @if(CheckPermission::isPermitted('admin.role.excelexport'))
                            <a target="_blank" id="export_excel" href="javascript:void(0);" class="btn btn-sm btn-flat btn-primary" onclick="return ExportToExcel(this);"><i class="fa fa-file-excel-o"></i> Export To Excel</a>
                            @endif
                            <a href="javascript:void(0)" class="btn btn-default btn-sm dark" title="Refresh" onclick="javascript:dt_role.fnStandingRedraw();"><i class="fa fa-refresh"></i></a>
                        </div>
                    </div>
                </div>
                <div class="box-body">
                    <table id="dt_role" class="table-condensed table" cellspacing="0" width="100%">
                        <thead>
                            <tr role="row">
                                <th style=""><input type="checkbox" value="CH" name="selectall"></th>
                                <th style="">Role Name</th>
                                <th style="">Action</th>
                            </tr>
                        </thead>
                        <thead>
                            <tr>
                                <!-- <th></th>
                                    <th></th>
                                    <th><input type="text" class="form-control" placeholder="Title" name="title"></th>
                                    <th><input type="text" class="form-control" placeholder="Akdn Manager" name="colfilter_manager_name"></th>
                                    <th> <input type="text" class="form-control" placeholder="Akdn Manager email" name="colfilter_manager_name"></th>
                                    <th><input type="text" class="form-control" placeholder="start date" name="colfilter_start_date" style="width:100px;"> </th>
                                    <th><input type="text" class="form-control" placeholder="End date" name="colfilter_end_date" style="width:100px;"></th>
                                    <th></th>
                                    <th></th> -->
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
<!-- /.modal -->
<!-- Delete Modal -->
@stop

@section('script')
<script type="text/javascript">
    
    var server_params = '';
    function ExportToExcel(ele){
        var query_string = decodeURIComponent($.param(server_params));
        console.log(query_string);
        $(ele).attr('href','{{ URL::route("admin.role.excelexport") }}?'+query_string);
        return true;
    }

    jQuery(document).ready(function() {
             /* Role Permission */
            dt_role = $('#dt_role').dataTable({
                "bProcessing" : false,
                "bServerSide" : true,
                "sAjaxSource" : "{{ URL::route('admin.role.index') }}",
                "fnServerParams": function ( aoData ) {
                    var form_data = $('#frm_filter').serializeArray();

                    $.each(form_data, function(i, val){
                        aoData.push(val);
                    });
                    aoData.push({ "name": "act", "value": "fetch"});
                    server_params = aoData;
                },
                "aaSorting"   : [[1, "desc"]],
                "aoColumns"   : [
                    {
                        mData     : "id",
                        sWidth    : "30px",
                        bVisible  : true,
                        bSortable : false,
                        mRender   : function (v, t, o) {
        
                           return '<input type="checkbox" id="chk_'+v+'" name="id[]" value="'+v+'" class="tickbox">';
                        }
                    },
                    {   mData : "name",sClass   : 'text-left' },
                    {
                        sClass    :"text-right",
                        bSortable : false,
                        mData     : null,
                        mRender: function(v, t, o) {
        
                            var data = JSON.stringify(o);
                            var act_html    =   "<div class='btn-group-pr5'>"
                                            @if(CheckPermission::isPermitted('admin.role.edit'))
                                            +    "<a title='Edit' href='{{URL::to('/')}}/admin/role/"+ o['id']+"/edit' class='btn btn-sm btn-warning btn-flat'><i class='fa fa-edit'></i></a>"
                                            @endif
                                            @if(CheckPermission::isPermitted('admin.role.delete'))
                                            +     "<button title='Delete' id='awarded_consultancy_destroy_confirm' class='btn btn-sm btn-danger btn-flat ' type='button' value='"+o['id']+"'><span class='fa fa-remove'></span></button>"
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
        
                dt_role.api().draw();
                return false;
            });
    
        $('#adminuser_destroy_confirm').click(function() {
                
            var ele = $('#dt_role tbody input[type=checkbox]:checked');
            var btn_html = ele.html();

            var destroy_ele = $('#dt_role tbody input[type=checkbox]:checked');
            var destroy_id = [];
            $.each(destroy_ele, function(i, ele){
                destroy_id.push($(ele).val());
            });
            if(destroy_id.length > 0){

            bootbox.confirm("Are you sure you want to delete the Role from the database? This action cannot be undone.", function(result) {

                if( result==true )
                {
                    $.ajax({
                        url        : "{{URL::route('admin.role.delete')}}",
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
                            dt_role.fnStandingRedraw();
                            
                            $('#dt_role input:checkbox[name="selectall"]').prop('checked', false);
                        }
                    });
                }

                else
                {

                    dt_role.fnStandingRedraw();
                }
             });
            }
            else
            {
                bootbox.alert("Please Choose Any Recode to Delete!", function() {
                  dt_role.fnStandingRedraw();
                });
            }   
        });
        
    });
    
    $("#dt_role_wrapper .dataTables_filter input").unbind().donetyping(function() {

        dt_role.api().search(this.value).draw();
        return;
    });
    
    $('#dt_role input:checkbox[name="selectall"]').click(function() {
        
        var is_checked = $(this).is(':checked');
        $(this).closest('table').find('tbody tr td:first-child input[type=checkbox], thead tr th:first-child input[type=checkbox], tfoot tr th:first-child input[type=checkbox]')
                                .prop('checked', is_checked);
    });
    
    $("#dt_role thead#filters input:text").donetyping(function() {

        dt_role.fnFilter( this.value, $(" #dt_role input:text").index(this));
    });
    
    $(document).on('click', '#awarded_consultancy_destroy_confirm', function() {
        var id = $(this).val();
        
        bootbox.confirm("Are you sure you want to delete the Role from the database? This action cannot be undone.", function(result) {
        
        if( typeof id === 'undefined' ) return false;
        
        if(result == true){
    
        var ele = $('button.destroy_awarded_consultant[value="'+ id +'"]');
    
        $.ajax({
            url        : "{{URL::to('/')}}/" + 'admin/role/' +id,
            type       : "DELETE",
            data       : {id : id, name : 'destroy'},
            dataType   : 'json',
            beforeSend : function() {
        
            ele.prop('disabled', true).html('<i class="fa fa-spin fa-spinner"></i>');
            },
            success    : function(resp) {
        
                dt_role.fnStandingRedraw();
            }
        });

        }else{
    
            return true;
    
            dt_role.fnStandingRedraw();
            }
    
        });
    
    }); 
    
     
    $("#dt_role thead input").donetyping( function () {
        /* Filter on the column (the index) of this element */
        dt_role.fnFilter( this.value, $("#dt_role thead input").index(this));
    } );
    
    $("#dt_role thead input").focus( function () {
        if ( this.className == "form-control" )
        {
            //this.className = "";
            this.value = "";
        }
    });
    
</script>
@stop