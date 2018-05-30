<!------------------------------------------------------------------------------------------------------------------------------------------
	AKDN MER Consultant Database Version 1.0
	this file handles the message and screen shown once the consultant review has been provided
-------------------------------------------------------------------------------------------------------------------------------------------->
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>Consultant | Review</title>
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
    <style type="text/css">
	   
	    .help-inline { color: red; }
	    .box .overlay>.fa { font-size: 50px; }
	    body #canvas-wrapper {
			position: fixed;
			top: 0;
			left: 0;
			right: 0;
			bottom: 0;
			width: 100%;
			height: 100%;
		}
		.login-page, .register-page { background: #7E94A2; }
		.form-horizontal .control-label { text-align: left !important; }
	</style>
  </head>
<body class="register-page">
    
	<div id="canvas-wrapper">
		<canvas id="demo-canvas"></canvas>
	</div>
	<div class="row">
		<div class="col-md-2"></div>
		<div class="col-md-8">
			
			<div class="register-logo">
				<a href=""><b>Consultant </b> Review</a>
			</div>
			@include('partials.alert')

			<div class="box no-border">
				<div class="register-box-body">
					<div class="row">
						<div class="col-md-12 text-center">
							<strong>Thank you for taking the time to review this consultant.</strong>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-2"></div>
	</div>
    
    <!-- jQuery 2.1.3 -->
    {{ HTML :: script ('assets/plugins/jQuery/jQuery-2.1.3.min.js') }}

    <!-- Bootstrap 3.3.2 JS -->
    {{ HTML :: script ('assets/bootstrap/js/bootstrap.min.js') }}
    
    {{ HTML::script('assets/js/canvasbg/EasePack.min.js' ) }}
    {{ HTML::script('assets/js/canvasbg/rAF.js' ) }}
    {{ HTML::script('assets/js/canvasbg/TweenLite.min.js' ) }}
    {{ HTML::script('assets/js/canvasbg/login.js' ) }}
    {{ HTML::script('assets/js/canvasbg/canvasbg.js') }}
   
    <script type="text/javascript">

	jQuery(document).ready(function() {

		// Init CanvasBG and pass target starting location
        CanvasBG.init({
            Loc: {
                x: window.innerWidth / 2,
                y: window.innerHeight / 6
            },
        });
	});	
	</script>
</body>
</html>