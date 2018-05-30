<html>
    <head>
        <meta charset="UTF-8">
        <title>404 Page</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        {{HTML::style('assets/bootstrap/css/bootstrap.min.css')}}
        {{ HTML::style('assets/plugins/chosen/chosen.jquery.css') }}    
        {{ HTML::style('font-awesome-4.3.0/css/font-awesome.min.css') }}
        <!-- Theme style -->
        {{HTML::style('assets/dist/css/AdminLTE.min.css')}}
        {{HTML::style('assets/dist/css/skins/_all-skins.min.css')}}
        <style type="text/css">
            span.error{ color: red; font-size: 12px; }
            .font-18{ font-size: 18px; }
        </style>
    </head>
    
    <body>
    @if( 'akdn' == Request::segment(1))
        <div class="skin-blue layout-top-nav">
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
                        </div>
                    </nav>
                </header>
                <div class="content-wrapper">
                    <div class="container-fluid">
                       
                        <section class="content">
                            <div class="error-page">
                                <h2 class="headline text-red">401</h2>
                                <div class="error-content">
                                    <h3><i class="fa fa-warning text-yellow"></i> Oops! Page not found.</h3>
                                    <p>
                                        We could not find the page you were looking for.
                                        Meanwhile, you may <a href="{{route('akdn.home')}}">return to Dashboard</a> or try using the search form.
                                    </p>
                                </div>
                            </div>

                        </section>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if('admin'==Request::segment(1))
        <div class="skin-blue">
            <header class="main-header">
                <a href="{{ URL::route('user.profile') }}" class="logo"><b>Consultant</b> Admin</a>
                <nav class="navbar navbar-static-top" role="navigation">
                </nav>
            </header>

            <div style="min-height: 693px; background-color:#ecf0f5;">
                <div class="container-fluid">
                    <section class="content">
                        <div class="error-page">
                            <h2 class="headline text-red">401</h2>
                            <div class="error-content">
                                <h3><i class="fa fa-warning text-yellow"></i> Oops! Page not found.</h3>
                                <p>
                                    We could not find the page you were looking for.
                                    Meanwhile, you may <a href="{{route('admin.dashboard')}}">return to Dashboard</a> or try using the search form.
                                </p>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    @endif

    @if('user'==Request::segment(1))
        <div class="skin-blue">
            <header class="main-header" style="background-color:#3c8dbc;">
                
                <nav class="navbar navbar-static-top" role="navigation">
                </nav>
            </header>

            <div style="min-height: 693px; background-color:#ecf0f5;">
                <div class="container-fluid">
                    <section class="content">
                        <div class="error-page">
                            <h2 class="headline text-red">401</h2>
                            <div class="error-content">
                                <h3><i class="fa fa-warning text-yellow"></i> Oops! Page not found.</h3>
                                <p>
                                    We could not find the page you were looking for.
                                    Meanwhile, you may <a href="{{route('user.profile')}}">return to Dashboard</a> or try using the search form.
                                </p>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    @endif

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
    </body>


</html>
