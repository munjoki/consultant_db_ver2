@extends('admin.layout')
@section('content')
<section class="content-header">
    <div class="row">
        <div class="col-md-12">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="javascript:void(0)">Consultant Logins</a></li>
                    @if(CheckPermission::isPermitted('admin.loginlogs.akdn'))
                    <li><a href="<?= route('admin.loginlogs.akdn') ?>">AKDN User Logins</a></li>
                    @endif
                    @if(CheckPermission::isPermitted('admin.loginlogs.admin'))
                    <li><a href="<?= route('admin.loginlogs.admin') ?>">Administrator Logins</a></li>
                    @endif
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active">
                        <div class="box box-primary box-solid">
                            <div class="box-header with-border">
                                <h3 class="box-title">Consultant Login Logs</h3>
                                <div class="pull-right box-tools">
                                    <a target="_blank" id="export_excel" href="javascript:void(0);" class="btn btn-sm btn-flat btn-primary" onclick="return ExportToExcel(this);"><i class="fa fa-file-excel-o"></i> Export To Excel</a>
                                </div>
                                <!-- /. tools -->
                            </div>
                            <div class="box-body">
                                <table id='login_logs' class="table table-condensed table-bordered table-hover" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>Consultant Name</th>
                                            <th>IP Address</th>
                                            <th>Country</th>
                                            <th>Login Time</th>
                                            <th>Logout Time</th>
                                        </tr>
                                    </thead>
                                    <thead id="filters">
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                            <div id="spin" class="overlay" style="display:none;">
                                <i class="fa fa-spin fa-spinner"></i>
                            </div>
                            <!-- /.box-body -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- /.content -->
@stop
@section('script')

<script type="text/javascript">

var server_params = '';
function ExportToExcel(ele){
    var query_string = decodeURIComponent($.param(server_params));
    console.log(query_string);
    $(ele).attr('href','{{ URL::route("admin.loginlogs.excelexport") }}?'+query_string);
    return true;
}
$(function() {

    login_logs = $('#login_logs').dataTable({
        "bProcessing": false,
        "bServerSide": true,
        "autoWidth": true,
        "bStateSave": true,
        "sAjaxSource": "{{URL::route('admin.loginlogs.user')}}",
        "oLanguage": {
          "sLengthMenu": "Display _MENU_ records"
        },
        "fnServerParams": function ( aoData ) {
            var form_data = $('#frm_filter').serializeArray();

            $.each(form_data, function(i, val){
                aoData.push(val);
            });
            aoData.push({ "name": "act", "value": "fetch" });
            aoData.push({ "name": "user_type", "value": "user"});
            server_params = aoData;
        },
        "aaSorting": [
            [0, "asc"]
        ],
        "aoColumns": [
            { "mData": "username",sWidth: "10%" },
            { "mData": "ip_address",sWidth: "25%" },
            { "mData": "country",sWidth: "25%" },
            { "mData": "login_time",sWidth: "20%" },
            { "mData": "logout_time",sWidth: "20%" },
        ],
        fnPreDrawCallback: function() {
            $("div.overlay").show();
        },
        fnDrawCallback: function(oSettings) {
            $("div.overlay").hide();
        }
    });

    $("#login_logs_wrapper .dataTables_filter input").unbind().donetyping(function() {
    
        login_logs.api().search(this.value).draw();
        return;
    });

    $("#login_logs thead#filters input:text").donetyping(function() {

            login_logs.fnFilter( this.value, $(" #login_logs input:text").index(this));
    });
});
</script>
@stop