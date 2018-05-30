<!------------------------------------------------------------------------------------------------------------------------------------------
	AKDN MER Consultant Database Version 1.0
	this file handles the left bar menu of the administrator page
-------------------------------------------------------------------------------------------------------------------------------------------->
<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
	<!-- sidebar: style can be found in sidebar.less -->
	<section class="sidebar">
		<!-- sidebar menu: : style can be found in sidebar.less -->
		<ul class="sidebar-menu">
			<li @if( 'dashboard' == Request::segment(2)) class="active" @endif><a href="{{URL::route('admin.dashboard')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
			
			@if(CheckPermission::isPermitted('admin.constantsponsor.index'))
			<li @if( 'constantsponsor' == Request::segment(2)) class="active" @endif><a href="{{URL::route('admin.constantsponsor.index')}}"><i class="fa fa-list"></i> Invited Consultants List</a></li>
			@endif

			@if(CheckPermission::isPermitted('admin.user.index'))
			<li @if( 'users' == Request::segment(2) || 'consultant' == Request::segment(2) || 'user' == Request::segment(2)) class="active" @endif><a href="{{URL::route('admin.user.index')}}"><i class="fa fa-users"></i> Registered Consultants List</a></li>
			@endif
			@if(CheckPermission::isPermitted('admin.akdn.index'))
			<li @if( 'akdn' == Request::segment(2)) class="active" @endif><a href="{{URL::route('admin.akdn.index')}}"><i class="fa fa-user-plus"></i> AKDN Users List</a></li>
			@endif
			@if(CheckPermission::isPermitted('admin.awarded.index'))
			<li @if( 'awarded' == Request::segment(2)) class="active" @endif><a href="{{URL::route('admin.awarded.index')}}"><i class="fa  fa-trophy"></i> Registered Consultancies List</a></li>
			@endif
			@if(CheckPermission::isPermitted('admin.language.index'))
			<li @if( 'language' == Request::segment(2)) class="active" @endif><a href="{{URL::route('admin.language.index')}}"><i class="fa fa-graduation-cap"></i> Languages List</a></li>
			@endif
			@if(CheckPermission::isPermitted('admin.skill.index'))
			<li @if( 'skill' == Request::segment(2)) class="active" @endif><a href="{{URL::route('admin.skill.index')}}"><i class="fa fa-shield"></i> Skills List</a></li>
			@endif
			@if(CheckPermission::isPermitted('admin.specialization.index'))
			<li @if( 'specialization' == Request::segment(2)) class="active" @endif><a href="{{URL::route('admin.specialization.index')}}"><i class="fa fa-table"></i> Thematic Areas List</a></li>
			@endif

			@if(CheckPermission::isPermitted('admin.agencies.index'))
			<li><a href="{{URL::route('admin.agencies.index')}}"><i class="fa fa-bars"></i> Agencies List</a></li>
			@endif

			@if(CheckPermission::isPermitted('admin.adminuser.index'))
			<li @if( 'adminuser' == Request::segment(2)) class="active" @endif><a href="{{URL::route('admin.adminuser.index')}}"><i class="fa fa-user"></i> Administrator User List</a></li>
			@endif

			@if(CheckPermission::isPermitted('admin.firewall.index'))
			<li @if( 'firewall' == Request::segment(2)) class="active" @endif><a href="{{URL::route('admin.firewall.index')}}"><i class="fa fa-th-list"></i> Firewall</a></li>
			@endif

			@if(CheckPermission::isPermitted('admin.loginlogs.user'))
			<li @if( 'loginlogs' == Request::segment(2)) class="active" @endif><a href="{{URL::route('admin.loginlogs.user')}}"><i class="fa fa-bar-chart"></i> Login Logs</a></li>
			@endif

			@if(CheckPermission::isPermitted('admin.role.index'))
			<li @if( 'role' == Request::segment(2)) class="active" @endif><a href="{{URL::route('admin.role.index')}}"><i class="fa fa-user-secret"></i> Roles List</a></li>
			@endif

			@if(CheckPermission::isPermitted('admin.mailsend.create'))
			<li><a href="{{URL::route('admin.mailsend.create')}}"><i class="fa fa-envelope"></i> Send Email</a></li>
			@endif
			
		</ul>
	</section>
	<!-- /.sidebar -->
</aside>