<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Consultant | Lockscreen</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    {{HTML::style('assets/bootstrap/css/bootstrap.min.css')}}

    {{ HTML::style('font-awesome-4.3.0/css/font-awesome.min.css') }}

    {{ HTML::style('ionicons-2.0.1/css/ionicons.min.css') }}    
    <!-- Theme style -->
    {{HTML::style('assets/dist/css/AdminLTE.min.css')}}

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body class="hold-transition lockscreen">
    <!-- Automatic element centering -->
    <div class="lockscreen-wrapper">
      <div class="lockscreen-logo">
        <a href=""><b>Consultant</b></a>
      </div>
      <!-- User name -->
      <div class="lockscreen-name">{{ Session::get('user')['username'] }}</div>

      <!-- START LOCK SCREEN ITEM -->
      <div class="lockscreen-item">
        <!-- lockscreen image -->
        <div class="lockscreen-image">
          <img src="{{ asset('assets/img/nouser.jpg') }}" alt="User Image">
        </div>
        <!-- /.lockscreen-image -->

        <!-- lockscreen credentials (contains the form) -->
        <form class="lockscreen-credentials" method="POST" action="{{ route('login.post') }}">
          {{ Form::token(); }}
          <input type="hidden" name="email" class="form-control" value="{{ Session::get('user')['email'] }}" >
          <div class="input-group">
            <input type="password" class="form-control" name="password" placeholder="password">
            <div class="input-group-btn">
              <button class="btn"><i class="fa fa-arrow-right text-muted"></i></button>
            </div>
          </div>
          <span class="help-inline text-danger">{{ $errors->first('password') }}</span>
        </form><!-- /.lockscreen credentials -->

      </div><!-- /.lockscreen-item -->
      <div class="help-block text-center">
        Enter your password to retrieve your session
      </div>
      <div class="text-center">
        <a href="<?= route('user.login.get') ?>">Or sign in as a different user</a>
      </div>
      <div class="lockscreen-footer text-center">
        Copyright &copy; {{ date('Y'); }} <b><a href="#" class="text-black">Consultant</a></b><br>
        All rights reserved
      </div>
    </div><!-- /.center -->

    <!-- jQuery 2.1.4 -->
    {{ HTML::script('assets/plugins/jQuery/jQuery-2.1.3.min.js') }}
    <!-- Bootstrap 3.3.5 -->
    {{HTML::script('assets/bootstrap/js/bootstrap.min.js')}}
    <script type="text/javascript">
      setTimeout(function(){
        location.reload();
      }, 10 * 60 * 1000);
    </script>
  </body>
</html>
