<!------------------------------------------------------------------------------------------------------------------------------------------
	AKDN MER Consultant Database Version 1.0
	this file renders the consultant login page
-------------------------------------------------------------------------------------------------------------------------------------------->
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>Consultant | Log in</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- Bootstrap 3.3.2 -->
    <link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- Font Awesome Icons -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <!-- Theme style -->
    <link href="assets/dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
    <!-- iCheck -->
    <link href="assets/plugins/iCheck/square/blue.css" rel="stylesheet" type="text/css" />

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
        <a href=""><b>Consultant</b> | Login</a>
      </div><!-- /.login-logo -->
      <div class="login-box-body">
        {{Form::open(array('route'=>'login.post','method'=>'post','id'=>'login_form'))}}

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
            <span class='error'>{{ $errors->first('captcha') }}</span><br>
            <div id="captcha_div">{{ HTML::image(Captcha::img(), 'Captcha image') }}
              <button id="refresh_captcha" type="button" class="btn btn-xs btn-flat btn-success" style="margin-left:10px;"><i class="fa fa-refresh"></i></button>
            </div>
            
          </div>
          @endif
          <div class="row">
            <div class="col-xs-8">
              <div class="checkbox icheck">
                <label>
                  <input type="checkbox" name ="remember"> Remember Me
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
        </div> --><!-- /.social-auth-links -->

        <a href="{{ URL::to('/password/remind') }}">I forgot my password</a><br>
        
      </div><!-- /.login-box-body -->
    </div><!-- /.login-box -->

    <!-- jQuery 2.1.3 -->
    <script src="assets/plugins/jQuery/jQuery-2.1.3.min.js"></script>
    <!-- Bootstrap 3.3.2 JS -->
    <script src="assets/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <!-- iCheck -->
    <script src="assets/plugins/iCheck/icheck.min.js" type="text/javascript"></script>
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