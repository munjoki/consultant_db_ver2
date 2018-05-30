@extends('admin.layout')
@section('content')
<!------------------------------------------------------------------------------------------------------------------------------------------
    AKDN MER Consultant Database Version 1.0
    this file handles any un-allowed access to the database
-------------------------------------------------------------------------------------------------------------------------------------------->
<section class="content-header">
	<h1>401 Error Page</h1>
</section>
<section class="content">
    <div class="error-page">
        <h2 class="headline text-red">401</h2>
        <div class="error-content">
            <h3><i class="fa fa-warning text-red"></i> Access Denied.</h3>
            <p>
                Page You are try to access is not accessible for you. 
                Meanwhile, you may <a href="{{route('admin.dashboard')}}">return to dashboard</a> or try to contact super admin to access this page.
            </p>
        </div>
    </div>
    <!-- /.error-page -->
</section>
@stop