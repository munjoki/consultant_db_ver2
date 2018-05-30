<!------------------------------------------------------------------------------------------------------------------------------------------
	AKDN MER Consultant Database Version 1.0
	this file handles the resetting of the admin password
-------------------------------------------------------------------------------------------------------------------------------------------->
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>Consultant  | Admin Password Reset</title>
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
        <a href="javascript:void(0)"><b>Reset </b> | Password</a>
      </div><!-- /.login-logo -->
      <div class="box">
          <div class="login-box-body">
            {{Form::open(array('to'=>'','method'=>'post','id'=>'login_form'))}}
              
                @include('partials.alert')
                <input type="hidden" name="token" value="{{ $token }}">
              <div class="form-group has-feedback">
                <input type="email" name="email" id="email" class="form-control" placeholder="Your Email Address">
                <span class='error'>{{ $errors->first('email') }}</span>
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
              </div>
              <div class="form-group has-feedback">
                <input type="password" name="password" id="password" class="form-control" placeholder="New password">
                <span class='error'>{{ $errors->first('password') }}</span>
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
              </div>
              <div class="form-group has-feedback">
                <input type="password" name="password_confirmation" class="form-control" id="password_confirmation" placeholder="Confirm new password">
                <span class='error'>{{ $errors->first('password') }}</span>
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
              </div>
              <div class="row">
                
                <div class="col-xs-12">
                  <button type="submit" class="btn btn-primary btn-block btn-flat">Reset</button>
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