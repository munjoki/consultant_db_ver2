@extends('admin.layout')
@section('content')
<!------------------------------------------------------------------------------------------------------------------------------------------
    AKDN MER Consultant Database Version 1.0
    this file handles adding a new area of specialization
-------------------------------------------------------------------------------------------------------------------------------------------->
<section class="content-header">
    <h1>Add New Thematic Area</h1>
</section>
<section class="content">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header">
                        {{Form::open(array('route'=>'admin.specialization.store','method'=>'post','id'=>'akdn_form','files' => true ,'class'=>'form-horizontal')); }}
                        <div class="box-body">
                            @include('partials.alert')
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Thematic Area <sup>*</sup></label>
                                    <div class="col-sm-5">
                                        {{ Form::text('spec_des', Input::old('spec_des'), array('id' => 'spec_des', 'class' => 'form-control ', 'placeholder' => 'Enter Thematic area')) }}
                                        {{ $errors->first('spec_des', '<span class="help-inline">:message</span>')}}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-10 col-md-offset-2"> 
                                <button type="submit" id="frm-submit" class="btn btn-primary">Add Thematic Area</button>                                    
                                <a class="btn btn-info" href="{{URL::route('admin.specialization.index')}}">Back to Thematic Areas </a>  
                            </div>
                        </div>
                    </div>
                    {{Form::close();}}
                    <div class="overlay" style="display:none;" id="frm-loader">
                        <i class="fa fa-spinner fa-spin fa-lg"></i>
                    </div>
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
        });
    });    
</script>
@stop