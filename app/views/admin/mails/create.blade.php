@extends('admin.layout')
@section('content')
<section class="content-header">
    <h1>Send Email</h1>
</section>
<section class="content">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header">
                        {{Form::open(array('route'=>'admin.mailsend.store','method'=>'post','id'=>'mail_form','files' => true ,'class'=>'form-horizontal')) }}
                        <div class="box-body">
                            @include('partials.alert')
                            <div class="col-md-12" style="margin-left:150px">
                                <div class="form-group ">
                                    <div class="col-md-3">
                                        <label>{{ Form::checkbox('consultantselect','consultantselect',null,['class'=>'checkbox-inline ','id' => 'checkbox-consultant']) }} &nbsp All Consultants</label>
                                    </div>
									<div class="col-md-3">
                                        <label>{{ Form::checkbox('akdnselect','akdnselect',null,['class'=>'checkbox-inline ','id' => 'checkbox-akdn']) }} &nbsp All AKDN Users</label>
                                    </div>
                                    <div class="col-md-3">
                                        <label>{{ Form::checkbox('adminuserselect','adminuserselect',null,['class'=>'checkbox-inline','id' => 'checkbox-adminuser']) }} &nbsp All Administrators</label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">

                                <div id="divConsultant">
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Consultant Name</label>
                                        <div class="col-sm-8">
                                            {{ Form::select('consultant[]',$consultant, Input::old('consultant'), array('class' => 'form-control consultuser','multiple'=>true)) }}
                                            {{ $errors->first('consultant', '<span class="help-inline">:message</span>')}}
                                        </div>
                                    </div>
                                </div>
                                
								<div id="divAkdn">
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">AKDN User Name</label>
                                        <div class="col-sm-8">
                                            {{ Form::select('akdn[]',$akdnuser, Input::old('akdn'), array('id' => 'akdn','class' => 'form-control akdnUser','multiple'=>true)) }}
                                            {{ $errors->first('akdn', '<span class="help-inline">:message</span>')}}
                                        </div>
                                    </div>
                                </div>

                                <div id="divAdminuser">
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Administrator Name</label>
                                        <div class="col-sm-8">
                                            {{ Form::select('user[]',$adminuser, Input::old('user'), array('id' => 'user','class' => 'form-control adminuser','multiple'=>true)) }}
                                            {{ $errors->first('user', '<span class="help-inline">:message</span>')}}
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Subject</label>
                                    <div class="col-sm-8">
                                        {{ Form::text('subject', Input::old('subject'), array('id' => 'subject','class' => 'form-control')) }}
                                        <span class="help-inline text-danger">{{ $errors->first('subject') }}</span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Message</label>
                                    <div class="col-sm-8">
                                        {{ Form::textarea('message', Input::old('subject'), array('id' => 'message','class' => 'form-control')) }}
                                        <span class="help-inline text-danger">{{ $errors->first('message') }}</span>
                                    </div>
                                </div>

                            </div>

                            <div class="col-md-10 text-center"> 
                                    <button type="submit" id="frm-submit" class="btn btn-primary">Send</button>                                     
                                      
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
{{ HTML::script('assets/plugins/ckeditor/ckeditor.js') }}
<script type="text/javascript">
    
    CKEDITOR.config.allowedContent = true;
    CKEDITOR.config.extraAllowedContent = '*(*);*{*}';
    CKEDITOR.replace('message', {
    toolbar: [
        ["Source"],
        ["Bold","Italic","Underline"],
        ["Link","Unlink","Anchor"],
        ["Styles","Format","Font","FontSize"],
        ["TextColor","BGColor"],
        ["UIColor","Maximize","ShowBlocks"],
        "/",
        ["NumberedList","BulletedList","Outdent","Indent","Blockquote","CreateDiv","JustifyLeft","JustifyCenter","JustifyRight","JustifyBlock"],
    ]
    });

$(document).ready(function(){

    $('.consultuser').chosen();
    $('.chosen-container').width('100%');

    $('.akdnUser').chosen();
    $('.chosen-container').width('100%');

    $('.adminuser').chosen();
    $('.chosen-container').width('100%');


    $('#checkbox-akdn').click(function(){
        this.checked ? $('#divAkdn').hide():$('#divAkdn').show();

    });

    $('#checkbox-consultant').click(function(){
        this.checked ? $('#divConsultant').hide():$('#divConsultant').show();

    });

    $('#checkbox-adminuser').click(function(){
        this.checked ? $('#divAdminuser').hide():$('#divAdminuser').show();

    });


    // $('#checkbox-consultant').change(function(){
    //     if(this.checked)
    //     {
    //         $("#divConsultant").show();                
    //     } 
    //     else
    //     {
    //         $("#divConsultant").hide();
    //     }
        
    // });

    // $('#checkbox-akdn').change(function(){
    //     if(this.checked)
    //     {
    //         $("#divAkdn").show();                
    //     } 
    //     else
    //     {
    //         $("$divAkdn").hide();
    //     }
    // });


    // $('#checkbox-adminuser').change(function(){
    //     if(this.checked)
    //     {
    //         $("#divAdminuser").show();                
    //     } 
    //     else
    //     {
    //         $("#divAdminuser").hide();
    //     }
    // });

    $('form').submit(function() {
        $('#frm-submit').html('<i class="fa fa-lg fa-pulse fa-fw fa-spinner"></i> Sending...').prop('disabled',true);
    }); 

});


</script>

@stop