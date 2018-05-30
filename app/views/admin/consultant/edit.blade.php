@extends('admin.layout')
@section('content')
<!------------------------------------------------------------------------------------------------------------------------------------------
    AKDN MER Consultant Database Version 1.0
    this file handles editing existing consultant details
-------------------------------------------------------------------------------------------------------------------------------------------->
<section class="content-header">
    <h1>
        Edit {{$user->consultant->surname}}       
    </h1>
</section>
<section class="content">
    <div class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header">
                    @include('partials.alert')
                    <div class="box no-border">
                        <div class="register-box-body">
                            <div class="row">
                                <div class="col-md-12">
                                    {{Form::model( $user->consultant, array('route'=>'admin.consultant.update','method'=>'post','id'=>'profile_update','files' => true, 'class' => 'form-horizontal')); }}
                                    {{Form::hidden('user_id',$user->id)}}
                                    <fieldset>
                                        <legend>
                                            <h4> <i class="fa fa-user"></i> Personal Details <small class="help-inline pull-right">* Required</small></h4>
                                        </legend>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label" for="surname">Surname <span class="help-inline">*</span></label>
                                                    <div class="col-md-8">
                                                        {{ Form::text('surname', Input::old('surname'), array('id' => 'surname', 'class' => 'form-control', 'placeholder' => 'Enter surname')) }}
                                                        {{ $errors->first('surname', '<span class="help-inline">:message</span>')}}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label" for="other_names">First/Given Name(s) <span class="help-inline">*</span></label>
                                                    <div class="col-md-8">
                                                        {{ Form::text('other_names', Input::old('other_names'), array('id' => 'other_names', 'class' => 'form-control', 'placeholder' => 'Enter first/given name(s)')) }}
                                                        {{ $errors->first('other_names', '<span class="help-inline">:message</span>')}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label" for="nationality[]">Nationality(ies) <span class="help-inline">*</span></label>
                                                    <div class="col-md-8">
                                                        {{ Form::select('nationality[]',array('Select your nationality')+$country, $consultant_nationalities,array( 'id' => 'nationality','class'=>'form-control chosen-select', 'multiple' => true,'data-placeholder' => 'Select your nationality(ies)'))}}
                                                        {{ $errors->first('nationality', '<span class="help-inline">:message</span>')}}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label" for="gender">Gender <span class="help-inline">*</span></label>
                                                    <div class="col-md-8">
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <label for="gender">
                                                                {{ Form::radio('gender','1', (Input::old('gender') == '1'), array('id'=>'male', 'class' => 'gender')) }} Male</i>
                                                                </label>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label for="gender">
                                                                {{ Form::radio('gender','2', (Input::old('gender') == '2'), array('id'=>'female', 'class' => 'gender')) }} Female</i>
                                                                </label>
                                                            </div>
                                                            <div class="clearfix"></div>
                                                            {{ $errors->first('gender', '<span class="help-inline">:message</span>')}}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label" for="consultant type">Consultant Type <span class="help-inline">*</span></label>
                                                    <div class="col-md-8">                                                  
                                                        {{ Form::select('consultant_type', array( '' => 'Select consultant type' ,
                                                        'Independent Consultant' => 'Independent consultant',
                                                        'Institution-affiliated' => 'Institution-affiliated',
                                                        'both' => 'Both independent and institution-affiliated'),
                                                        Input::old('consultant_type'), array('id' => 'consultant_type','class' => 'form-control')) }}
                                                        {{ $errors->first('consultant_type', '<span class="help-inline">:message</span>')}}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label" for="companyname">Institution Name <span class="help-inline">*</span></label>
                                                    <div class="col-md-8">      
                                                        {{ Form::text('company_name', Input::old('company_name'), array('id' => 'companyname', 'class' => 'form-control', 'placeholder' => 'Enter institution name')) }}
                                                        {{ $errors->first('company_name', '<span class="help-inline">:message</span>')}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label" for="nationality[]">Nationality(ies) <span class="help-inline">*</span></label>
                                                    <div class="col-md-8">
                                                        {{ Form::select('nationality[]',array('Select your nationality')+$country, $consultant_nationalities,array( 'id' => 'nationality','class'=>'form-control chosen-select', 'multiple' => true,'data-placeholder' => 'Select your nationality(ies)'))}}
                                                        {{ $errors->first('nationality', '<span class="help-inline">:message</span>')}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div> -->
                                    </fieldset>
                                    <br/>
                                    <fieldset>
                                        <legend>
                                            <h4> <i class="fa fa-envelope"></i> Contact Details</h4>
                                        </legend>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label" for="email">Email <span class="help-inline">*</span></label>
                                                    <div class="col-md-8">
                                                        <div class="input-group">
                                                            {{ Form::text('email', Input::old('email'), array('id' => 'email', 'class' => 'form-control', 'placeholder' => 'Enter primary email address','disabled' => true)) }}
                                                            <span class="input-group-addon">@</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label" for="alternate_email">Alternate Email </label>
                                                    <div class="col-md-8">
                                                        <div class="input-group">
                                                            {{ Form::text('alternate_email', Input::old('alternate_email'), array('id' => 'alternate_email', 'class' => 'form-control', 'placeholder' => 'Enter alternate email address')) }}
                                                            <span class="input-group-addon">@</span>
                                                        </div>
                                                        {{ $errors->first('alternate_email', '<span class="help-inline">:message</span>')}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label" for="telno">Tel. (inc. country code) </label>
                                                    <div class="col-md-8">
                                                        <div class="input-group">
                                                            <div class="input-group-addon">
                                                                <i class="fa fa-plus"></i>
                                                            </div>
                                                            {{ Form::text('telno', Input::old('telno'), array('id' => 'telno', 'class' => 'form-control', 'placeholder' => 'Enter Tel No e.g. +41229097300')) }}
                                                            <div class="input-group-addon">
                                                                <i class="fa fa-mobile"></i>
                                                            </div>
                                                        </div>
                                                        {{ $errors->first('telno', '<span class="help-inline">:message</span>')}}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label" for="alternate_telno">Skype Id </label>
                                                    <div class="col-md-8">
                                                        {{ Form::text('skypeid', Input::old('skypeid'), array('id' => 'skypeid', 'class' => 'form-control', 'placeholder' => 'Enter Skype Id')) }}
                                                        {{ $errors->first('skypeid', '<span class="text-danger">:message</span>')}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                    <br/>
                                    <fieldset>
                                        <legend>
                                            <h4> <i class="fa fa-mortar-board"></i> Skills and Competencies</h4>
                                        </legend>
                                        <div class="box box-default box-solid">
                                            <div class="box-header with-border">
                                                <h5 class="box-title">Language(s)</h5>
                                            </div>
                                            <div class="box-body no-padding no-border">
                                                {{ $errors->first('languages', '
                                                <p><span class="help-inline text-center">:message</span></p>
                                                ')}}
                                                <div class="language_row row border-bottom">
                                                    <div class="col-md-12">
                                                        <table class="table table-bordered table-responsive">
                                                            <thead>
                                                                <tr>
                                                                    <th width="18%" class="text-center">Language</th>
                                                                    <th width="18%" class="text-center">Speaking</th>
                                                                    <th width="18%" class="text-center">Reading</th>
                                                                    <th width="18%" class="text-center">Writing</th>
                                                                    <th width="18%" class="text-center">Understanding</th>
                                                                    <th width="10%"></th>
                                                                </tr>
                                                                <tr id="language_row">
                                                                    <td>{{ Form::select('language',array(""=>"Select") + $language , '0', array('class'=>'form-control', 'id' => 'language'))}}</td>
                                                                    <td>{{ Form::select('speaking_level', Config::get('language-level'), null, array('class'=>'form-control', 'id' => 'speaking_level'))}}</td>
                                                                    <td>{{ Form::select('reading_level', Config::get('language-level'), null, array('class'=>'form-control', 'id' => 'reading_level'))}}</td>
                                                                    <td>{{ Form::select('writing_level', Config::get('language-level'), null, array('class'=>'form-control', 'id' => 'writing_level'))}}</td>
                                                                    <td>{{ Form::select('understanding_level', Config::get('language-level'), null, array('class'=>'form-control', 'id' => 'understanding_level'))}}</td>
                                                                    <td>
                                                                        <a id="plus_language" class="btn btn-success btn-flat btn-xs"><i class="fa fa-plus"></i></a>
                                                                        <a id="minus_language" class="btn btn-danger btn-flat btn-xs" style="display:none"><i class="fa fa-times"></i></a>
                                                                    </td>
                                                                </tr>
                                                            </thead>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label" for="specialization[]">Thematic Area(s)</label>
                                                    <div class="col-md-8">
                                                        {{ Form::select('specialization[]',array(""=>"")+$specialization, $consultant_specialization,array('id' => 'specialization', 'class'=>'form-control chosen-select', 'multiple' => 'true', 'data-placeholder' => 'Select relevant thematic area'))}}
                                                        {{ $errors->first('specialization', '<span class="help-inline">:message</span>')}}
                                                    </div>
                                                </div>
                                            </div>
											<div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label" for="skills[]">Skill(s) <span class="help-inline">*</span></label>
                                                    <div class="col-md-8">
                                                        {{ Form::select('skill[]',array(""=>"")+$skills, $consultant_skills,array('id' => 'skills', 'class'=>'form-control chosen-select', 'multiple' => 'true', 'data-placeholder' => 'Select relevant skills'))}}
                                                        {{ $errors->first('skill', '<span class="help-inline">:message</span>')}}
                                                    </div>
                                                </div>
                                            </div>
                                            
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label" for="country_worked[]">International Experience</label>
                                                    <div class="col-md-8">
                                                        {{ Form::select('country_worked[]',array(""=>"")+$country_worked, $consultant_workedcountries, array('id' => 'country_worked', 'class'=>'form-control chosen-select', 'multiple' => 'multiple', 'data-placeholder' => "Select the countries you've worked in"))}}
                                                        {{ $errors->first('country_worked', '<span class="help-inline">:message</span>')}}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label" for="agency_affiliations[]">AKDN Agency Experience</label>
                                                    <div class="col-md-8">
                                                        {{ Form::select('consultant_agencies[]',array(""=>"")+$agencies, $consultant_agencies, array('id' => 'consultant_agencies', 'class'=>'form-control chosen-select', 'multiple' => 'multiple', 'data-placeholder' => 'Select agencies you have previously worked with'))}}
                                                        {{ $errors->first('consultant_agencies', '<span class="help-inline">:message</span>')}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label" for="linkedin_url">LinkedIn Profile</label>
                                                    <div class="col-md-8">
                                                        {{ Form::text('linkedin_url', Input::old('linkedin_url'), array('id' => 'linkedin_url', 'class' => 'form-control', 'placeholder' => 'Enter LinkedIn URL')) }}
                                                        {{ $errors->first('linkedin_url', '<span class="help-inline">:message</span>')}}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label" for="website_url">Personal Website/Blog</label>
                                                    <div class="col-md-8">
                                                        {{ Form::text('website_url', Input::old('website_url'), array('id' => 'website_url', 'class' => 'form-control', 'placeholder' => 'Enter website/blog URL')) }}
                                                        {{ $errors->first('website_url', '<span class="help-inline">:message</span>')}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @if(isset($user->consultant->resume) || $user->consultant->resume != null)
                                        <div class="row" id="div_delete_resume">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label" for="resume">Last Updated Resume</label>
                                                    <div class="col-md-8">
                                                        @if(isset($user->consultant->resume) || $user->consultant->resume != "")
                                                        <a href="{{ URL::to('upload/resume') }}/{{$user->consultant->resume}}" target="_blank" class="btn btn-success btn-flat btn-xs"><i class="fa fa-download"></i> Download Resume</a>
                                                        <a href="#" onclick="return false;" id="resume_delete" target="_blank" class="btn btn-danger btn-flat btn-xs"><i class="fa fa-trash-o"></i></a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label" for="resume">Upload Resume</label>
                                                    <div class="col-md-8">
                                                        {{ Form::file('resume') }}
                                                        {{ $errors->first('resume', '<span class="help-inline">:message</span>')}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                    <fieldset>
                                        <legend></legend>
                                        <div class="row">
                                            <div class="col-md-12 text-center">
                                                <button type="submit" id="consultant_edit" class="btn btn-primary btn-flat">Update Consultant</button>
                                                <a href="{{ URL::route('admin.user.index') }}" id="frm-cancel" class="btn btn-info btn-flat">Back to Consultants List</a>
                                            </div>
                                    </fieldset>
                                    {{ Form::close() }}
                                    </div>
                                </div>
                            </div>
                            <div class="overlay" style="display:none;" id="frm-loader">
                                <i class="fa fa-spinner fa-spin fa-lg"></i>
                            </div>
                            
                        </div>
</section>
{{Form::close();}}
</div>
</div>
</div>
</div>
<div class="overlay" id="frm-loader" style="display:none">
    <i class="fa fa-spinner fa-spin fa-lg"></i>
</div>
</div>
</section>  
@stop
@section('script')
{{ HTML::script('assets/js/jquery-dynamic-form.js') }}
{{ HTML :: script ('assets/plugins/iCheck/icheck.min.js') }}
<script type="text/javascript">
    $('#resume_delete').click(function(){
        bootbox.confirm("Are you sure you want to delete the current resume? This action cannot be undone.", function(result)
        {
            if (result == true) {
                $.ajax({
                    url: "{{URL::route('admin.resume.delete',array($user->consultant->id))}}",
                    type: "POST",
                    dataType: "json",
                    beforeSend: function() {
                        $('#frm-loader').show();
                    },
                    success: function(resp) {
                        $('#frm-loader').hide();
                        $('#div_delete_resume').hide();
                    }
                });
            } else {
                return true;
            }
        });
    });
    
    jQuery(document).ready(function() {
    
    var dynamic_languages = $("#language_row").dynamicForm("#plus_language", "#minus_language", {
           limit:100,
           formPrefix :'languages',
           normalizeFullForm: false
       });
    
    @if(Input::old('languages.languages') && count(Input::old('languages.languages')) > 0)
    dynamic_languages.inject( {{ json_encode(Input::old('languages.languages')) }} );
    @elseif( count($load_consultant_languages) > 0 )
    dynamic_languages.inject( {{ json_encode($load_consultant_languages) }} );
    @endif
    
       $('input[name="gender"]').iCheck({ radioClass: 'iradio_square-blue' });
    
        $('#terms_conditions').iCheck({ checkboxClass: 'icheckbox_square-blue' });
    
       $('.chosen-select').chosen();
    
       $('#frm-cancel').click(function(){
        $(this).html('<i class="fa fa-spin fa-spinner"></i> Please wait...');
        $('#frm-loader').show();
       });
    
       $('form').submit(function(){
        $(this).find('#consultant_edit').html('<i class="fa fa-spin fa-spinner"></i> Please wait...');
        $('#frm-loader').show();
       });
    
       $(document).on('change', '#title', function(){
    
           if($('#title').val() == 'Mr') {
            $('#male').iCheck('check');
               $('#male').iCheck('enable'); 
               
               $('#female').iCheck('disable');
            $("#female").iCheck('uncheck');
    
           } 
           else {
    
            $('#female').iCheck('check');
               $('#female').iCheck('enable'); 
               
               $('#male').iCheck('disable');
            $("#male").iCheck('uncheck');
           } 
    });
       
       if ( ($(window).height() + 100) < $(document).height() ) {
        $('#top-link-block').removeClass('hidden').affix({
            // how far to scroll down before link "slides" into view
            offset: {top:100}
        });
    }
    }); 
    
</script>
@stop
@section('style')
<!-- iCheck -->
{{HTML::style('assets/plugins/iCheck/square/blue.css')}}
<style type="text/css">
    .help-inline { color: red; }
    .box .overlay>.fa { font-size: 50px; }
    body #canvas-wrapper {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    width: 100%;
    height: 100%;
    }
    .login-page, .register-page { background: #7E94A2; }
    #top-link-block.affix-top {
    position: absolute; /* allows it to "slide" up into view */
    bottom: -82px; /* negative of the offset - height of link element */
    right: 10px; /* padding from the left side of the window */
    }
    #top-link-block.affix {
    position: fixed; /* keeps it on the bottom once in view */
    bottom: 18px; /* height of link element */
    right: 10px; /* padding from the left side of the window */
    }
</style>
@stop