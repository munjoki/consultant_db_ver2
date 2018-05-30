@extends('admin.layout')
@section('content')
<!------------------------------------------------------------------------------------------------------------------------------------------
    AKDN MER Consultant Database Version 1.0
    this file handles editing AKDN users from admin module
-------------------------------------------------------------------------------------------------------------------------------------------->
<section class="content-header">
    <h1>Edit {{$akdn->surname}}</h1>
</section>
<section class="content">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header">
                        {{Form::open(array('route' => array('admin.akdn.update', $akdn->id),'method'=>'post','id'=>'akdn_form','prouclass'=>'form-horizontal')); }}
                        <div class="box-body">
                            @include('partials.alert')
                            <div class="col-md-12">
                                <div class="form-group clearfix">
                                    <label for="exampleInputEmail1" class="col-md-4 ">Surname <sup>*</sup></label>
                                    <div class="col-md-6">
                                        {{ Form::text('surname',$akdn->surname, array('id' => 'surname', 'class' => 'form-control ', 'placeholder' => 'Enter Surname')) }}
                                        {{ $errors->first('surname', '<span class="help-inline">:message</span>')}}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group clearfix">
                                    <label for="exampleInputEmail1" class="col-md-4 ">Other Names<sup>*</sup></label>
                                    <div class="col-md-6">
                                        {{ Form::text('other_name', $akdn->other_name, array('id' => 'other_name', 'class' => 'form-control ', 'placeholder' => 'Enter Other Names')) }}
                                        {{ $errors->first('other_name', '<span class="help-inline">:message</span>')}}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
	                            <div class="form-group clearfix">
	                                <label for="exampleInputEmail1" class="col-md-4 ">AKDN Email <sup>*</sup></label>
	                                <div class="col-md-6">
	                                    {{ Form::text('email',$akdn->email , array('id' => 'email', 'class' => 'form-control ', 'placeholder' => 'Enter Email Address')) }}
	                                    {{ $errors->first('email', '<span class="help-inline">:message</span>')}}
	                                </div>
	                            </div>
	                        </div>
	                        <div class="col-md-12">
	                            <div class="form-group clearfix">
	                                <label for="exampleInputEmail1" class="col-md-4 ">Country of Residence<sup>*</sup></label>
	                                <div class="col-md-6">
	                                    {{ Form::select('nationality[]',array(""=>"")+$country,$selected_country,array( 'id' => 'nationality','class'=>'form-control chosen-select'))}}
	                                    {{ $errors->first('nationality', '<span class="help-inline">:message</span>')}}
	                                </div>
	                            </div>
	                        </div>
	                        <div class="col-md-12">
	                            <div class="form-group clearfix">
	                                <label for="exampleInputEmail1" class="col-md-4 ">AKDN Agency Affiliation <sup>*</sup></label>
	                                <div class="col-md-6">
	                                    {{ Form::select('consultant_agencies[]',$agencies,$selected_agency, array('id' => 'consultant_agencies', 'class'=>'form-control chosen-select', 'multiple' => 'multiple', 'data-placeholder' => 'Select agencies you have previously worked with'))}}
	                                    {{ $errors->first('consultant_agencies', '<span class="help-inline">:message</span>')}}
	                                </div>
	                            </div>
	                        </div>
                            <div class="col-md-8 col-md-offset-4">
                                        <button type="submit" id="frm-submit" class="btn btn-primary">Update User </button> 
                                    <a class="btn btn-info" href="{{URL::route('admin.akdn.index')}}">Back to Users List </a>  
                                </div>
                            </div>
                        </div>
                            <div class="overlay" style="display:none;" id="frm-loader">
                                <i class="fa fa-spinner fa-spin fa-lg"></i>
                            </div>
                        {{Form::close();}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@stop
@section('script')
<script type="text/javascript">
    jQuery(document).ready(function() {
    
    	$('.chosen-select').chosen();
    
           $('#frm-submit').click(function(){
           	$(this).html('<i class="fa fa-spin fa-spinner"></i> Please wait...');
           	$('#frm-loader').show();
           	window.location.reload(true);
           });
    
           
           $('form').submit(function(){
           	//function loadingBtn(button);
           });
    
       });    
</script>
@stop