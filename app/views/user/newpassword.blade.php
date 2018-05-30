<!------------------------------------------------------------------------------------------------------------------------------------------
	AKDN MER Consultant Database Version 1.0
	this file allows consultant to provide the new password while verifying consultant profile that has been created
-------------------------------------------------------------------------------------------------------------------------------------------->
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>Consultant | Change Password</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- Bootstrap 3.3.2 -->
    {{HTML::style('assets/bootstrap/css/bootstrap.min.css')}}
    <!-- Font Awesome Icons -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <!-- Theme style -->
    {{HTML::style('assets//dist/css/AdminLTE.min.css')}}
    <!-- iCheck -->
    {{HTML::style('assets/plugins/iCheck/flat/blue.css')}}

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
  </head>
  <body class="login-page">
    <div class="login-box">
      <div class="login-logo">
        <a href="">Update password</a>
      </div><!-- /.login-logo -->
      <div class="login-box-body">
        @include('partials.alert')
        
        {{Form::open(array('route'=>'user.newpassword.post','method'=>'post','id'=>'password_form'))}}
          <div class="form-group has-feedback">
            {{ Form::hidden('token', Session::get('token')) }}
            {{ Form::password('old_password', array("placeholder"=>"System generated password","class"=>"form-control", "autofocus" => true)) }}
            <span class='error'>{{ $errors->first('old_password') }}</span>
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            {{ Form::password('password', array("placeholder"=>"New password","class"=>"form-control")) }}
            <span class='error'>{{ $errors->first('password') }}</span>
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            {{ Form::password('password_confirmation', array("placeholder"=>"Confirm password","class"=>"form-control")) }}
            <span class='error'>{{ $errors->first('password_confirmation') }}</span>
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          </div>
          <div class="row">
            <div class="col-xs-12">
              <button type="submit" class="btn btn-primary btn-block btn-flat">Submit</button>
            </div><!-- /.col -->
          </div>
        {{Form::close();}}
      </div><!-- /.login-box-body -->
    </div><!-- /.login-box -->

    <!-- jQuery 2.1.3 -->
    {{ HTML::script('assets/plugins/jQuery/jQuery-2.1.3.min.js') }}
    <!-- Bootstrap 3.3.2 JS -->
    {{HTML::script('assets/bootstrap/js/bootstrap.min.js')}}
    
    <script>
      $(function () {
        $('form').submit(function(){
          $(this).find('button:submit').html("<i class='fa fa-spinner fa-spin '></i> Please wait...").prop('disabled', true);
        })
      });
    </script>

    <style type="text/css">
    span.error{ color: red;}
    </style>
  </body>
</html>