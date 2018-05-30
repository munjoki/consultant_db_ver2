@extends('admin.layout')

@section('content')
<div class="row">
    
    <div class="col-lg-12 col-md-12">
        <div class="row">                   
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="dg_events">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>email</th>
                                <th>first name</th>
                                <th>Last name</th>
                                <th>Mobile</th>
                                <th>Address</th>
                                <th>City</th>
                                <th>Pincode</th>
                               
                                <th width="100">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        
                        </tbody>
                        
                    </table>
                </div>
                <!-- /.table-responsive -->
                
            </div>
        </div>
    </div>
</div>


<!--Model for delete multiple not selected-->
<div id="modal-trash-not-selected" class="popup-basic bg-none mfp-with-anim mfp-hide">
    <div class="panel">
        <div class="panel-heading">
            <span class="panel-icon"><i class="fa fa-exclamation"></i></span>
            <span class="panel-title"> Error</span>
        </div>
        <div class="panel-body">
            <div class="alert alert-danger mbn">Please select the User(s) you wish to delete.</div>
        </div>
        <div class="panel-footer text-right">
            <div class="btn-group">
                <a class="btn btn-default btn-sm popup-modal-dismiss" >Cancel</a>
            </div>
        </div>
    </div>
</div>

<!--Model for delete multiple confirm-->
<div id="modal-trash-all" class="popup-basic bg-none mfp-with-anim mfp-hide">
    <div class="panel">
        <div class="panel-heading">
            <span class="panel-icon"><i class="fa fa-trash"></i></span>
            <span class="panel-title"> Delete</span>
        </div>
        <div class="panel-body">
            <div class="alert alert-danger mbn">You are about to delete the selected User.<br/>Would you like to proceed?</div>
        </div>
        <div class="panel-footer text-right">
            <div class="btn-group">
                <a id="adminusers_trash_confirm" class="btn btn-primary btn-sm popup-modal-dismiss">Ok</a>
                <a class="btn btn-default btn-sm popup-modal-dismiss" >Cancel</a>
            </div>
        </div>
    </div>
</div>

<!--Model for delete single confirm-->
<div id="modal-trash-single" class="popup-basic bg-none mfp-with-anim mfp-hide">
    <div class="panel">
        <div class="panel-heading">
            <span class="panel-icon"><i class="fa fa-trash"></i></span>
            <span class="panel-title"> Delete</span>
        </div>
        <div class="panel-body">
            <div class="alert alert-danger mbn">You are about to delete the selected User.<br/>Would you like to proceed?</div>
        </div>
        <div class="panel-footer text-right">
            <div class="btn-group">
                <a id="adminuser_trash_confirm" class="btn btn-primary btn-sm popup-modal-dismiss">Ok</a>
                <a class="btn btn-default btn-sm popup-modal-dismiss" >Cancel</a>
            </div>
        </div>
    </div>
</div>


<!--Model for restoring multiple not selected-->
<div id="modal-restore-not-selected" class="popup-basic bg-none mfp-with-anim mfp-hide">
    <div class="panel">
        <div class="panel-heading">
            <span class="panel-icon"><i class="fa fa-exclamation"></i></span>
            <span class="panel-title"> Error</span>
        </div>
        <div class="panel-body">
            <div class="alert alert-danger mbn">Please select the User you wish to restore.</div>
        </div>
        <div class="panel-footer text-right">
            <div class="btn-group">
                <a class="btn btn-default btn-sm popup-modal-dismiss" >Cancel</a>
            </div>
        </div>
    </div>
</div>

<!--Model for restoring multiple confirm-->
<div id="modal-restore-all" class="popup-basic bg-none mfp-with-anim mfp-hide">
    <div class="panel">
        <div class="panel-heading">
            <span class="panel-icon"><i class="fa fa-undo"></i></span>
            <span class="panel-title"> Restore</span>
        </div>
        <div class="panel-body">
            <div class="alert alert-alert mbn">You are about to restore the selected User.<br/>Would you like to proceed?</div>
        </div>
        <div class="panel-footer text-right">
            <div class="btn-group">
                <a id="adminusers_restore_confirm" class="btn btn-primary btn-sm popup-modal-dismiss">Ok</a>
                <a class="btn btn-default btn-sm popup-modal-dismiss" >Cancel</a>
            </div>
        </div>
    </div>
</div>

<!--Model for restoring single confirm-->
<div id="modal-restore-single" class="popup-basic bg-none mfp-with-anim mfp-hide">
    <div class="panel">
        <div class="panel-heading">
            <span class="panel-icon"><i class="fa fa-undo"></i></span>
            <span class="panel-title"> Restore</span>
        </div>
        <div class="panel-body">
            <div class="alert alert-alert mbn">You are about to restore the selected User.<br/>Would you like to proceed?</div>
        </div>
        <div class="panel-footer text-right">
            <div class="btn-group">
                <a id="adminuser_restore_confirm" class="btn btn-primary btn-sm popup-modal-dismiss">Ok</a>
                <a class="btn btn-default btn-sm popup-modal-dismiss" >Cancel</a>
            </div>
        </div>
    </div>
</div>


<!--Model for hard delete adminusers multiple not selected-->
<div id="modal-destroy-not-selected" class="popup-basic bg-none mfp-with-anim mfp-hide">
    <div class="panel">
        <div class="panel-heading">
            <span class="panel-icon"><i class="fa fa-exclamation"></i></span>
            <span class="panel-title"> Error</span>
        </div>
        <div class="panel-body">
            <div class="alert alert-danger mbn">Please select the User you wish to delete permanently.</div>
        </div>
        <div class="panel-footer text-right">
            <div class="btn-group">
                <a class="btn btn-default btn-sm popup-modal-dismiss" >Cancel</a>
            </div>
        </div>
    </div>
</div>

<!--Model for restoring multiple confirm-->
<div id="modal-destroy-all" class="popup-basic bg-none mfp-with-anim mfp-hide">
    <div class="panel">
        <div class="panel-heading">
            <span class="panel-icon"><i class="fa fa-times-circle"></i></span>
            <span class="panel-title"> Permanent Delete</span>
        </div>
        <div class="panel-body">
            <div class="alert alert-danger mbn">You are about to delete the selected User permanently.<br/>Would you like to proceed?</div>
        </div>
        <div class="panel-footer text-right">
            <div class="btn-group">
                <a id="adminusers_destroy_confirm" class="btn btn-primary btn-sm popup-modal-dismiss">Ok</a>
                <a class="btn btn-default btn-sm popup-modal-dismiss" >Cancel</a>
            </div>
        </div>
    </div>
</div>

<!--Model for restoring single confirm-->
<div id="modal-destroy-single" class="popup-basic bg-none mfp-with-anim mfp-hide">
    <div class="panel">
        <div class="panel-heading">
            <span class="panel-icon"><i class="fa fa-times-circle"></i></span>
            <span class="panel-title"> Permanent Delete</span>
        </div>
        <div class="panel-body">
            <div class="alert alert-danger mbn">You are about to delete the selected User permanently.<br/>Would you like to proceed?</div>
        </div>
        <div class="panel-footer text-right">
            <div class="btn-group">
                <a id="adminuser_destroy_confirm" class="btn btn-primary btn-sm popup-modal-dismiss">Ok</a>
                <a class="btn btn-default btn-sm popup-modal-dismiss" >Cancel</a>
            </div>
        </div>
    </div>
</div>

@stop

@section('style')
{{ HTML::style('/plugins/datatables/media/css/dataTables.bootstrap.css')}}
@stop

@section('script')
{{ HTML::script('/plugins/datatables/media/js/jquery.dataTables.min.js')}}
{{ HTML::script('/plugins/datatables/media/js/dataTables.bootstrap.js')}}
{{ HTML::script('js/donetyping.js') }}
{{ HTML::script('js/jeditable.js') }}

 <script type="text/javascript">

//     $(document).on('click', '.popup-modal-dismiss', function (e) {

//         e.preventDefault();
//         $.magnificPopup.close();
//     });

//     $(document).ready(function() {

//         /* dt_adminusers Datatable */
//         dt_adminuser = $('#dt_adminuser').dataTable({

//             dom              : '<"dt-panelmenu clearfix"lTfr>t<"dt-panelfooter clearfix"ip>',
//             "bProcessing"    : true,
//             "bServerSide"    : true,
//             "sAjaxSource"    : "{{URL::action('AdminController@index')}}",
//             "fnServerParams" : function ( aoData ) { 

//                 aoData.push({ "name" : "act", "value" : "fetch" });
//             },
//             "aaSorting"      : [[1, "asc"]],
//             "aoColumns"      : [
//                 {
//                     mData     : "id",
//                     bSortable : false,
//                     sWidth    : "30px",
//                     sClass    : 'text-center admin-form',
//                     bVisible  : true,
//                     mRender   : function (v, t, o) {

//                        return '<label class="option block mn">'
//                             +    '<input type="checkbox" id="chk_'+v+'" name="id[]" value="'+v+'">'
//                             +    '<span class="checkbox pr1"></span>'
//                             + '</label>';

//                     }
//                 },
//                 { mData : "title" },
//                 { mData : "surname" },
//                 { mData : "name" },
//                 { mData : "address" },
//                 { mData : "email" },
//                 { mData : "mobile" },
               
//                 {
//                     mData     : null,
//                     sWidth    : "120px",
//                     sClass    :"text-right",
//                     bSortable : false,
//                     mRender: function(v, t, o) {

//                         var act_html    =   "<div class='btn-group pr5'>"
//                                         +       "<a title='View' href='{{URL::to('/')}}/admin/user/"+ o['id']+"' class='btn btn-sm btn-primary light'><i class='fa fa-search'></i></a>"
//                                         +       "<a title='Edit' href='{{URL::to('/')}}/admin/user/"+ o['id']+"/edit' class='btn btn-sm light btn-system'><i class='fa fa-edit'></i></button></a>"
//                                         +       "<button type='button' value='"+o['id']+"' class='btn btn-sm btn-danger light destroy_adminuser' title='Delete'><span class='fa fa-trash'></span></button>"
//                                         +   "</div>";

//                         return act_html;
//                     }
//                 }
//             ],
//             fnDrawCallback : function (oSettings) {}
//         });
        
//         $("#dt_adminuser_wrapper .dataTables_filter input").unbind().donetyping(function() {

//             dt_adminuser.api().search(this.value).draw();
//             return;
//         });

//         $('#dt_adminuser input:checkbox[name="selectall"]').click(function() {
            
//             var is_checked = $(this).is(':checked');
//             $(this).closest('table').find('tbody tr td:first-child input[type=checkbox], thead tr th:first-child input[type=checkbox], tfoot tr th:first-child input[type=checkbox]')
//                                     .prop('checked', is_checked);
//         });
        
//         $("#dt_adminuser thead input:text").donetyping( function () {

//             dt_adminuser.fnFilter( this.value, $(" #dt_adminuser input:text").index(this) );
//         });

//         $(document).on('click', 'button.destroy_adminuser', function() {

//             $('#adminuser_destroy_confirm').attr('data-id', $(this).val());

//             $.magnificPopup.open({ items : { src : $('#modal-destroy-single') }, mainClass : 'mfp-zoomIn'});
//             return false;    
//         });

//         $(document).on('click', '#adminuser_destroy_confirm', function() {

//             var id = $(this).attr('data-id');

//             if( typeof id === 'undefined' ) return false;

//             var ele = $('button.destroy_adminuser[value="'+ id +'"]');
            
//             $.ajax({
//                 url        : "{{URL::route('admin.user.delete')}}",
//                 type       : "post",
//                 data       : {id : id, name : 'destroy'},
//                 dataType   : 'json',
//                 beforeSend : function() {

//                     ele.prop('disabled', true).html('<i class="fa fa-spin fa-spinner"></i>');
//                 },
//                 success    : function(resp) {

//                     showAlert('Success!', 'User has been deleted permanently!', 'success', '30%');
//                     dt_deleted_adminuser.fnStandingRedraw();
//                 }
//             });
//         });
     
//     });
//     /* End Restoring Single AdminUser */


    $(document).on('click', 'button.delete', function(){
        console.log($(this).val());
        var id = $(this).val();
        $.ajax({
            url : "{{URL::to('/')}}" + "/client/" + id,
            type: "delete" ,
            data: {'_method':'DELETE'},
            dataType:'html',
            success: function(resp){
                dg_events.fnStandingRedraw();
            }
        });
    });

    $.fn.dataTableExt.oApi.fnStandingRedraw = function(oSettings) {
        if(oSettings.oFeatures.bServerSide === false){
            var before = oSettings._iDisplayStart;
      
            oSettings.oApi._fnReDraw(oSettings);
      
            // <a href="//legacy.datatables.net/ref#iDisplayStart">iDisplayStart</a> has been reset to zero - so lets change it back
            oSettings._iDisplayStart = before;
            oSettings.oApi._fnCalculateEnd(oSettings);
        }
           
        // draw the 'current' page
        oSettings.oApi._fnDraw(oSettings);
    };


    $(document).ready(function(){
                

        dg_events = $('#dg_events').dataTable({
            // "sDom": "<'row-fluid'<'span6'l>r>t<'row-fluid'pi>",
            oLanguage : {
                sSearch : "Search _INPUT_",
                sLengthMenu : " _MENU_ ",
                sInfo : "_START_ to _END_ of _TOTAL_",
                sInfoEmpty : "0 - 0 of 0",
                oPaginate : {
                    // sFirst : '<i class="icon-double-angle-left"></i>',
                    // sLast : '<i class="icon-double-angle-right"></i>',
                    // sPrevious: '<i class="icon-angle-left"></i>',
                    // sNext: '<i class="icon-angle-right"></i>'
                }
            },
            "bProcessing": true,
            "bServerSide": true,
            "sAjaxSource": "{{URL::action('admin.user.index')}}",//change here
            "fnServerParams": function ( aoData ) { //send other data to server side
                aoData.push({ "name": "act", "value": "fetch" });
            },
            "aaSorting": [[1,"asc"]],
            "aoColumns": [
                {
                    mData: "id",
                    bSortable : false,
                    bVisible : false,
                    sClass: 'center'
                },
                { "mData": "title" },
                { "mData": "surname" },
                { "mData": "name" },
                { "mData": "address" },
                { "mData": "email" },
                { "mData": "mobile" },
               
                {
                    mData: null,
                    bSortable : false,
                    mRender: function(v,t,o){

                        var act_html = "<a href= '/client/"+ o['id']+"/edit" +"' class='btn btn-xs btn-info btn-minier' title='Edit'><i class='fa fa-pencil-square-o'></i></a> "
                        //+ "<form class='inline'  style='display:inline-block' accept-charset='UTF-8' action='/events/"+ o['id']+"/delete" +"' method='POST'>"

                       // + '{{ Form::hidden('_method', 'DELETE') }}'
                        +"<button type='submit' class='btn btn-danger btn-xs delete' value="+o['id']+" id='deletebtn'>"
                        +"<i class='fa fa-trash-o'></i>"
                        + "</button>"
                        //+ "</form>";     
                        return act_html;
                    }
                }
            ],
            fnPreDrawCallback: function () {
                // if (!responsiveHelper2) {
                //     responsiveHelper2 = new ResponsiveDatatablesHelper(this, breakpointDefinition);
                // }
            },
            fnRowCallback  : function (nRow) {
                //responsiveHelper2.createExpandIcon(nRow);
            },
            fnDrawCallback : function (oSettings) {
                //responsiveHelper2.respond();
                // $(this).removeAttr('style');
            }
        });
        // $("tfoot input").keyup( function () {
        //  /* Filter on the column (the index) of this element */
        //  dg_userrequest.fnFilter( this.value, $("tfoot input").index(this) );
        // } );
        
        // $("tfoot input").focus( function () {
        //  if ( this.className == "search_init" )
        //  {
        //      this.className = "";
        //      this.value = "";
        //  }
        // } );         
                
            
    
    });

// </script>
@stop
