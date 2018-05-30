<?php

use Carbon\Carbon;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('mail',function(){
	/*Mail::send('admin.mails.message',['msg'=>"test"],function ($message)  {
      $message->subject('d')
      		  ->from('from@example.com')
              ->to('niraj@thinktanker.in');
    });*/
});

Route::get('ipblocked', [
	'as' => 'ipblocked.get',
	'uses' => function(){
		return View::make('ipblocked');
	}
]);

Route::get('remote-login', [
	'as' => 'remotelogin.get',
	'uses' => function(){
		return View::make('remotelogin');
	}
]);



Route::get('akdn-cron-job', function(){

	$akdns = Akdn::whereBetween('last_activity', array(Carbon::now(), Carbon::now()->addWeek()))->get()->toArray();

	echo "<pre>";
	print_r($akdns);
	exit();

	foreach ($akdns as $key => $akdn) {
		$last_activity = $akdn['last_activity'];
	}
});

Route::get('testmail',function(){
	Mail::send('emails.sample',['hello'],function($message){
		$message->to('utsav@yopmail.com')->subject('nothing');
	});
});

Route::get('/hash', function(){

	$form = Form::open() . Form::text('hash', null, array('placeholder' => 'Enter password to get hashed', 'style' => "padding:5px; width:250px;")) . "  " .Form::submit('Submit',array( 'style' => "padding:5px;")) .Form::close();
	return $form;
});

Route::post('/hash', function(){

	return "Hash Password For \"" . Input::get('hash') . "\": " . Hash::make(Input::get('hash')) . '<br/><br/>' . "<a href=" . url(URL::to('/hash')) . ">Go Back</a>";
});

Route::group(array('before' => ['csrf','ip_blacklisted'] ), function() {

	Route::get('/', function(){
		return Redirect::route('akdn.login');
	});

	Route::get('/register', array(
			'as'	=> 'user.register',
			'uses'  => 'ConsultantController@register'
		));
	Route::post('/register', array(
			'as'	=> 'register.post',
			'uses'  => 'ConsultantController@store'
		));
	Route::get('/login', array(
			'as'	=> 'user.login.get',
			'uses'  => 'UserLoginController@login'
		));
	Route::get('/login/{token}', array(
			'as'	=> 'user.token',
			'uses'  => 'UserLoginController@loginToken'
		));

	Route::post('/login', array(
			'as'	=> 'login.post',
			'uses'  => 'UserLoginController@postlogin'
		));

	Route::get('/logout', array(
		'as'	=> 'logout',
		'uses'  => 'UserLoginController@logout'
	));

	Route::get('/locked', array(
		'as' => 'locked.get',
		'uses' => 'UserLoginController@getLocked'
	));

	Route::get('session',[
		'as' => 'user.session',
		'uses' => 'UserLoginController@loginSession'
	]);

	Route::get('/consultant/review/complete', array(
		'as' => 'consultant.review.complete',
		'uses' => 'ConsultantController@reviewComplete'
	));

	Route::get('/consultant/extenddate/complete', array(
		'as' => 'consultant.enddate.complete',
		'uses' => 'ConsultantController@extendComplete'
	));

	Route::get('/consultant/review/{token}', array(
		'as' => 'consultant.review.get',
		'uses' => 'ConsultantController@getReview'
	));
	Route::get('/consultant/extenddate/{token}',array(
		'as'=> 'consultant.extenddate.get',
		'uses'=> 'ConsultantController@extendDateGet'
	));

	Route::post('/consultant/extenddate/{token}',array(
		'before'=> 'csrf',
		'as'=> 'consultant.extenddate.post',
		'uses'=> 'ConsultantController@extendDatePost'
	));

	Route::post('/consultant/review/{token}', array(
		'before' => 'csrf',
		'as' => 'consultant.review.post',
		'uses' => 'ConsultantController@postReview'
	));

	Route::get('/terms', array(
		'as' => 'consultant.terms',
		'uses' => 'ConsultantController@terms'
	));

	// Password reset
	Route::controller('password', 'PasswordRemindersController');

	Route::group(array('before' => 'auth.user'), function(){

		Route::get('user/profile', array(
			'as'	=> 'user.profile',
			'uses'  => 'UserLoginController@showProfile'
		));

		Route::get('user/profile/edit', array(
			'as'	=> 'user.profile.edit',
			'uses'  => 'UserLoginController@editProfile'
		));

		Route::post('user/profile/update', array(
			'as'	=> 'user.profile.update',
			'uses'  => 'UserLoginController@updateProfile'
		));

		Route::post('user/resume/delete', array(
			'as'	=> 'user.resume.delete',
			'uses'  => 'UserLoginController@deleteResume'
		));

		Route::get('user/resume', array(
			'as'	=> 'user.resume.download',
			'uses'  => 'ConsultantController@downloadResume'
		));

		Route::post('user/changepassword', array(
			'as'	=> 'user.changepassword.post',
			'uses'  => 'UserLoginController@postChangePassword'
		));

		Route::post('/account/delete', array(
			'as'	=> 'user.deleteaccount.post',
			'uses'  => 'UserLoginController@postDeleteAccount'
		));
	});

	Route::group(array('prefix' => 'akdn'), function(){

		/*
			Route::get('/hash', function(){
				return Hash::make("Akdn#123");
			});
		*/
		# Admin Login

		Route::get('/login', array('as' => 'akdn.login' ,'uses' => 'AkdnLoginController@login'));
		Route::post('/login', array('as' => 'akdn.login.post', 'uses' => 'AkdnLoginController@doLogin'));
		Route::get('logout', array('as' => 'akdn.logout' ,'uses' => 'AkdnLoginController@logout'));

		Route::get('session',[
			'as' => 'akdn.session',
			'uses' => 'AkdnLoginController@loginSession'
		]);

		Route::get('/locked', array('as' => 'akdn.locked.get', 'uses' => 'AkdnLoginController@getLocked'));

		// Password reset
		Route::controller('password', 'AkdnPasswordRemindersController');
		
		Route::get('/register',array(
			'as'=>'akdn.register',
			'uses'=>'AkdnController@register'

			));
		Route::post('/register',array(
			'as'=>'akdn.register.post',
			'uses'=>'AkdnController@store'

			));
		Route::get('/login/{token}', array(
			'as'	=> 'user.token',
			'uses'  => 'AkdnLoginController@loginToken'
		));

		Route::get('/newpassword', array(
			'as'	=> 'akdn.newpassword.get',
			'uses'	=> 'AkdnLoginController@getNewPassword'
		));

		Route::post('/newpassword', array(
			'as'	=> 'akdn.newpassword.post',
			'uses'	=> 'AkdnLoginController@postNewPassword'
		));

		Route::get('/changepassword', array(
			'as'	=> 'akdn.changepassword.get',
			'uses'	=> 'AkdnLoginController@getChangePassword'
		));

		Route::post('/changepassword', array(
			'as'	=> 'akdn.changepassword.post',
			'uses'	=> 'AkdnLoginController@postChangePassword'
		));


		Route::group(array('before' => 'auth.akdn', 'after' => 'akdn_last_activity'), function(){
			
			Route::get('/', array(
				'as' => 'akdn.home',
				'uses' => 'AkdnConsultantController@home'
			));

			Route::get('/consultant/search', array(
				'as' => 'akdn.consultant.search',
				'uses' => 'AkdnConsultantController@consultantSearch'
			));

			Route::get('/consultant/{id}/profile', array(
				'as'=> 'akdn.constant.show',
				'uses'=>'AkdnConsultantController@show'
			));

			Route::get('/consultant/excelexport', array(
				'as'=> 'akdn.constant.excelexport',
				'uses'=>'AkdnConsultantController@excelExport'
			));

			Route::post('/consultant/profile/quickview', array(
				'as'=> 'akdn.constant.quickview',
				'uses'=>'AkdnConsultantController@quickview'
			));

			Route::post('/consultantsponsor/store', array(
				'as'=> 'akdn.consultantsponsor.store',
				'uses'=>'ConsultantSponsorController@store'
			));


			Route::resource('consultantassignment', 'ConsultantAssignmentController');

			Route::post('/consultantassignment/update', [
				'as'	=> 'akdn.consultantassignment.update',
				'uses'	=> 'ConsultantAssignmentController@update'
			]);


			Route::get('assignment/{id}',array(
					'as'=>'akdn.index',
					'uses'=>'AkdnAssignmentController@index'
				));
			
		});
	});

	Route::get('user/newpassword', array(
		'as'	=> 'user.newpassword.get',
		'uses'	=> 'UserLoginController@getNewPassword'
	));

	Route::post('user/newpassword', array(
		'as'	=> 'user.newpassword.post',
		'uses'	=> 'UserLoginController@postNewPassword'
	));



	Route::group(array('prefix' => 'admin'), function()
	{
		# Admin Login
		Route::get('/login', array('as' => 'admin.login' ,'uses' => 'AdminController@login'));
		Route::post('/login', array('as' => 'admin.login.post', 'uses' => 'AdminController@doLogin'));
		Route::get('/locked', array('as' => 'admin.locked.get', 'uses' => 'AdminController@getLocked'));
		Route::get('session',[
			'as' => 'admin.session',
			'uses' => 'AdminController@loginSession'
		]);

		// Password reset
		Route::controller('password', 'AdminPasswordRemindersController');

		//Route::get('logout', array('as' => 'admin.logout' ,'uses' => 'AdminLoginController@logout'));
		Route::group(array('before' => 'auth.admin'), function(){

			Route::get('/', array(
				'as' => 'admin.home',
				'uses' => 'AdminController@dashboard'
			));

		

			Route::get('/changepassword', array(
				'as'	=> 'admin.changepassword.get',
				'uses'  => 'AdminUserController@getChangePassword'
			));

			Route::post('/changepassword', array(
				'as'	=> 'admin.changepassword.post',
				'uses'  => 'AdminUserController@postChangePassword'
			));

			Route::get('/dashboard',array(
				'as'=> 'admin.dashboard',
				'uses'=>'AdminController@dashboard'
			));

			Route::get('/logout', array(
				'as'	=> 'admin.logout',
				'uses'  => 'AdminController@getLogout'
			));

			Route::get('user/previous-consultancies/{id}',array(
					'as'=>'admin.showPreviousConsultancies',
					'uses'=>'AdminConsultantController@showPreviousConsultancies'
				));

			Route::get('/denied',array(
				'as' => 'admin.denied',
				'uses' => 'AdminController@getDenied'
			));

			Route::group(array('before'=>'acl.permited'),function(){

				Route::get('/users', array(
					'as'=> 'admin.user.index',
					'uses'=>'AdminConsultantController@index'
				));
				Route::get('/user/{id}', array(
					'as'=> 'admin.constant.show',
					'uses'=>'AdminConsultantController@show'
				));
				Route::get('/consultant/{id}/edit', array(
					'as'=> 'admin.consultant.edit',
					'uses'=>'AdminConsultantController@edit'
				));
				Route::post('/consultant/{id}/edit', array(
					'as'=> 'admin.consultant.update',
					'uses'=>'AdminConsultantController@update'
				));
				Route::post('/resume/{id}/delete', array(
					'as'	=> 'admin.resume.delete',
					'uses'  => 'AdminConsultantController@deleteResume'
				));
				
				Route::delete('/user/destroy', array(
					'as'	=> 'admin.user.delete',
					'uses'	=> 'AdminController@destroy' 
				));

				Route::get('/download/{filename}/',array(
					'as' => 'admin.download',
					'uses' => 'AdminController@Download'
				));

				Route::delete('/akdn/destroy', array(
					'as'	=> 'admin.akdn.delete',
					'uses'	=> 'AdminAkdnController@destroy' 
				));

				Route::post('/akdn/{id}',array(
					'as'=>'admin.akdn.update',
					'uses'=>'AdminAkdnController@update'
				));
				Route::resource('akdn','AdminAkdnController');

				Route::resource('role','AdminRoleController');

				Route::post('/role/{role_id}',array(
					'as'=>'admin.role.update',
					'uses'=>'AdminRoleController@update'
				));
				Route::delete('/role/destroy', array(
					'as'	=> 'admin.role.delete',
					'uses'	=> 'AdminRoleController@destroy' 
				));

				Route::resource('specialization','AdminSpecializationController');
				Route::post('/specialization/{id}',array(
					'as'=>'admin.specialization.update',
					'uses'=>'AdminSpecializationController@update'
				));
				Route::delete('/specialization/destroy', array(
					'as'	=> 'admin.specialization.delete',
					'uses'	=> 'AdminSpecializationController@destroy' 
				));

				Route::resource('adminuser','AdminUserController');
				Route::post('/adminuser/{id}',array(
					'as'=>'admin.adminuser.update',
					'uses'=>'AdminUserController@update'
				));
				Route::delete('/adminuser/destroy', array(
					'as'	=> 'admin.adminuser.delete',
					'uses'	=> 'AdminUserController@destroy'
				));

				Route::resource('awarded','AdminAwardedConsultancyController');
				
				Route::post('/awarded/update', [
					'as'	=> 'admin.awarded.update',
					'uses'	=> 'AdminAwardedConsultancyController@update'
				]);
				Route::delete('/awardedconsultancy/destroy', array(
					'as'	=> 'admin.awarded.delete',
					'uses'	=> 'AdminAwardedConsultancyController@destroy' 
				));

				Route::resource('language','AdminLanguageController');
				Route::post('/language/update',array(
					'as'=>'admin.language.update',
					'uses'=>'AdminLanguageController@update'
					));
				Route::delete('/language/destroy', array(
					'as'	=> 'admin.language.delete',
					'uses'	=> 'AdminLanguageController@destroy' 
				));
				Route::resource('skill','AdminSkillController');
				Route::post('/skill/update',array(
					'as'=>'admin.skill.update',
					'uses'=>'AdminSkillController@update'
					));
				Route::delete('/skill/destroy', array(
					'as'	=> 'admin.skill.delete',
					'uses'	=> 'AdminSkillController@destroy' 
				));

				Route::get('firewall',[
					'as' => 'admin.firewall.index',
					'uses' => 'FirewallController@index'
				]);
				Route::post('firewall/block',[
					'as' => 'admin.firewall.block',
					'uses' => 'FirewallController@blockIp'
				]);
				Route::post('firewall/unblock',[
					'as' => 'admin.firewall.unblock',
					'uses' => 'FirewallController@unblockIp'
				]);
				Route::delete('/firewall/destroy', array(
					'as'	=> 'admin.firewall.delete',
					'uses'	=> 'FirewallController@destroy' 
				));
				Route::get('loginlogs/akdn',[
					'as' => 'admin.loginlogs.akdn',
					'uses' => 'LoginLogsController@akdn'
				]);
				Route::get('loginlogs/admin',[
					'as' => 'admin.loginlogs.admin',
					'uses' => 'LoginLogsController@admin'
				]);
				Route::get('loginlogs/user',[
					'as' => 'admin.loginlogs.user',
					'uses' => 'LoginLogsController@user'
				]);

				//Route::resource('agencies','AdminAgenciesController');

				Route::post('agencies/order', array(
					'as'=> 'admin.agencies.order',
					'uses'=>'AdminAgenciesController@setOrder'
				));

				Route::get('/agencies/index', array(
					'as'=> 'admin.agencies.index',
					'uses'=>'AdminAgenciesController@index'
				));

				Route::get('/agencies/create', array(
					'as'=> 'admin.agencies.create',
					'uses'=>'AdminAgenciesController@create'
				));

				Route::post('/agencies/store', array(
					'as'=> 'admin.agencies.store',
					'uses'=>'AdminAgenciesController@store'
				));

				Route::get('agencies/edit/{id}',array(
					'as'=>'admin.agencies.edit',
					'uses'=>'AdminAgenciesController@edit'
				));

				Route::post('agencies/destroy', array(
					'as'	=> 'admin.agencies.delete',
					'uses'	=> 'AdminAgenciesController@destroy' 
				));

				Route::post('agencies/{id}',array(
					'as'=>'admin.agencies.update',
					'uses'=>'AdminAgenciesController@update'
				));

				Route::get('/mails/create', array(
					'as'=> 'admin.mailsend.create',
					'uses'=>'AdminMailSendController@create'
				));

				Route::post('/mails/store', array(
					'as'=> 'admin.mailsend.store',
					'uses'=>'AdminMailSendController@store'
				));

				Route::get('/constantsponsor', array(
					'as'=> 'admin.constantsponsor.index',
					'uses'=>'AdminConsultantSponserController@index'
				));

				Route::post('/constantsponsor/destroy', array(
					'as'	=> 'admin.constantsponsor.delete',
					'uses'	=> 'AdminConsultantSponserController@destroy' 
				));
				// Route::get('/consultant/{id}/edit', array(
				// 	'as'=> 'admin.consultant.edit',
				// 	'uses'=>'AdminConsultantController@edit'
				// ));
				// Route::post('/consultant/{id}/edit', array(
				// 	'as'=> 'admin.consultant.update',
				// 	'uses'=>'AdminConsultantController@update'
				// ));
				// Route::post('/resume/{id}/delete', array(
				// 	'as'	=> 'admin.resume.delete',
				// 	'uses'  => 'AdminConsultantController@deleteResume'
				// ));
				
				// Route::delete('/user/destroy', array(
				// 	'as'	=> 'admin.user.delete',
				// 	'uses'	=> 'AdminController@destroy' 
				// ));
			});
		});
	});
});

App::missing(function($exception)
{
    return Response::view('404', array(), 404);
});
