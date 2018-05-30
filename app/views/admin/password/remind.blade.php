<!------------------------------------------------------------------------------------------------------------------------------------------
	AKDN MER Consultant Database Version 1.0
	this file handles sending the reset password email for the admin
-------------------------------------------------------------------------------------------------------------------------------------------->
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>Consultant  | Admin Password Reminder</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- Bootstrap 3.3.2 -->
    {{HTML::style('assets/bootstrap/css/bootstrap.min.css')}}

    {{ HTML::style('font-awesome-4.3.0/css/font-awesome.min.css') }}
    
    <!-- Theme style -->
    {{HTML::style('assets/dist/css/AdminLTE.min.css')}}

    
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        {{ HTML::script('assets/js/html5shiv.js') }}
        {{ HTML::script('assets/js/respond.min.js') }}
    <![endif]-->
  </head>
  <body class="login-page">
    <div class="login-box">
      <div class="login-logo">
        <a href=""><b>Reset </b> | Password</a>
      </div><!-- /.login-logo -->
      <div class="box">
          <div class="login-box-body">
            {{Form::open(array('to'=>'','method'=>'post','id'=>'login_form'))}}
                @include('partials.alert')
              <div class="form-group has-feedback">
                {{ Form::email('email', Input::old('email'), array("placeholder"=>"Enter your email address to reset your password","class"=>"form-control", "autofocus" => true)) }}
                <span class='error'>{{ $errors->first('email') }}</span>
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
              </div>
              <div class="row">
                
                <div class="col-xs-12">
                  <button type="submit" class="btn btn-primary btn-block btn-flat">Submit</button>
                </div><!-- /.col -->
              </div>
            {{Form::close();}}
          </div><!-- /.login-box-body -->
          <div class="overlay" style="display:none;" id="frm-loader">
                    <i class="fa fa-spinner fa-spin fa-lg"></i>
                </div>
        </div>
    </div><!-- /.login-box -->

    <!-- jQuery 2.1.3 -->
   {{ HTML::script('assets/plugins/jQuery/jQuery-2.1.3.min.js') }}

    {{HTML :: script ('assets/bootstrap/js/bootstrap.min.js')}}
    <!-- Bootstrap 3.3.2 JS -->
   
    <script>
      $(function () {
        
        $('form').submit(function(){
            $('div.overlay').show();
        });
      });
    </script>

    <style type="text/css">
    span.error{ color: red;}
    </style>

  </body>
</html>
