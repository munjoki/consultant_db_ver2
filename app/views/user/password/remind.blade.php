<!------------------------------------------------------------------------------------------------------------------------------------------
	AKDN MER Consultant Database Version 1.0
	this file allows the users to provide their email address to allow reset their passwords
-------------------------------------------------------------------------------------------------------------------------------------------->
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>Consultant  | AKDN Password Reminder</title>
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
    <div class="modal modal-success" id="consultant_success">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            <h4 class="modal-title">Success</h4>
          </div>
          <div class="modal-body">
            <p>{{ Session::get('message') }}</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Close</button>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div>
  <!-- /.modal -->
  <div class="modal modal-danger" id="consultant_error">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            <h4 class="modal-title">Error</h4>
          </div>
          <div class="modal-body">
            <p>{{ Session::get('message') }}</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Close</button>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div>

    <!-- jQuery 2.1.3 -->
   {{ HTML::script('assets/plugins/jQuery/jQuery-2.1.3.min.js') }}

    {{HTML :: script ('assets/bootstrap/js/bootstrap.min.js')}}
    <!-- Bootstrap 3.3.2 JS -->
   
    <script>
      $(function () {
        
        $('form').submit(function(){
            $('div.overlay').show();
        });

        @if(Session::has('message'))
        
        @if("success" == Session::get('message_type'))
          $('#consultant_success').modal('show');
          
        @endif
        @if( 'danger' == Session::get('message_type') )
          $('#consultant_error').modal('show');
        @endif
        @endif  

        $('#consultant_success').on('hidden.bs.modal', function () {
          //open(location, '_self').close();
            setTimeout(function(){
              window.location = "{{ route('user.login.get') }}";
            },2000
          );
        });
      });
    </script>

    <style type="text/css">
    span.error{ color: red;}
    </style>

  </body>
</html>
