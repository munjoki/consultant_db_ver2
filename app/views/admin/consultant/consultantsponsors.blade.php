@extends('admin.layout')
@section('content')
<!------------------------------------------------------------------------------------------------------------------------------------------
    AKDN MER Consultant Database Version 1.0
    this file handles viewing existing AKDN users and performing some quick actions (edit/delete)
-------------------------------------------------------------------------------------------------------------------------------------------->
<section class="content-header">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary box-solid">
                <div class="box-header">
                    <h3 class="box-title">Invited Consultants List</h3>
                    <div class="box-tools pull-right">     
                        @if(CheckPermission::isPermitted('admin.constantsponsor.excelexport'))               
                        <a target="_blank" id="export_excel" href="javascript:void(0);" class="btn btn-sm btn-flat btn-primary" onclick="return ExportToExcel(this);"><i class="fa fa-file-excel-o"></i> Export To Excel</a>
                        @endif
                        <a href="javascript:void(0)" class="btn btn-default btn-sm dark" title="Refresh" onclick="javascript:dt_akdn.fnStandingRedraw();"><i class="fa fa-refresh"></i></a>                        
                    </div>
                </div>
                <div class="box-body">
                    <table id="dt_akdn" class="table-condensed table" cellspacing="0" width="100%">
                        <thead>
                            <tr role="row">
                                <th></th>                                
                                <th>Consultant Name</th>
                                <th>Email Address</th>                                
                                <th>Date of Invitation</th>
                                <th>Invited By</th>
								<th>Invited on Behalf of</th>
                                <th>Status</th>
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

@stop
@section('style')
{{ HTML::style('assets/plugins/sweetalert/sweetalert.css') }}
@stop
@section('script')
{{ HTML::script('assets/plugins/sweetalert/sweetalert.min.js') }}
<script type="text/javascript">
    
    var server_params = '';
    function ExportToExcel(ele){
        var query_string = decodeURIComponent($.param(server_params));
        $(ele).attr('href','{{ URL::route("admin.constantsponsor.excelexport") }}?'+query_string);
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
            "sAjaxSource" : "{{ URL::route('admin.constantsponsor.index') }}",
            "aaSorting"   : [[2, "desc"]],
            "fnServerParams": function ( aoData ) {
                aoData.push({ "name": "act", "value": "fetch" });
                server_params = aoData;
            },
            "aoColumns"   : [
                {
                    mData     : "id",
                    bVisible  : false
                },
                { mData : "name",sClass    : 'text-left' },
                { mData : "email",sClass    : 'text-left',bSortable : false, },
                { mData : "created_at",sClass    : 'text-left',bSortable : false, },
                {   
                    mData : "full_name",
                    sClass    : 'text-left',
                    bSortable : false, 
                },
                {   
                    mData : "invited_on_behalf",
                    sClass    : 'text-left',
                    bSortable : false, 
                },
                {
                    mData : "user_id",
                    sClass    : 'text-left',
                    bSortable : true,
                    mRender: function(v, t, o) {
                        if (v){
                            return "Joined";
                        }else{
                            return "Pending";
                        }
                    }
                },
                {
                    mData: null,
                    bSortable: false,
                    sWidth: "19%",
                    sClass: "text-left",
                    mRender: function(v, t, o) 
                    {
                        var status = o.user_id;
                        
                            var act_html = "<div class='btn-group'>"     
                                            @if(CheckPermission::isPermitted('admin.constantsponsor.edit'))
                                            +    "<a title='Edit' href='{{URL::to('/')}}/admin/constantsponsor/"+ o['id']+"/edit' class='btn btn-xs btn-warning btn-flat'><i class='fa fa-edit'></i></a>"
                                            @endif
                                            if (status==null) {
                                                @if(CheckPermission::isPermitted('admin.constantsponsor.delete'))                               
                                                act_html += "<a href='javascript:void(0)' onclick='doDelete("+ o['id'] +")' data-toggle='tooltip' title='Delete User' data-placement='top' class='btn btn-xs btn-danger'><i class='fa fa-fw fa-trash-o'></i></a>"                                        
                                                @endif
                                            }
                                            +"</div>"
                            return act_html;

                        return '';
                    }
            },
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
     
    });   
    
    function doDelete(delete_id)
    {
        swal({
             title: "Are you sure want to delete",
             type: "warning",
             showCancelButton: true,   
             confirmButtonColor: "#DD6B55",   
             closeOnConfirm: true
           },
           function()
           {
                $.ajax({
                   url: "{{ URL::route('admin.constantsponsor.delete') }}",
                   type: 'post',
                   data: { id: delete_id},
                   success:function(resp)
                   { 
                     location.reload();
                   }
               });
            }
        );
    }
</script>
@stop