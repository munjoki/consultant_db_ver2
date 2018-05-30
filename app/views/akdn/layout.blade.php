<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Consultant | Search</title>
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
        <style type="text/css">
            span.error{ color: red; font-size: 12px; }
            .font-18{ font-size: 18px; }
        </style>
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
                            <a href="{{ URL::route('akdn.home') }}" class="navbar-brand"><b>AKDN MER Consultant Database</b></a>
                            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
                            <i class="fa fa-bars"></i>
                            </button>
                        </div>
                        <!-- Collect the nav links, forms, and other content for toggling -->
                        <div class="collapse navbar-collapse" id="navbar-collapse">
                            <ul class="nav navbar-nav">
                                <li><a href="javascript:void(0)" data-toggle="modal" data-target="#div-sponser-consultant"><b class="font-18">Invite a Consultant</b></a></li>
                                <!-- <li><a href="javascript:void(0)" data-toggle="modal" data-target="#div-password">change Password</a></li> -->
                                <li @if( Request::segment(2) == "consultantassignment" ) class="active" @endif><a href="{{ URL::route('akdn.consultantassignment.index') }}"><b class="font-18">Registered Consultancies</b></a></li>
                            </ul>
                            <div class="navbar-custom-menu">
                                <ul class="nav navbar-nav">
                                    <li class="dropdown user">
                                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                        <i class="glyphicon glyphicon-user"></i>
                                        <b class="font-18">Welcome {{ Auth::akdn()->get()->getWelcomeName() }} <i class="caret"></i></b>
                                        </a>
                                        <ul class="dropdown-menu">
                                            <li role="presentation"><a role="menuitem" tabindex="-1" href="{{ asset('upload/downloads/akdn-manual.pdf') }}" target="_blank"></i>User Manual</a></li>
                                            <li role="presentation"><a role="menuitem" tabindex="-1" href="javascript:void(0)" data-toggle="modal" data-target="#div-password"></i>Change Password</a></li>
                                            <li role="presentation" class="divider"></li>
                                            <li role="presentation"><a role="menuitem" tabindex="-1" href="{{ URL::route('akdn.logout') }}"><i class="fa fa-power-off"></i> Logout</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                            <!--   <ul class="nav navbar-nav navbar-right">
                                <li class="dropdown">
                                  <a href="javascript:void(0)">
                                    <i class="fa fa-user"></i> Welcome {{ Auth::akdn()->get()->getWelcomeName() }}
                                  </a>
                                </li>
                                <li><a href="{{ URL::route('akdn.logout') }}"><i class="fa fa-power-off"></i> Logout</a></li>
                                </ul> -->
                        </div>
                    </div>
                </nav>
            </header>
            <!-- Full Width Column -->
            <div class="content-wrapper">
                <div class="container-fluid">
                    @yield('content')
                </div>
                <!-- /.container -->
            </div>
            <!-- /.content-wrapper -->
        </div>
        <!-- ./wrapper -->
        <div class="modal" id="div-sponser-consultant">
            <div class="modal-dialog modal-lg">
                <div class="modal-content box">
                    <form class="form-horizontal" id="frm-sponser-consultant" method="post" action="{{ URL::route('akdn.consultantsponsor.store') }}">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Invite a Consultant</h4>
                        </div>
                        <div class="modal-body">
                            <div class="callout callout-success" id="sponser-consultant-success" style="display:none">
                                <p>Invitation has been sent successfully.</p>
                            </div>
                            <div class="callout callout-danger" id="sponser-consultant-danger" style="display:none">
                                <p>There were some errors.</p>
                            </div>
                            <div class="form-group">
                                <label for="name" class="control-label col-xs-4">Consultant's Name <span class="text-danger">* </span></label>
                                <div class="col-xs-8">
                                    <input type="text" name="name" class="form-control" id="sp-con-name" placeholder="Provide consultant's name(s)"/>
                                    <span class="help-inline error"></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputEmail" class="control-label col-xs-4">Consultant's Email <span class="text-danger">* </span></label>
                                <div class="col-xs-8">
                                    <input type="email" name="email" class="form-control" id="sp-con-email" placeholder="Provide consultant's email address">
                                    <span class="help-inline error"></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputPassword" class="control-label col-md-4">Thematic Area(s)</label>
                                <div class="col-md-8">
                                    {{ Form::select('specialization[]',array(""=>"")+$specialization,0,array('id' => 'sp-con-specialization', 'class'=>'form-control', 'multiple' => true, 'data-placeholder'=>'Field(s) of expertise'))}}
                                    <span class="help-inline error"></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputPassword" class="control-label col-md-4">Invited on behalf of</label>
                                <div class="col-md-8">
                                    <input type="text" name="invited_on_behalf_of" class="form-control" id="sp-con-invited_behalf" placeholder="Invited on behalf of"/>
                                    <span class="help-inline error"></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="messageText" class="control-label col-md-4">Personalised Message</label>
                                <div class="col-md-8">
                                    <textarea name="message_by" id = "message_by" class="form-control" rows="10" placeholder="Add an optional personalised message to the consultant in addition to the system-generated invitation"></textarea>
                                    <span class="help-inline error"></span>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default pull-left btn-flat" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary btn-flat">Invite</button>
                            <button type="button" class="btn btn-primary btn-flat" id="preview">Preview</button> 
                        </div>
                    </form>
                    <div class="overlay">
                        <i class="fa fa-spin fa-spinner"></i>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
        <div class="modal" id="div-password">
            <div class="modal-dialog">
                <div class="modal-content box">
                    <form class="form-horizontal" name="" id="frm-password" method="post" action="{{ URL::route('akdn.changepassword.post') }}">
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
                            <button type="submit" class="btn btn-primary btn-flat">Update</button>
                        </div>
                    </form>
                    <div class="overlay" id="password_spin" style="display:none;">
                        <i class="fa fa-spin fa-spinner"></i>
                    </div>
                </div>
            </div>
        </div>
        <div id="modal-awarded-consultant" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Registered Consultancies</h4>
                    </div>
                    <div class="modal-body box no-border">
                        <div id="modal-awarded-consultant-info"></div>
                        <div class="overlay" id="modal-awarded-consultant-loader">
                            <i class="fa fa-spin fa-spinner"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @yield('modal')
        <!-- jQuery 2.1.3 -->
        {{ HTML::script ('assets/plugins/jQuery/jQuery-2.1.3.min.js') }}
        <!-- Bootstrap 3.3.2 JS -->
        {{ HTML::script ('assets/bootstrap/js/bootstrap.min.js') }}
        <!-- SlimScroll -->
        {{ HTML::script ('assets/plugins/slimScroll/jquery.slimscroll.min.js') }}
        <!-- FastClick -->
        {{ HTML::script ('assets/plugins/fastclick/fastclick.min.js') }}
        <!-- AdminLTE App -->
        {{ HTML::script ('assets/dist/js/app.min.js') }}
        {{ HTML::script('assets/plugins/chosen/chosen.jquery.js') }}
        {{ HTML::script('assets/js/jquery.form.min.js') }}
        {{ HTML::script('assets/js/bootbox.min.js') }}
        {{ Html::style('assets/plugins/colorbox/colorbox.min.css' )}}
        {{ Html::script('assets/plugins/colorbox/colorbox.min.js')}}
        @yield('scriptfiles')
        @yield('script')
        <script type="text/javascript">

            $('#preview').colorbox(colorbox_params);
            var height = 10;
            var colorbox_params = {
                iframe: true,
                reposition:true,
                scalePhotos:true,
                scrolling:true,
                close:'&times;',
                width:"450",
                height:height,
                title:"To close this dialog click X icon at the right", 
                onOpen:function(){
                    document.body.style.overflow = 'hidden';
                },
                onClosed:function(){
                    document.body.style.overflow = 'auto';
                }
            };

            $( "#preview" ).click(function() {
                var consultant_name = $('#sp-con-name').val();
                var consultant_email = $('#sp-con-email').val();
                var invited_behalf = $('#sp-con-invited_behalf').val();
                var message_by = $('#message_by').val();

                    $.ajax({
                    url        : "{{URL::route('akdn.constant.preview')}}",
                    type       : 'post',
                    data       : {
                                    'consultant_name': consultant_name,
                                    'consultant_email':consultant_email,
                                    'invited_behalf':invited_behalf,
                                    'message_by':message_by
                                 },
                    dataType   : 'json',
                    success: function(resp){
                        $.colorbox({html:resp});
                        $.colorbox.resize({width:'750px',height:'450px'});
                    }
                });
            }); 

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
            
            
            <?php Session::put('session_akdn_timer', time()); ?>
            var timer = <?= Session::get('session_akdn_timer'); ?>
            
            $.ajaxSetup({ headers: { 'csrftoken' : '{{ csrf_token() }}' } });
            $.ajaxSetup({
               statusCode: {
                   401: function() {
                       bootbox.alert("Your current session has expired. Login again to continue to access the database.", function() {
                           location.reload();
                       });
                   }
               }
            });
            $(function(){
            
             $('#sp-con-specialization').chosen({ width:"100%", placeholder: "Specialization" });
             $('#sp_con_specialization_chosen').find('ul.chosen-choices input').width('auto');
            
             $('#div-sponser-consultant').on('hidden.bs.modal', function () {
                 $('#frm-sponser-consultant')[0].reset();
                 $('#sp-con-specialization').val('').trigger('chosen:updated');
                 $('.callout').hide();
                 $('#frm-sponser-consultant').find('span.error').text('');
             });
            
             $('#frm-sponser-consultant').submit(function(){
               form = $(this);
               form.ajaxSubmit({
                 beforeSend: function(){
                   form.next('div.overlay').show();
                 },
                 complete: function(){
                   form.next('div.overlay').hide();
                 },
                 success: function(resp){
            
                   form.find('.callout').hide();
                   form.find('span.error').text('');
            
                   if(resp.success){
                     $('#sponser-consultant-success').show().delay(3000).fadeOut();
                     $('#frm-sponser-consultant')[0].reset();
                     $('#sp-con-specialization').val('').trigger('chosen:updated');
                   }else{
                     $('#sponser-consultant-danger').show().delay(3000).fadeOut();
                     $.each(resp.errors, function(key, val){
                       form.find('#sp-con-'+key).closest('div').find('span.error').text(val[0]);
                     });
                   }
                 }
               });
            
               return false;
             });
            
             var mousePosition = [-1, -1];
             $(document).on('keyup mouseup mousemove touchend touchmove', function(e) {
                   if (e.type === 'mousemove') {
            
                       if (e.clientX === mousePosition[0] && e.clientY === mousePosition[1]) {
                           return;
                       }
                       mousePosition[0] = e.clientX;
                       mousePosition[1] = e.clientY;
                   }
            
                   <?php Session::put('session_akdn_timer', time()); ?>
                   timer = <?= Session::get('session_akdn_timer'); ?>;
               });
            
               setInterval(function(){
            
                   var isBootboxVisible = $('.bootbox').is(':visible');
            
                   if( <?= Session::get('session_akdn_timer') + 15 * 60 ?> < timer) {
                       window.location.replace("<?= route('akdn.locked.get') ?>");
                   }
            
                   if ( <?= Session::get('session_akdn_timer') + 13 * 60 ?> < timer) {
                       if( ! isBootboxVisible){
                           bootbox.alert("Your current session is about to expire. Click OK to continue using the database");
                       }
                   }
            
                   timer++;
               }, 1000);
            });
        </script>
    </body>
</html>