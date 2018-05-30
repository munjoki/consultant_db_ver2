@extends('akdn.layout')
@section('content')
<!------------------------------------------------------------------------------------------------------------------------------------------
    AKDN MER Consultant Database Version 1.0
    this file handles awarding of a consultancy by an AKDN staff
-------------------------------------------------------------------------------------------------------------------------------------------->
<section class="content">
<div class="row">
	<div class="col-md-12">
       <div class="box box-primary box-solid">
            <div class="overlay">
                <i class="fa fa-spin fa-spinner"></i>
            </div>
            <div class="box-header with-border">
                <h3 class="box-title">Consultant Details</h3>
                <div class="box-tools pull-right">
                <!--    <a href="{{ URL::route('akdn.home') }}" class="btn btn-box-tool"><b>Back</b></a> -->
                </div>
            </div>
            @if($consultant != null)
                <div class="box-body">
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="surname">Consultant </label>
                        <div class="col-md-8">
                            @if(empty($consultant->title))
                                {{$consultant->other_names. ' ' . $consultant->surname }}
                            @else
                                {{$consultant->title .'. ' . $consultant->other_names. ' ' . $consultant->surname }}
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>
            
		<div class="box box-primary box-solid">
			<div class="overlay">
			    <i class="fa fa-spin fa-spinner"></i>
			</div>
			<div class="box-header with-border">
				<h3 class="box-title">Previous Consultancies</h3>
				<div class="box-tools pull-right">
                <a href="{{ URL::route('akdn.home') }}" class="btn btn-box-tool"><b>Back</b></a>
				</div>
			</div>
			<div class="box-body">
				<table id="dt_previous_consultancies" class="table table-bordered table-striped" cellspacing="0" width="100%">
                    <thead>
                        <tr role="row">
                            <th style=""></th>
                            <th style="width:55%;">Title of Project</th>
                            <th style="width:10%;">Start Date</th>
                            <th style="width:10%;">End Date</th>
                            <th style="width:10%;">AKDN Contact</th>
                            <th style="width:12%;">AKDN Contact's Email</th>
                            <th>Status</th>
                            <!-- <th style="">Akdn Name</th> -->
                            <!-- <th>Akdn Email</th> -->
                            <!-- <th style="">Action</th> -->
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
		
         /* Awarded Consultant */
        dt_previous_consultancies = $('#dt_previous_consultancies').dataTable({
        	"oLanguage": {
		        "sEmptyTable": "No awarded consultancies available"
		    },
        	"bPaginate" : true,
			"bLengthChange" : false,
			"bSort" : true,
			"bInfo" : true,
			"bAutoWidth" : false,
            "bProcessing" : false,
            "bServerSide" : true,
            "sAjaxSource" : "{{ URL::route('akdn.index',array(Request::segment(3))) }}",
            "aaSorting"   : [[1, "desc"]],
            "aoColumns"   : [
                {
                    mData     : "id",
                    sWidth    : "30px",
                    sClass    : 'text-center',
                    bVisible  : false,
                    bSortable : false
                },
                { 	mData : "title_of_consultancy",sClass 	: "text-left", },
                {   mData : "start_date_dmy",sClass     : "text-center", },
                { 	mData : "end_date_dmy",sClass 	: "text-center", },
                {   mData : "akdn_manager_name",sClass  : "text-left", },
                {   mData : "akdn_manager_email",sClass     : "text-left", },
                // {   mData : "name",sClass   : 'text-left' },
                // {   mData : "email",sClass    : 'text-left' },
                {   mData :"status",
                    mRender: function(v,t,o){

                        if(v==1){
                            return "<span class='label label-success'> Complete</span>";
                        }else if(v==2){

                            return "<span class='label label-danger'>Terminated</span>";
                        }else if(v==3){
                            return "<span class='label label-warning'>On-going</span>";
                        }else{
                            return "<span class='label label-warning'>Pending</span>";
                        }

                    }
                },
                // {
                //     sClass    :"text-right",
                //     bSortable : false,
                //     
                //     mRender: function(v, t, o) {

                //     	var data = JSON.stringify(o);
                //     	var act_html    =   "<div class='btn-group pr5'>"
                //                         +        "<button class='btn btn-sm btn-info btn-flat' type='button' onclick='editAwardedConsultancy("+ data +")'><span class='fa fa-edit'></span></button>"
                //                         +        "<button id='awarded_consultancy_destroy_confirm' class='btn btn-sm btn-danger btn-flat ' type='button' value='"+o['id']+"'><span class='fa fa-remove'></span></button>"
                //                         +   "</div>";

                //         return act_html;
                //     }
                // }
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

        	dt_previous_consultancies.api().draw();
        	return false;
        });

	});


</script>

@stop
