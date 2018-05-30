@extends('admin.layout')
@section('content')
<!------------------------------------------------------------------------------------------------------------------------------------------
    AKDN MER Consultant Database Version 1.0
    this file handles editing the current database users and assigning them roles
-------------------------------------------------------------------------------------------------------------------------------------------->
<section class="content-header">
    <h1>Edit Invited Consultant</h1>
</section>
<section class="content">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header">
                        {{Form::open(array('route'=>array('admin.constantsponsor.update',$id),'method'=>'post','files' => true ,'class'=>'form-horizontal')); }}
                        <div class="box-body">
                            @include('partials.alert')
                            <div class="col-md-12">

                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Consultant Name</label>
                                    <div class="col-sm-5">
                                        {{ Form::text('name', Input::old('name',$consultantSponsor['name']), array('id' => 'name', 'class' => 'form-control ', 'placeholder' => 'Enter Consultant Name ')) }}
                                        {{ $errors->first('name', '<span class="help-inline">:message</span>')}}
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Invited Consultant Email</label>
                                    <div class="col-sm-5">
                                        {{ Form::text('email', Input::old('email',$consultantSponsor['email']), array('id' => 'email', 'class' => 'form-control ', 'placeholder' => 'Enter Consultant Email ')) }}
                                        {{ $errors->first('email', '<span class="help-inline">:message</span>')}}
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Register With Email</label>
                                    <div class="col-sm-5">
                                        {{ Form::select('registeremail',$consultant_emails,Input::old('registeremail'), array('id' => 'consultantemail','class' => 'form-control')) }}

                                        {{ $errors->first('registeremail', '<span class="help-inline">:message</span>')}}
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Invited By</label>
                                    <div class="col-sm-5">
                                        {{ Form::select('akdn_id',$all_akdn,$akdn, array('id' => 'akdn_id','class' => 'form-control')) }}
                                        {{ $errors->first('akdn_id', '<span class="help-inline">:message</span>')}}
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Invited On Behalf Of</label>
                                    <div class="col-sm-5">
                                        {{ Form::text('invited_on_behalf', Input::old('invited_on_behalf',$consultantSponsor['invited_on_behalf']), array('id' => 'email', 'class' => 'form-control ', 'placeholder' => 'Enter Invited On Behalf ')) }}
                                        {{ $errors->first('invited_on_behalf', '<span class="help-inline">:message</span>')}}
                                    </div>
                                </div>
                                <!-- <div class="form-group">
                                    <label class="col-sm-2 control-label">Message By</label>
                                    <div class="col-sm-5">
                                        {{ Form::textarea('message_by', Input::old('message_by',$consultantSponsor['message_by']), array('id' => 'email', 'class' => 'form-control ', 'rows'=>3,'placeholder' => 'Enter message_by ')) }}
                                        {{ $errors->first('message_by', '<span class="help-inline">:message</span>')}}
                                    </div>
                                </div>
                                 -->
                            </div>
                            <div class="col-md-10 col-md-offset-2"> 
                                        <button class="btn btn-primary" type="submit">Edit</button>
                                        <a class="btn btn-info" href="{{URL::route('admin.constantsponsor.index')}}">Back to Invited Consultants List</a>  
                                </div>  
                            </div>
                        </div>
                        <div class="overlay" style="display:none;" id="frm-loader">
                            <i class="fa fa-spinner fa-spin fa-lg"></i>
                        </div>
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
    
        $('#frm-submit').click(function(){
            $(this).html('<i class="fa fa-spin fa-spinner"></i> Please wait...');
            $('#frm-loader').show();
        });
    });    

    $('#consultantemail').chosen();
</script>
@stop