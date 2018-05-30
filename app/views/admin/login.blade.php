<!------------------------------------------------------------------------------------------------------------------------------------------
	AKDN MER Consultant Database Version 1.0
	this file handles login to the administrator area
-------------------------------------------------------------------------------------------------------------------------------------------->
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>Consultant  | Log in</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- Bootstrap 3.3.2 -->
    {{HTML::style('assets/bootstrap/css/bootstrap.min.css')}}

    {{ HTML::style('font-awesome-4.3.0/css/font-awesome.min.css') }}
    
    <!-- Theme style -->
    {{HTML::style('assets/dist/css/AdminLTE.min.css')}}

    <!-- iCheck -->
    {{HTML::style('assets/plugins/iCheck/square/blue.css')}}
    
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
        <a href=""><b>Admin </b> | Login</a>
      </div><!-- /.login-logo -->
      <div class="login-box-body">
        {{Form::open(array('route'=>'admin.login.post','method'=>'post','id'=>'login_form'))}}
          
            @include('partials.alert')
          <div class="form-group has-feedback">
            {{ Form::email('email', Input::old('email'), array("placeholder"=>"E-mail","class"=>"form-control", "autofocus" => true)) }}
            <span class='error'>{{ $errors->first('email') }}</span>
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            {{ Form::password('password', array("placeholder"=>"Password","class"=>"form-control")) }}
            <span class='error'>{{ $errors->first('password') }}</span>
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          </div>
          @if( Session::get('login_failed_count') > 0 )
          <div class="form-group has-feedback">
            {{ Form::text('captcha', null, array("placeholder"=>"Enter Captcha","class"=>"form-control", "autofocus" => true)) }}
            <span class='error'>{{ $errors->first('captcha') }}</span>
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            <div id="captcha_div">{{ HTML::image(Captcha::img(), 'Captcha image') }}</div>
            <button id="refresh_captcha" type="button" class="btn btn-xs btn-flat btn-success">Refresh</button>
          </div>
          @endif
          <div class="row">
            <div class="col-xs-8">    
              <div class="checkbox icheck">
                <label>
                  <input type="checkbox"> Remember Me
                </label>
              </div>                        
            </div><!-- /.col -->
            <div class="col-xs-4">
              <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
            </div><!-- /.col -->
          </div>
        {{Form::close();}}

        <!-- <div class="social-auth-links text-center">
          <p>- OR -</p>
          <a href="#" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i> Sign in using Facebook</a>
          <a href="#" class="btn btn-block btn-social btn-google-plus btn-flat"><i class="fa fa-google-plus"></i> Sign in using Google+</a>
        </div><!- /.social-auth-links --> 

        <a href="{{ URL::to('/admin/password/remind') }}">I forgot my password</a><br>
        <!-- <a href="" class="text-center">Register a new membership</a> -->

      </div><!-- /.login-box-body -->
    </div><!-- /.login-box -->

    <!-- jQuery 2.1.3 -->
   {{ HTML::script('assets/plugins/jQuery/jQuery-2.1.3.min.js') }}

    {{HTML :: script ('assets/bootstrap/js/bootstrap.min.js')}}
    <!-- Bootstrap 3.3.2 JS -->
    {{HTML :: script ('assets/plugins/iCheck/icheck.min.js')}}
    <!-- iCheck -->
   
    <script>
      $(function () {
        $('input').iCheck({
          checkboxClass: 'icheckbox_square-blue',
          radioClass: 'iradio_square-blue',
          increaseArea: '20%' // optional
        });

        $("#refresh_captcha").click(function(){
            $("#captcha_div").html("<img src='{{ Captcha::img() }}', value='Captcha image'></img>");
        });
      });
    </script>

    <style type="text/css">
    span.error{ color: red;}
    </style>

  </body>
</html>