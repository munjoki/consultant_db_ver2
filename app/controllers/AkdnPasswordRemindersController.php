<?php
/*----------------------------------------------------------------------------------------------------------------------------------------
	AKDN Consultant Database Version 1.0
	this controller file handles the AKDN user password reset
------------------------------------------------------------------------------------------------------------------------------------------*/

class AkdnPasswordRemindersController extends Controller {

	/**
	 * Display the password reminder view.
	 *
	 * @return Response
	 */
	public function getRemind()
	{
		return View::make('akdn.password.remind');
	}

	/**
	 * Handle a POST request to remind a user of their password.
	 *
	 * @return Response
	 */
	public function postRemind()
	{	

		Config::set('auth.reminder.email', 'emails.auth.akdn.reminder');
		$response = Password::akdn()->remind(Input::only('email'), function($message, $user, $type, $token){
			$message->subject('AKDN Consultant Database: Reset your password');
			// echo "<pre>";
			// dd($user);
		});

		switch ( $response )
		{
			case Password::INVALID_USER:
				return Redirect::back()->withInput()->with('message', Lang::get($response))->with('message_type','danger');

			case Password::REMINDER_SENT:
				return Redirect::back()->with('message', Lang::get($response))->with('message_type','success');
		}
	}

	/**
	 * Display the password reset view for the given token.
	 *
	 * @param  string  $token
	 * @return Response
	 */
	public function getReset($token = null)
	{
		if (is_null($token)) App::abort(404);

		return View::make('akdn.password.reset')->with('token', $token);
	}

	/**
	 * Handle a POST request to reset a user's password.
	 *
	 * @return Response
	 */
	public function postReset()
	{

		$credentials = Input::only(
			'email', 'password', 'password_confirmation', 'token'
		);
		
		Validator::extend('strong_password', function($attribute, $value, $parameters) {
			$r1 = '/[A-Z]/';  					 //Uppercase
			$r2 = '/[a-z]/';  					 //lowercase
			$r3 = '/[!@#$%^&*()\-_=+{};:,<.>]/'; // whatever you mean by 'special char'
			$r4 = '/[0-9]/';  					 //numbers

			if(preg_match_all($r1,$value, $o)<1) return false;

			if(preg_match_all($r2,$value, $o)<1) return false;

			if(preg_match_all($r3,$value, $o)<1) return false;

			if(preg_match_all($r4,$value, $o)<1) return false;

			if(strlen($value)<8) return false;

			return true;
		});

		$rules = array(
			'password' 				=> 'required|strong_password',
		);

		$validator = Validator::make(Input::only('password'), $rules);

		if($validator->passes())
		{
			$response = Password::akdn()->reset($credentials, function($user, $password){
				$user->password = Hash::make($password);
				$user->save();
			});

			switch ($response)
			{
				case Password::INVALID_PASSWORD:
				case Password::INVALID_TOKEN:
				case Password::INVALID_USER:
					return Redirect::back()->with('message', Lang::get($response))->with('message_type','danger');

				case Password::PASSWORD_RESET:
					return Redirect::route('akdn.login')->with('message', 'Password updated successfully')->with('message_type','success');
			}
		}
		else
		{
			return Redirect::back()->with('message', 'Passwords must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, one digit and one special character.')->with('message_type','danger');
		}
	}
}