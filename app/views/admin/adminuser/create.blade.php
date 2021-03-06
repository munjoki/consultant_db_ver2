@extends('admin.layout')
@section('content')
<!------------------------------------------------------------------------------------------------------------------------------------------
    AKDN MER Consultant Database Version 1.0
    this file allows adding new users/admins and assigning them roles
-------------------------------------------------------------------------------------------------------------------------------------------->
<section class="content-header">
    <h1>Add New Administrator</h1>
</section>
<section class="content">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header">
                        {{Form::open(array('route'=>'admin.adminuser.store','method'=>'post','id'=>'akdn_form','files' => true ,'class'=>'form-horizontal')) }}
                        <div class="box-body">
                            @include('partials.alert')
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Administrator Name <sup>*</sup></label>
                                    <div class="col-sm-5">
                                        {{ Form::text('name', Input::old('name'), array('id' => 'name', 'class' => 'form-control ', 'placeholder' => 'Enter Administrator Name')) }}
                                        {{ $errors->first('name', '<span class="help-inline">:message</span>')}}
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Administrator Email <sup>*</sup></label>
                                    <div class="col-sm-5">
                                        {{ Form::text('email', Input::old('email'), array('id' => 'name', 'class' => 'form-control ', 'placeholder' => 'Enter Administrator Email')) }}
                                        {{ $errors->first('email', '<span class="help-inline">:message</span>')}}
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Administrator Password <sup>*</sup></label>
                                    <div class="col-sm-5">
                                        {{ Form::password('password', array('id' => 'password', 'class' => 'form-control ', 'placeholder' => 'Enter password')) }}
                                        {{ $errors->first('password', '<span class="help-inline">:message</span>')}}
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Select Role <sup>*</sup></label>
                                    <div class="col-sm-5">
                                        {{ Form::select('group_id',$roles, Input::old('group_id'), array('id' => 'group_id','class' => 'form-control')) }}
                                        <span class="help-inline text-danger">{{ $errors->first('group_id') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-10 col-md-offset-2"> 
                                    <button type="submit" id="frm-submit" class="btn btn-primary">Add Admin </button>                                     
                                    <a class="btn btn-info" href="{{URL::route('admin.adminuser.index')}}">Back to Admin List </a>  
                                </div>  
                            </div>
                        </div>
                        <div class="overlay" style="display:none;" id="frm-loader">
                            <i class="fa fa-spinner fa-spin fa-lg"></i>
                        </div>
                    </div>
                    {{Form::close()}}
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