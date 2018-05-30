<?php

/*
|--------------------------------------------------------------------------
| Application & Route Filters
|--------------------------------------------------------------------------
|
| Below you will find the "before" and "after" events for the application
| which may be used to do any work before or after a request into your
| application. Here you may also register your custom route filters.
|
*/

App::before(function($request)
{
	//
});


App::after(function($request, $response)
{
	//
});

/*
|--------------------------------------------------------------------------
| Authentication Filters
|--------------------------------------------------------------------------
|
| The following filters are used to verify that the user of the current
| session is logged into this application. The "basic" filter easily
| integrates HTTP Basic authentication for quick, simple checking.
|
*/

Route::filter('auth.admin', function()
{

	if ( !Auth::admin()->check() ){

		if (Request::ajax())
		{
			return Response::make('Unauthorized', 401);
		}
		else
		{
			return Redirect::route('admin.login');
		}
	}
});

Route::filter('auth.user', function()
{

	if ( !Auth::user()->check() ){

		if (Request::ajax())
		{
			return Response::make('Unauthorized', 401);
		}
		else
		{
			return Redirect::route('user.login.get');
		}
	}
});


Route::filter('auth.akdn', function()
{

	if ( !Auth::akdn()->check() ){

		if (Request::ajax())
		{
			return Response::make('Unauthorized', 401);
		}
		else
		{
			return Redirect::route('akdn.login');
		}
	}
});

Route::filter('akdn_last_activity', function()
{

	if( Auth::akdn()->check() ){
		
        $akdn = Auth::akdn()->get();
		$akdn->last_activity = date('Y-m-d H:i:s');
		$akdn->save();
    }
});



Route::filter('auth.basic', function()
{
	return Auth::basic();
});

/*
|--------------------------------------------------------------------------
| Guest Filter
|--------------------------------------------------------------------------
|
| The "guest" filter is the counterpart of the authentication filters as
| it simply checks that the current user is not logged in. A redirect
| response will be issued if they are, which you may freely change.
|
*/

Route::filter('guest', function()
{
	if (Auth::check()) return Redirect::to('/');
});

/*
|--------------------------------------------------------------------------
| CSRF Protection Filter
|--------------------------------------------------------------------------
|
| The CSRF filter is responsible for protecting your application against
| cross-site request forgery attacks. If this special token in a user
| session does not match the one given in this request, we'll bail.
|
*/

Route::filter('csrf', function()
{
	if ($_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'PUT') {
		if (Request::ajax())
	    {
	        if (Session::token() !== Request::header('csrftoken')) 
	        {
	            throw new Illuminate\Session\TokenMismatchException;
	        }
	    } 
		elseif (Session::token() != Input::get('_token'))
		{
			throw new Illuminate\Session\TokenMismatchException;
		}
	}
});

Route::filter('acl.permited','AclPermittedFilter');

Route::filter('ip_blacklisted', function(){

	$ip = Request::getClientIp(true);

	if(Firewall::isBlacklisted($ip)){

		return Redirect::route('ipblocked.get');
	}
});
