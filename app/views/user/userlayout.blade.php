<!------------------------------------------------------------------------------------------------------------------------------------------
	AKDN MER Consultant Database Version 1.0
	this file allow the consultant to change/update their password from their user profile
-------------------------------------------------------------------------------------------------------------------------------------------->
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>Consultant</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    
    <!-- Bootstrap 3.3.2 -->
    {{HTML::style('assets/bootstrap/css/bootstrap.min.css')}}

    {{ HTML::style('assets/plugins/chosen/chosen.jquery.css') }}    

    {{ HTML::style('font-awesome-4.3.0/css/font-awesome.min.css') }}
    
    <!-- Theme style -->
    {{HTML::style('assets/dist/css/AdminLTE.min.css')}}
    {{HTML::style('assets/dist/css/skins/_all-skins.min.css')}}

    @yield('stylefiles')
    @yield('style')

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        {{ HTML::script('assets/js/html5shiv.js') }}
        {{ HTML::script('assets/js/respond.min.js') }}
    <![endif]-->
  </head>
  <!-- ADD THE CLASS layout-top-nav TO REMOVE THE SIDEBAR. -->
  <body class="skin-blue layout-top-nav">
    <div class="wrapper">
      
      <header class="main-header">               
        <nav class="navbar navbar-static-top">
          <div class="container-fluid">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
              <i class="fa fa-bars"></i>
            </button>
          </div>

          <!-- Collect the nav links, forms, and other content for toggling -->
          <div class="collapse navbar-collapse" id="navbar-collapse">
            
            <ul class="nav navbar-nav navbar-right">
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <i class="fa fa-user"></i> Welcome {{ Auth::user()->get()->other_names }}
                  <span class="caret"></span>
                </a>
                <ul class="dropdown-menu" role="menu">
                    <li role="presentation"><a role="menuitem" tabindex="-1" href="{{ asset('upload/downloads/consultant-manual.pdf') }}" target="_blank"></i>User Manual</a></li>
                  <li><a href="{{ URL::route('user.profile.edit') }}">Edit Profile</a></li>
                  <li><a href="javascript:void(0)" id="model_password">Change Password</a></li>
                  <li><a href="javascript:void(0)" id="delete_account">Delete Profile</a></li>
                  <li class="divider"></li>
                  <li><a href="{{ URL::route('logout') }}"><i class="fa fa-power-off"></i> Logout</a></li>
                </ul>
              </li>
            </ul>
          </div><!-- /.navbar-collapse -->
          </div><!-- /.container-fluid -->
        </nav>
      </header>
      <!-- Full Width Column -->
      <div class="content-wrapper">
        <div class="container-fluid">
          @yield('content')
            <div class="modal" id="div-password">
                <div class="modal-dialog">
                    <div class="modal-content box">
                        <form class="form-horizontal" name="" id="frm-password" method="post" action="{{ URL::route('user.changepassword.post') }}">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title">Change Password</h4>
                            </div>
                            <div class="modal-body">
                                <div class="callout callout-success" id="password-success" style="display:none">
                                    <p>Success! Your password has been changed successfully.</p>
                                </div>
                                <div class="callout callout-danger" id="password-danger" style="display:none">
                                    <p>There were some errors.</p>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="">Current Password <span class="text-danger">*</span></label>
                                    <div class="col-md-8">
                                        {{ Form::password('old_password', array('id' => 'aw-con-old_password', 'class' => 'form-control', 'placeholder' => 'Enter the current password')) }}
                                        <span class="help-inline text-danger error"></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="">New password <span class="text-danger">*</span></label>
                                    <div class="col-md-8">
                                        {{ Form::password('password', array('id' => 'aw-con-password', 'class' => 'form-control', 'placeholder' => 'Enter the new password')) }}
                                        <span class="help-inline text-danger error"></span>
                                    </div>
                                </div>
                            
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="">Confirm Password <span class="text-danger">*</span></label>
                                    <div class="col-md-8">
                                        {{ Form::password('password_confirmation', array('id' => 'aw-con-password_confirmation', 'class' => 'form-control', 'placeholder' => 'Confirm the new password')) }}
                                        <span class="help-inline text-danger error"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default pull-left btn-flat" data-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary btn-flat">Edit</button>
                            </div>
                        </form>
                        <div class="overlay" id="password_spin" style="display:none;">
                            <i class="fa fa-spin fa-spinner"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- /.container -->
      </div><!-- /.content-wrapper -->
    </div><!-- ./wrapper -->
    <!-- jQuery 2.1.3 -->
    {{ HTML :: script ('assets/plugins/jQuery/jQuery-2.1.3.min.js') }}

    <!-- Bootstrap 3.3.2 JS -->
    {{ HTML :: script ('assets/bootstrap/js/bootstrap.min.js') }}
    {{ HTML::script('assets/js/bootbox.min.js') }}
    {{ HTML::script('assets/js/jquery.form.min.js') }}
    
    <!-- SlimScroll -->
    {{ HTML :: script ('assets/plugins/slimScroll/jquery.slimscroll.min.js') }}
    
    <!-- FastClick -->
    {{ HTML :: script ('assets/plugins/fastclick/fastclick.min.js') }}

    <!-- AdminLTE App -->
    {{ HTML :: script ('assets/dist/js/app.min.js') }}
    
    @yield('scriptfiles')
    <script type="text/javascript">
    $.ajaxSetup({ headers: { 'csrftoken' : '{{ csrf_token() }}' } });
    $('#model_password').click(function(){
        $('#div-password').modal('show');
    });
    <?php Session::put('session_user_timer', time()); ?>
    var timer = <?= Session::get('session_user_timer'); ?>

    var mousePosition = [-1, -1];
    $(document).on('keyup mouseup mousemove touchend touchmove', function(e) {
        if (e.type === 'mousemove') {

            if (e.clientX === mousePosition[0] && e.clientY === mousePosition[1]) {
                return;
            }
            mousePosition[0] = e.clientX;
            mousePosition[1] = e.clientY;
        }

        <?php Session::put('session_user_timer', time()); ?>
        timer = <?= Session::get('session_user_timer'); ?>;
    });
    setInterval(function(){

        var isBootboxVisible = $('.bootbox').is(':visible');

        if( <?= Session::get('session_user_timer') + 15 * 60 ?> < timer) {
            window.location.replace("<?= route('locked.get') ?>");
        }

        if ( <?= Session::get('session_user_timer') + 13 * 60 ?> < timer) {
            if( ! isBootboxVisible){
                bootbox.alert("Your current session is about to expire. Please click “OK” to continue.");
            }
        }

        timer++;
    }, 1000);

    $(document).ready(function(){

        $('#frm-password').submit(function(){
            form = $(this);
            form.ajaxSubmit({
                beforeSend: function(){
                    $('#password_spin').show();
                },
                complete: function(){
                    $('#password_spin').hide();
                },
                success: function(resp){
                    form.find('.callout').hide();
                    form.find('span.error').text('');
                    if(resp.success){
                        $('#frm-password')[0].reset();
                        $('#password-success').show().delay(3000).fadeOut('slow', function(){
                            $('#div-password').modal('hide');
    
                        });
                    }else{
                        $('#passowrd-danger').show().delay(3000).fadeOut();
                        $.each(resp.errors, function(key, val){
                            form.find('#aw-con-'+key).closest('div').find('span.error').text(val[0]);
                        });
                    }
                }
            });
            
            return false;
        });
    });

    $(document).on('click', '#delete_account', function() {
    
        bootbox.confirm("Are you sure you want to delete your consultant profile from our database?", function(result) {

            if( true === result ){
                var ele = $('#delete_account');
                
                $.ajax({
                    url: "{{ route('user.deleteaccount.post') }}",
                    type: "post",
                    dataType: 'json',
                    beforeSend: function() {
                        bootbox.dialog({message: '<i class="fa fa-spin fa-spinner"></i> Please wait, we are processing your request.'});
                        ele.prop('disabled', true).append('<i class="fa fa-spin fa-spinner"></i>');
                    },
                    success: function(resp){
                        bootbox.hideAll();
                        bootbox.alert('Your request to delete your profile from our consultant database has been sent to the database administrator. Thank you.', function(){

                            window.location = "{{ route('logout') }}";
                        });
                    }
                });
            }
        });
    });
    </script>
    @yield('script')
  </body>
</html>