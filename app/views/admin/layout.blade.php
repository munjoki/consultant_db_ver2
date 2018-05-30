<!------------------------------------------------------------------------------------------------------------------------------------------
	AKDN MER Consultant Database Version 1.0
	this file handles layout of the administration panel
-------------------------------------------------------------------------------------------------------------------------------------------->
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>Consultant | Dashboard</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>

    <!-- Bootstrap 3.3.2 -->
    {{HTML::style('assets/bootstrap/css/bootstrap.min.css')}}

    {{ HTML::style('font-awesome-4.3.0/css/font-awesome.min.css') }}

    {{ HTML::style('ionicons-2.0.1/css/ionicons.min.css') }}    

    <!-- Theme style -->
    {{HTML::style('assets//dist/css/AdminLTE.min.css')}}

    <!-- AdminLTE Skins. Choose a skin from the css/skins 
         folder instead of downloading all of them to reduce the load. -->
    {{HTML::style('assets/dist/css/skins/_all-skins.min.css')}}

    {{ HTML::style('assets/plugins/chosen/chosen.jquery.css') }}    

    {{ HTML::style('assets/plugins/iCheck/square/blue.css') }}
    {{ HTML::style('assets/plugins/datepicker/datepicker3.css') }}
    {{ HTML::style('assets/plugins/datatables/dataTables.bootstrap.css') }}
    {{ HTML::style('assets/plugins/datatables/dataTables.bootstrap.css') }}
    <style type="text/css">
        .help-inline {
            color: #AE4942;
        }
    </style>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        {{ HTML::script('assets/js/html5shiv.js') }}
        {{ HTML::script('assets/js/respond.min.js') }}
    <![endif]-->
    @yield('style')
  </head>
  <body class="skin-blue">
    
    <div class="wrapper">
        <!-- Start: Header -->
        @include('admin.topnavbar')
        <!-- End: Header -->

        <!-- Start: Sidebar -->
        @include('admin.leftnavbar')
        <div class="content-wrapper">

            @yield('content')
        </div>

    </div><!-- ./wrapper -->
    <!-- jQuery 2.1.3 -->
    {{ HTML::script('assets/plugins/jQuery/jQuery-2.1.3.min.js') }}

    {{HTML::script('vendorlib/jquery/jquery_ui/jquery-ui.min.js')}}
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    
    <!-- Bootstrap 3.3.2 JS -->
    {{HTML::script('assets/bootstrap/js/bootstrap.min.js')}}
    <!-- Morris.js charts -->
    
    <!-- FastClick -->
    {{HTML::script('assets/plugins/fastclick/fastclick.min.js')}}
    <!-- AdminLTE App -->
    {{HTML::script('assets/dist/js/app.min.js')}}

    {{ HTML::script('assets/plugins/chosen/chosen.jquery.js') }}    
    {{ HTML::script('vendorlib/plugins/datatables/media/js/jquery.dataTables.js') }}
    <!-- {{ HTML::script('assets/plugins/datatables/jquery.dataTables.js') }} -->
    {{ HTML::script('assets/plugins/datatables/dataTables.bootstrap.js') }}
    {{ HTML::script('assets/plugins/datepicker/bootstrap-datepicker.js') }}
    {{ HTML::script('assets/js/bootbox.min.js') }}
    {{ HTML::script('assets/js/donetyping.js') }}
    {{ HTML::script('assets/js/jquery.populate.pack.js') }}
    {{ HTML::script('assets/js/jquery.form.min.js') }}
    
    <script>
        <?php Session::put('session_admin_timer', time()); ?>
        var timer = <?= Session::get('session_admin_timer'); ?>

        $.widget.bridge('uibutton', $.ui.button);
        $.ajaxSetup({ headers: { 'csrftoken' : '{{ csrf_token() }}' } });
        $.ajaxSetup({
            statusCode: {
                401: function() {
                    bootbox.alert("Session Timeout! Login again to continue Session", function() {
                        location.reload();
                    });
                }
            }
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
            <?php Session::put('session_admin_timer', time()); ?>
            timer = <?= Session::get('session_admin_timer'); ?>;
        });

        setInterval(function(){

            var isBootboxVisible = $('.bootbox').is(':visible');

            if( <?= Session::get('session_admin_timer') + 15 * 60 ?> < timer) {
                window.location.replace("<?= route('admin.locked.get') ?>");
            }

            if ( <?= Session::get('session_admin_timer') + 13 * 60 ?> < timer) {
                if( ! isBootboxVisible){
                    bootbox.alert("Your Session is About to Expire! in Sometime");
                }
            }

            timer++;

        }, 1000);

    </script>
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <!-- {{HTML::script('assets/dist/js/pages/dashboard.js')}} -->

    @yield('script')
  </body>
</html>