<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>Consultant  | IpBlocked</title>
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
        <a href=""><b>Consultant</b></a>
      </div><!-- /.login-logo -->
      <div>
        <div class="alert alert-danger">Your Ip has Been Blocked Because Multiple failed attempts found.</div>
      </div><!-- /.login-box-body -->
    </div><!-- /.login-box -->

    <!-- jQuery 2.1.3 -->
    {{ HTML::script('assets/plugins/jQuery/jQuery-2.1.3.min.js') }}

    {{HTML :: script ('assets/bootstrap/js/bootstrap.min.js')}}

  </body>
</html>