<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>Consultant | </title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- Bootstrap 3.3.2 -->
    {{HTML::style('assets/bootstrap/css/bootstrap.min.css')}}
       <!-- FontAwesome 4.3.0 -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <!-- Ionicons 2.0.0 -->
    <link href="http://code.ionicframework.com/ionicons/2.0.0/css/ionicons.min.css" rel="stylesheet" type="text/css" />    
    <!-- Theme style -->
    {{HTML::style('assets/dist/css/AdminLTE.min.css')}}
   
    <!-- AdminLTE Skins. Choose a skin from the css/skins 
         folder instead of downloading all of them to reduce the load. -->
    {{HTML::style('assets/dist/css/skins/_all-skins.min.css')}}
     
    <!-- iCheck -->
    {{HTML::style('assets/plugins/iCheck/flat/blue.css')}}
    
    <!-- Morris chart -->
    <!-- {{HTML::style('assets/plugins/morris/morris.css')}} -->
    
    <!-- jvectormap -->
    {{HTML::style('assets/plugins/jvectormap/jquery-jvectormap-1.2.2.css')}}
    
    <!-- Date Picker -->
    {{HTML::style('assets/plugins/datepicker/datepicker3.css')}}
    
    <!-- Daterange picker -->
    {{HTML::style('assets/plugins/daterangepicker/daterangepicker-bs3.css')}}
    
    <!-- bootstrap wysihtml5 - text editor -->
    {{HTML::style('assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css')}}
  
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
    @yield('style')
  </head>
  <body class="register-page">
              
                @yield('content')
     
    <!-- jQuery 2.1.3 -->
    {{ HTML::script('assets/plugins/jQuery/jQuery-2.1.3.min.js') }}

    {{HTML :: script('http://code.jquery.com/ui/1.11.2/jquery-ui.min.js')}}
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <!-- Bootstrap 3.3.2 JS -->
    {{HTML :: script ('assets/bootstrap/js/bootstrap.min.js')}}
    <!-- Morris.js charts -->
    <script src="http://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    {{HTML :: script ('assets/plugins/morris/morris.min.js')}}
    <!-- Sparkline -->
    {{HTML :: script ('assets/plugins/sparkline/jquery.sparkline.min.js')}}
    {{HTML :: script ('assets/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js')}}
    {{HTML :: script ('assets/plugins/jvectormap/jquery-jvectormap-world-mill-en.js')}}
    <!-- jQuery Knob Chart -->
    {{HTML :: script ('assets/plugins/knob/jquery.knob.js')}}
    <!-- daterangepicker -->
    {{HTML :: script ('assets/plugins/daterangepicker/daterangepicker.js')}}
        <!-- datepicker -->
    {{HTML :: script ('assets/plugins/datepicker/bootstrap-datepicker.js')}}
    <!-- Bootstrap WYSIHTML5 -->
    {{HTML :: script ('assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js')}}
    <!-- iCheck -->
    {{HTML :: script ('assets/plugins/iCheck/icheck.min.js')}}
    <!-- Slimscroll -->
    {{HTML :: script ('assets/plugins/slimScroll/jquery.slimscroll.js')}}
    <!-- FastClick -->
    {{HTML :: script ('assets/plugins/fastclick/fastclick.min.js')}}
    <!-- AdminLTE App -->
    {{HTML :: script ('assets/dist/js/app.min.js')}}

    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <!-- {{HTML :: script ('assets/dist/js/pages/dashboard.js')}} -->
    <script>
      $.widget.bridge('uibutton', $.ui.button);
      $.ajaxSetup({ headers: { 'csrftoken' : '{{ csrf_token() }}' } });
      $.ajaxSetup({
        statusCode: {
            401: function() {
                bootbox.alert("Your session has expired. Please click OK to return to the login page.", function() {
                    location.reload();
                });
            }
        }
        });
    </script>
    @yield('script')
  </body>
</html>