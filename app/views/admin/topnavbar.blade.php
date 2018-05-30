<!------------------------------------------------------------------------------------------------------------------------------------------
	AKDN MER Consultant Database Version 1.0
	this file handles top navigation menu of the administrator area
-------------------------------------------------------------------------------------------------------------------------------------------->
<header class="main-header">
	<a href="" class="logo"><b>Consultant</b> Admin</a>
	<!-- Header Navbar: style can be found in header.less -->
	<nav class="navbar navbar-static-top" role="navigation">
	  <!-- Sidebar toggle button-->
	  <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
		<span class="sr-only">Toggle navigation</span>
	  </a>
	  <div class="navbar-custom-menu">
		<ul class="nav navbar-nav">
			<li class="dropdown user">
				<a class="dropdown-toggle" data-toggle="dropdown" href="#">
					<i class="glyphicon glyphicon-user"></i>
					<span>Welcome {{ Auth::admin()->get()->name }} <i class="caret"></i></span>
				</a>
				<ul class="dropdown-menu">
					<li role="presentation"><a role="menuitem" tabindex="-1" href="{{ asset('upload/downloads/admin-manual.pdf') }}" target="_blank"></i>User Manual</a></li>
					<li role="presentation"><a role="menuitem" tabindex="-1" href="{{ URL::route('admin.changepassword.get') }}">Change Password</a></li>
					<li role="presentation" class="divider"></li>
					<li role="presentation"><a role="menuitem" tabindex="-1" href="{{ URL::route('admin.logout') }}"><i class="fa fa-power-off"></i> Logout</a></li>
				</ul>
			</li>
		</ul>
	  </div>
	</nav>
</header>