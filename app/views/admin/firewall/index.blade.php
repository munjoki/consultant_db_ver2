@extends('admin.layout')
@section('content')
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">Firewall </h3>
                    <div class="box-tools pull-right">
                        <div class="box-tools pull-right">
                            @if(CheckPermission::isPermitted('admin.firewall.block'))
                            <a class="btn btn-primary" onclick="blockIp();" data-toggle="tooltip" title="Block Ips" href="javascript:void(0)"> Block IP</a>
                            @endif
                            @if(CheckPermission::isPermitted('admin.firewall.unblock'))
                            <a class="btn btn-primary" onclick="unblockIp();" data-toggle="tooltip" title="Unblock Ips" href="javascript:void(0)"> Unblock IP</a>
                            @endif
							@if(CheckPermission::isPermitted('admin.firewall.delete'))
                            <a id="firewall_destroy_confirm" class="btn btn-primary">Delete All</a>
                            @endif
                            @if(CheckPermission::isPermitted('admin.firewall.excelexport'))
                            <a target="_blank" id="export_excel" href="javascript:void(0);" class="btn btn-sm btn-flat btn-primary" onclick="return ExportToExcel(this);"><i class="fa fa-file-excel-o"></i> Export To Excel</a>
                            @endif
                            <a href="javascript:void(0)" class="btn btn-default btn-sm dark" title="Refresh" onclick="javascript:dt_firewall.fnStandingRedraw();"><i class="fa fa-refresh"></i></a>
                        </div>
                    </div>
                </div>
                <div class="box-body">
                    <table id="dt_firewall" class="table-condensed table" cellspacing="0" width="100%">
                        <thead>
                            <tr role="row">
                                <th style=""><input type="checkbox" value="CH" name="selectall"></th>
                                <th style="">IP Address</th>
                                <th style="">Firewall Status</th>
                                <th style="">Action</th>
                            </tr>
                        </thead>
                        <thead>
                            <tr>
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
        $(ele).attr('href','{{ URL::route("admin.firewall.excelexport") }}?'+query_string);
        return true;
    }

function blockIp(block_id){
    if (block_id == undefined) {
        var delete_ele = $('#dt_firewall tbody input[type=checkbox]:checked');

        var block_id = [];
        $.each(delete_ele, function(i, ele) {
            block_id.push($(ele).val());
        });
    }
    if (0 == block_id) {
        bootbox.alert("Please Select a Record to Proceed!");
        return false;
    } else {
        $.ajax({
            url: "{{route('admin.firewall.block')}}",
            type: 'post',
            dataType: 'json',
            data: {
                id: block_id,
            },
            beforeSend: function() {
                $('#spin').show();
            },
            complete: function() {
                $('#spin').hide();
                var redrawtable = $('#dt_firewall').dataTable();
                redrawtable.fnDraw();
            },
            // success: function(respObj) {
            //     if (respObj) {
            //         $('#dt_firewall-update-success').show().delay(3000).fadeOut('slow');
            //     }
            // }
        });
    }
}

function unblockIp(){

    var delete_ele = $('#dt_firewall tbody input[type=checkbox]:checked');

    var block_id = [];
    $.each(delete_ele, function(i, ele) {
        block_id.push($(ele).val());
    });
    if (0 == block_id) {
        bootbox.alert("Please Select a Record to Proceed!");
        return false;
    } else {
        $.ajax({
            url: "{{route('admin.firewall.unblock')}}",
            type: 'post',
            dataType: 'json',
            data: {
                id: block_id,
            },
            beforeSend: function() {
                $('#spin').show();
            },
            complete: function() {
                $('#spin').hide();
                var redrawtable = $('#dt_firewall').dataTable();
                redrawtable.fnDraw();
            },
            // success: function(respObj) {
            //     if (respObj) {
            //         $('#dt_firewall-update-success').show().delay(3000).fadeOut('slow');
            //     }
            // }
        });
    }
}

jQuery(document).ready(function() {
         /* Role Permission */

    var server_params = '';
    function ExportToExcel(ele){
        var query_string = decodeURIComponent($.param(server_params));
        $(ele).attr('href','{{ URL::route("admin.firewall.excelexport") }}?'+query_string);
        return true;
    }

        dt_firewall = $('#dt_firewall').dataTable({
            "bProcessing" : false,
            "bServerSide" : true,
            "sAjaxSource" : "{{ URL::route('admin.firewall.index') }}",
            "aaSorting"   : [[1, "desc"]],
            "fnServerParams": function ( aoData ) {
                aoData.push({ "name": "act", "value": "fetch" });
                server_params = aoData;
            },
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
                {   mData : "ip_address",sClass   : 'text-left' },
                {
                    mData : 'whitelisted',
                    bSortable : false,
                    sClass : 'text-center',
                    mRender : function(v,t,o){

                        if (v == 0) {
                            return '<div class="badge bg-red">Blocked</div>';
                        }

                        return '<div class="badge bg-green">Active</div>';
                        
                    }
                },
                {
                    sClass    :"text-center",
                    bSortable : false,
                    mData     : null,
                    mRender: function(v, t, o) {
    
                        var data = JSON.stringify(o);
                        var act_html = "<div class='btn-group'>";

                        @if(CheckPermission::isPermitted('admin.firewall.delete'))
                            act_html += "<button title='Delete' onclick=\"DeleteFirewall(" + o['id'] + ")\" class='btn btn-sm btn-danger btn-flat ' type='button' value='"+o['id']+"'><span class='fa fa-remove'></span></button>"
                        @endif

                        @if(CheckPermission::isPermitted('admin.firewall.block') AND CheckPermission::isPermitted('admin.firewall.unblock'))
                            if (o['whitelisted'] == 0) {
                                act_html += "<a id="+o['whitelisted']+" href='javascript:void(0);'  onclick=\"blockIp(" + o['id'] + ")\" class='btn btn-sm btn-success btn-flat '>UnBlock</a>";
                            } else {                                
                                act_html += "<a id="+o['whitelisted']+" href='javascript:void(0);'  onclick=\"blockIp(" + o['id'] + ")\" class='btn btn-sm btn-danger btn-flat '>Block</a>";
                            }
                        @endif

                        act_html += "</div>";
    
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
    
            dt_firewall.api().draw();
            return false;
        });

    $('#firewall_destroy_confirm').click(function() {
            
        var ele = $('#dt_firewall tbody input[type=checkbox]:checked');
        var btn_html = ele.html();

        var destroy_ele = $('#dt_firewall tbody input[type=checkbox]:checked');
        var destroy_id = [];
        $.each(destroy_ele, function(i, ele){
            destroy_id.push($(ele).val());
        });

        if(destroy_id.length > 0){

            bootbox.confirm("Are you sure you want to Unblock\\Delete the Ip Addresses? This action cannot be undone.", function(result) {

                if(result == true)
                {
                    $.ajax({
                        url : "{{URL::route('admin.firewall.delete')}}",
                        type : 'delete',
                        dataType : 'json',
                        data : { id : destroy_id, name : 'destroyall'},
                        beforeSend : function() {
                            $("div.overlay").show();
                            ele.prop('disabled', true).html('<i class="fa fa-spin fa-spinner"></i>');
                        },
                        complete : function() {
                            $("div.overlay").hide();
                            ele.prop('disabled', false).html(btn_html); 
                        },
                        success : function(resp) {
                            dt_firewall.fnStandingRedraw();
                            $('#dt_firewall input:checkbox[name="selectall"]').prop('checked', false);
                        }
                    });
                } else {
                    dt_firewall.fnStandingRedraw();
                }
            });
        } else {
            bootbox.alert("Please Choose Any Record to Delete!", function() {
              dt_firewall.fnStandingRedraw();
            });
        }   
    });
    
});

$("#dt_firewall .dataTables_filter input").unbind().donetyping(function() {

    dt_firewall.api().search(this.value).draw();
    return;
});

$('#dt_firewall input:checkbox[name="selectall"]').click(function() {
    
    var is_checked = $(this).is(':checked');
    $(this).closest('table').find('tbody tr td:first-child input[type=checkbox], thead tr th:first-child input[type=checkbox], tfoot tr th:first-child input[type=checkbox]')
                            .prop('checked', is_checked);
});

$("#dt_firewall thead#filters input:text").donetyping(function() {

    dt_firewall.fnFilter( this.value, $(" #dt_firewall input:text").index(this));
});

function DeleteFirewall(id) {
    
    bootbox.confirm("Are you sure you want to Unblock\\Delete the Ip Address? This action cannot be undone.", function(result) {
    
        if( typeof id === 'undefined' && id == 0  ) return false;
        
        if(result == true){

            $.ajax({
                url : "{{ URL::route('admin.firewall.delete') }}",
                type : "DELETE",
                data : {id : id, name : 'destroy'},
                dataType : 'json',
                beforeSend : function() {
                    $("div.overlay").show();
                },
                success : function(resp) {
                    dt_firewall.fnStandingRedraw();
                    $("div.overlay").hide();
                }
            });

        } else {
            return true;
            dt_firewall.fnStandingRedraw();
        }
    });

} 

 
$("#dt_firewall thead input").donetyping( function () {
    /* Filter on the column (the index) of this element */
    dt_firewall.fnFilter( this.value, $("#dt_firewall thead input").index(this));
} );

$("#dt_firewall thead input").focus( function () {
    if ( this.className == "form-control" )
    {
        //this.className = "";
        this.value = "";
    }
});
    
</script>
@stop