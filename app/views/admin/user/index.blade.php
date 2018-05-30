@extends('admin.layout')
@section('content')
<!------------------------------------------------------------------------------------------------------------------------------------------
    AKDN MER Consultant Database Version 1.0
    this file handles viewing of existing consultant details with some quick action (edit/delete)
-------------------------------------------------------------------------------------------------------------------------------------------->
<section class="content-header">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary box-solid">
                <div class="box-header">
                    <h3 class="box-title">Registered Consultants List</h3>
                    <div class="box-tools pull-right">
                        @if(CheckPermission::isPermitted('admin.user.delete'))
                        <a id="adminuser_destroy_confirm" class="btn btn-primary">Delete All</a>
                        @endif
                        @if(CheckPermission::isPermitted('admin.user.excelexport'))
                        <a target="_blank" id="export_excel" href="javascript:void(0);" class="btn btn-sm btn-flat btn-primary" onclick="return ExportToExcel(this);"><i class="fa fa-file-excel-o"></i> Export To Excel</a>
                        @endif
                        <a href="javascript:void(0)" class="btn btn-default btn-sm dark" title="Refresh" onclick="javascript:dt_user.fnStandingRedraw();"><i class="fa fa-refresh"></i></a>
                    </div>
                </div>
                <div class="box-body">
                    <table id="dt_user" class="table-condensed table" cellspacing="0" width="100%">
                        <thead>
                            <tr role="row">
                                <th style=""><input type="checkbox" value="CH" name="selectall"></th>
                                <th style="">Surname</th>
                                <th style="">First/Given Names</th>
                                <th>Gender</th>
                                <th style="">Email</th>
                                <th style="">Tel No.</th>
                                <th style="">LinkedIn</th>
                                <th style="">Blog</th>
								<th style="">Resume</th>
                                <th>Previous Consultancies</th>
                                <th style="">Del Req.</th>
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
@stop

@section('script')
<script type="text/javascript">

    var server_params = '';
    function ExportToExcel(ele){
        var query_string = decodeURIComponent($.param(server_params));
        $(ele).attr('href','{{ URL::route("admin.user.excelexport") }}?'+query_string);
        return true;
    }

    $(document).ready(function(){
            /* Applicant */
        dt_user = $('#dt_user').dataTable({
                "bProcessing" : false,
                "bServerSide" : true,
                "sAjaxSource" : "{{ URL::route('admin.user.index') }}",
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
                    { mData : "surname",sClass    : 'text-left' },
                    { mData : "other_names",sClass    : 'text-left' },
                    { 
                        mData : "gender",
                        //sClass    :"text-center",
                        bSortable : false,
                        mRender: function(v,t,o){
                            if('1' == v ){
                                return "Male";    
                            }else{
                                return "Female";    
                            }
                        }
                    },
                    { mData : "email",bSortable : false },
                    { mData : "telno",bSortable : false },
                    
                    {
                        mData : "linkedin_url",
                        "bSortable": false,
                        sClass    :"text-center",
                        mRender: function(v,t,o){
                            if(v!=='' && v!==null){
                                return '<a href="'+ v +'" class="btn btn-primary btn-xs" target="_blank"><i class="fa fa-linkedin"></i></a>'
                            }
                            else{
                                return " ";
                            }
                        }
                    },
                    { 
                        mData : "website_url",
                        sClass    :"text-center",
                        "bSortable": false,
                        mRender: function(v,t,o){
                            if(v != null){
                                return '<a href="'+ v +'" class="btn btn-primary btn-xs" target="_blank"><i class="fa fa-globe"></i></a>'
                            }else{
                                return " ";
                            }
                        }
                    },
                    { 
                        "mData": "resume",
                        "bSortable": false,
                        sClass    :"text-center",
                        mRender: function(v,t,o){
                            if(v!=='' && v!==null)
                            {
                                return '<a href="{{ URL::to("/upload/resume") }}/'+v+' " class="btn btn-success btn-xs" target="_blank"><i class="fa fa-download"></i></a>';
                            }
                            else{
                                return "  ";
                            }
                        }
                    },

                    { 
                        "mData": "privious_consutancies",
                        "bSortable": false,
                        sClass    :"text-center",
                        mRender: function(v,t,o){
                            if(v!=='' && v!==null)
                            {
                                return 'Yes';
                            }
                            else{
                                return "No";
                            }
                        }
                    },

                    { 
                        mData : "delete_request",
                        sClass    :"text-center",
                        mRender: function(v,t,o){
                            return ( "1" == v ) ? '<i class="fa fa-trash-o fa-lg text-danger"></i>' : '';
                        }
                    },
                    {
                        sWidth    : "120px",
                        sClass    :"text-right",
                        bSortable : false,
                        mData     : null,
                        mRender: function(v, t, o) {
        
                            var act_html    =  "<div class='btn-group pr5'>"
                                            @if(CheckPermission::isPermitted('admin.constant.show'))
                                            +       "<a title='View' href='{{URL::to('/')}}/admin/user/"+ o['id']+"' class='btn btn-sm btn-primary btn-flat'><i class='fa fa-search'></i></a>"
                                            @endif
                                            @if(CheckPermission::isPermitted('admin.consultant.edit'))
                                            +       "<a title='Edit' href='{{URL::to('/')}}/admin/consultant/"+ o['id']+"/edit' class='btn btn-sm btn-warning btn-flat'><i class='fa fa-edit'></i></a>"
                                            @endif
                                            @if(CheckPermission::isPermitted('admin.user.delete'))
                                            +        "<button id='delete_user' title='Delete' class='btn btn-sm btn-danger btn-flat destroy_adminuser' type='button' value='"+o['id']+"'><span class='fa fa-remove'></span></button>"
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
            
            $("#dt_user_wrapper .dataTables_filter input").unbind().donetyping(function() {
        
                dt_user.api().search(this.value).draw();
                return;
            });
        
            $('#dt_user input:checkbox[name="selectall"]').click(function() {
                
                var is_checked = $(this).is(':checked');
                $(this).closest('table').find('tbody tr td:first-child input[type=checkbox], thead tr th:first-child input[type=checkbox], tfoot tr th:first-child input[type=checkbox]')
                                        .prop('checked', is_checked);
            });
        
            $("#dt_user thead#filters input:text").donetyping(function() {
        
                    dt_user.fnFilter( this.value, $(" #dt_user input:text").index(this));
            });
        
            $('#adminuser_destroy_confirm').click(function() {
                
                var ele = $('#dt_user tbody input[type=checkbox]:checked');
                var btn_html = ele.html();

                var destroy_ele = $('#dt_user tbody input[type=checkbox]:checked');
                
                var destroy_id = [];
                $.each(destroy_ele, function(i, ele){
                    destroy_id.push($(ele).val());
                });
                if( destroy_id.length > 0 )
                {
                bootbox.confirm("Are you sure you want to delete the current consultant from the database? This action cannot be undone.", function(result) {

                    if(result==true){
                        $.ajax({
                            url        : "{{URL::route('admin.user.delete')}}",
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
                                dt_user.fnStandingRedraw();
                                
                                $('#dt_user input:checkbox[name="selectall"]').prop('checked', false);
                            }
                        });
                    }

                    else{

                        dt_user.fnStandingRedraw();
                    }
                });
                }else{

                    bootbox.alert("Please Choose Any Recode to Delete!", function() {
                      dt_user.fnStandingRedraw();
                    });
                }
        });
        
    });
        
    
    $(document).on('click', '#delete_user', function() {
    
        var id = $(this).val();
        bootbox.confirm("Are you sure you want to delete the current consultant from the database? This action cannot be undone.", function(result) {
    
        if( typeof id === 'undefined' ) return false;
    
        if(result==true){
    
        var ele = $('button.destroy_adminuser[value="'+ id +'"]');
        
        $.ajax({
            url        : "{{URL::route('admin.user.delete')}}",
            type       : "delete",
            data       : {id : id, name : 'destroy'},
            dataType   : 'json',
            beforeSend : function() {
    
                ele.prop('disabled', true).html('<i class="fa fa-spin fa-spinner"></i>');
            },
            success    : function(resp) {
    
                dt_user.fnStandingRedraw();
            }
        });
    
        }else{
                return true;
                dt_user.fnStandingRedraw();
            }
    
            });
            
        }); 
</script>
@stop