@extends('admin.layout')
@section('content')
<!------------------------------------------------------------------------------------------------------------------------------------------
    AKDN MER Consultant Database Version 1.0
    this file handles changing the consultant user password
-------------------------------------------------------------------------------------------------------------------------------------------->
<!-- Main content -->
<section class="content">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">

            @include('partials.alert')
            <!-- general form elements -->
            {{ Form::open(array('route' => 'admin.changepassword.post', 'id' => 'frm-changepassword','role' => 'form')) }}
                <div class="box box-solid box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Change Password</h3>
                    </div>
                    <div class="box-body">
                        <br/>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-3">
                                    <label for="old_password">Current Password</label>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group">
                                        {{ Form::password('old_password', array('class'=>'form-control', 'id' => 'old_password', 'placeholder' => 'Old Password')) }}
                                        <span class="input-group-addon"><i class="fa fa-unlock-alt"></i></span>
                                    </div>
                                    @if($errors->has('old_password'))
                                    <code>{{ $errors->first('old_password') }}</code>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <br/>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-3">
                                    <label for="password">New Password</label>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group">
                                        {{ Form::password('password', array('class'=>'form-control', 'id' => 'password', 'placeholder' => 'New Password')) }}
                                        <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                                    </div>
                                    @if($errors->has('password'))
                                    <code>{{ $errors->first('password') }}</code>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <br/>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-3">
                                    <label for="password_confirmation">Confirm New Password</label>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group">
                                        {{ Form::password('password_confirmation', array('class'=>'form-control', 'id' => 'password_confirmation', 'placeholder' => 'Confirm New Password')) }}
                                        <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                                    </div>
                                    @if($errors->has('password_confirmation'))
                                    <code>{{ $errors->first('password_confirmation') }}</code>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <br/>
                    </div>
                    <div class="box-footer">
                        <div class="col-md-offset-3">
                            <button type="submit" name="save_button" class="btn btn-primary btn-flat" value="save">Save</button>
                        </div>
                    </div>
                    <div class="overlay" style="display:none;" id="frm-loader">
                        <i class="fa fa-spinner fa-spin fa-lg"></i>
                    </div>
                </div>
            {{ Form::close(); }}
        </div>
    </div>
</section>
@stop 

@section('script')
<script type="text/javascript">

jQuery(document).ready(function() {

    $('form').submit(function(){
        $(this).find('button').prop('disabled', true);
        $('div.overlay, div.loading-img').show();
    });
});
</script>
@stop