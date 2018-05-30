<!------------------------------------------------------------------------------------------------------------------------------------------
	AKDN MER Consultant Database Version 1.0
	this file handles creating new user role
-------------------------------------------------------------------------------------------------------------------------------------------->
@extends('admin.layout')
@section('content')
<section class="content-header">
    <h1>Add New Role</h1>
</section>
<section class="content">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header">
                        {{Form::open(array('route'=>'admin.role.store','method'=>'post','id'=>'akdn_form','files' => true ,'class'=>'form-horizontal')); }}
                        <div class="box-body">
                            @include('partials.alert')
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">Role Name <sup>*</sup></label>
                                    <div class="col-sm-5">
                                        {{ Form::text('role_name', Input::old('role_name'), array('id' => 'role_name', 'class' => 'form-control ', 'placeholder' => 'Enter Role Name')) }}
                                        {{ $errors->first('role_name', '<span class="help-inline">:message</span>')}}
                                    </div>
                                </div>
                            </div>
                            <span class="help-inline text-danger">{{ $errors->first('permission') }}</span>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="box box-primary box-solid">
                                        <div class="box-header with-border">
                                            <h3 class="box-title"> Consultant Options </h3>
                                            <div class="box-tools pull-right">
                                                <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                            </div>
                                        </div>
                                        <div class="box-body">
                                              @foreach($consultperm as $value)
                                                <div class="form-group">
                                                    <div class="col-xs-2">
                                                        <input type="checkbox" value="{{$value->id}}" id="permission_{{$value->id}}" name="permission[{{$value->id}}]" <?= (Input::old('$value->id')) ?'checked="checked"':'' ?> />
                                                    </div>
                                                    <label class="col-xs-10">{{$value->description}}</label>
                                                </div>
                                              @endforeach
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="box box-primary box-solid">
                                        <div class="box-header with-border">
                                            <h3 class="box-title"> Awarder Consultancy Options</h3>
                                            <div class="box-tools pull-right">
                                                <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                            </div>
                                        </div>
                                        <div class="box-body">
                                            @foreach($awardedperm as $value)
                                                <div class="form-group">
                                                    <div class="col-xs-2">
                                                        <input type="checkbox" value="{{$value->id}}" id="permission_{{$value->id}}" name="permission[{{$value->id}}]" <?= (Input::old('$value->id')) ?'checked="checked"':'' ?> />
                                                    </div>
                                                    <label class="col-xs-10">{{$value->description}}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="box box-primary box-solid">
                                        <div class="box-header with-border">
                                            <h3 class="box-title"> Akdn Options </h3>
                                            <div class="box-tools pull-right">
                                                <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                            </div>
                                        </div>
                                        <div class="box-body">
                                              @foreach($akdnperm as $value)
                                                <div class="form-group">
                                                    <div class="col-xs-2">
                                                        <input type="checkbox" value="{{$value->id}}" id="permission_{{$value->id}}" name="permission[{{$value->id}}]" <?= (Input::old('$value->id')) ?'checked="checked"':'' ?> />
                                                    </div>
                                                    <label class="col-xs-4">{{$value->description}}</label>
                                                </div>
                                              @endforeach
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="box box-primary box-solid">
                                        <div class="box-header with-border">
                                            <h3 class="box-title"> Language Options</h3>
                                            <div class="box-tools pull-right">
                                                <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                            </div>
                                        </div>
                                        <div class="box-body">
                                            @foreach($languageperm as $value)
                                                <div class="form-group">
                                                    <div class="col-xs-2">
                                                        <input type="checkbox" value="{{$value->id}}" id="permission_{{$value->id}}" name="permission[{{$value->id}}]" <?= (Input::old('$value->id')) ?'checked="checked"':'' ?> />
                                                    </div>
                                                    <label class="col-xs-4">{{$value->description}}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="box box-primary box-solid">
                                        <div class="box-header with-border">
                                            <h3 class="box-title"> Skill Options </h3>
                                            <div class="box-tools pull-right">
                                                <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                            </div>
                                        </div>
                                        <div class="box-body">
                                              @foreach($skillperm as $value)
                                                <div class="form-group">
                                                    <div class="col-xs-2">
                                                        <input type="checkbox" value="{{$value->id}}value->id}}" id="permission_{{$value->id}}" name="permission[{{$value->id}}]" <?= (Input::old('$value->id')) ?'checked="checked"':'' ?> />
                                                    </div>
                                                    <label class="col-xs-4">{{$value->description}}</label>
                                                </div>
                                              @endforeach
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="box box-primary box-solid">
                                        <div class="box-header with-border">
                                            <h3 class="box-title"> Roll Options</h3>
                                            <div class="box-tools pull-right">
                                                <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                            </div>
                                        </div>
                                        <div class="box-body">
                                            @foreach($roleperm as $value)
                                                <div class="form-group">
                                                    <div class="col-xs-2">
                                                        <input type="checkbox" value="{{$value->id}}"  id="permission_{{$value->id}}" name="permission[{{$value->id}}]" <?= (Input::old('$value->id')) ?'checked="checked"':'' ?> />
                                                    </div>
                                                    <label class="col-xs-4">{{$value->description}}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="box box-primary box-solid">
                                        <div class="box-header with-border">
                                            <h3 class="box-title"> Thematics Options </h3>
                                            <div class="box-tools pull-right">
                                                <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                            </div>
                                        </div>
                                        <div class="box-body">
                                              @foreach($specperm as $value)
                                                <div class="form-group">
                                                    <div class="col-xs-2">
                                                        <input type="checkbox" value="{{$value->id}}value->id}}" id="permission_{{$value->id}}" name="permission[{{$value->id}}]" <?= (Input::old('$value->id')) ?'checked="checked"':'' ?> />
                                                    </div>
                                                    <label class="col-xs-4">{{$value->description}}</label>
                                                </div>
                                              @endforeach
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="box box-primary box-solid">
                                        <div class="box-header with-border">
                                            <h3 class="box-title">Admin User Options</h3>
                                            <div class="box-tools pull-right">
                                                <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                            </div>
                                        </div>
                                        <div class="box-body">
                                            @foreach($userperm as $value)
                                                <div class="form-group">
                                                    <div class="col-xs-2">
                                                        <input type="checkbox" value="{{$value->id}}"  id="permission_{{$value->id}}" name="permission[{{$value->id}}]" <?= (Input::old('$value->id')) ?'checked="checked"':'' ?> />
                                                    </div>
                                                    <label class="col-xs-4">{{$value->description}}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="box box-primary box-solid">
                                        <div class="box-header with-border">
                                            <h3 class="box-title">Firewall Options</h3>
                                            <div class="box-tools pull-right">
                                                <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                            </div>
                                        </div>
                                        <div class="box-body">
                                            @foreach($firewallperm as $value)
                                                <div class="form-group">
                                                    <div class="col-xs-2">
                                                        <input type="checkbox" value="{{$value->id}}"  id="permission_{{$value->id}}" name="permission[{{$value->id}}]" <?= (Input::old('$value->id')) ?'checked="checked"':'' ?> />
                                                    </div>
                                                    <label class="col-xs-4">{{$value->description}}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="box box-primary box-solid">
                                        <div class="box-header with-border">
                                            <h3 class="box-title">Agency Options</h3>
                                            <div class="box-tools pull-right">
                                                <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                            </div>
                                        </div>
                                        <div class="box-body">
                                            @foreach($agency as $value)
                                                <div class="form-group">
                                                    <div class="col-xs-2">
                                                        <input type="checkbox" value="{{$value->id}}"  id="permission_{{$value->id}}" name="permission[{{$value->id}}]" <?= (Input::old('$value->id')) ?'checked="checked"':'' ?> />
                                                    </div>
                                                    <label class="col-xs-4">{{$value->description}}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="box box-primary box-solid">
                                        <div class="box-header with-border">
                                            <h3 class="box-title">Mail Options</h3>
                                            <div class="box-tools pull-right">
                                                <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                            </div>
                                        </div>
                                        <div class="box-body">
                                            @foreach($mails as $value)
                                                <div class="form-group">
                                                    <div class="col-xs-2">
                                                        <input type="checkbox" value="{{$value->id}}"  id="permission_{{$value->id}}" name="permission[{{$value->id}}]" <?= (Input::old('$value->id')) ?'checked="checked"':'' ?> />
                                                    </div>
                                                    <label class="col-xs-4">{{$value->description}}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-12"> 
                                <div class="form-group">  
                                    <div class="col-md-6 text-right">
                                        <button type="submit" id="frm-submit" class="btn btn-primary">Add Role </button> 
                                    </div>
                                        <a class="btn btn-info" href="{{URL::route('admin.role.index')}}">Back To RoleList </a>  
                                    </div>
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