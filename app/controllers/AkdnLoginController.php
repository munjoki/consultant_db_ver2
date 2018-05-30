<?php
/*----------------------------------------------------------------------------------------------------------------------------------------
	AKDN MER Consultant Database Version 1.0
	this controller file handles the AKDN user login
------------------------------------------------------------------------------------------------------------------------------------------*/ 

class AkdnLoginController extends BaseController{
	
	 /**
	 * Akdn login form 
	 * 
	 * @method GET 
	 * @return HTML -> Akdn login Form  
	 */
	public function login(){

		return View::make('akdn.login');

	}

	/**
	 * Akdn validates and login 
	 * 
	 * @method POST 
	 * @return if failed -> redirect back to with error messages 
	 * @return if successful -> redirect to akdn home with successful confirmation 
	 */
	public function doLogin()
	{
		$rules = array(
				'email'    => 'required|email', // make sure the email is an actual email
				'password' => 'required' // password can only be alphanumeric and has to be greater than 3 characters
		);

		$messages = [];

		if(Input::exists('captcha'))
		{
			$rules['captcha'] = 'required|captcha';
			$messages['captcha.captcha'] = 'captcha not match try again';
		}

		$login_failed_count = (Session::has('login_failed_count')) ? Session::get('login_failed_count') : 0;
		$ip = Request::getClientIp(true);

		$validator = Validator::make(Input::all(), $rules, $messages);

		if ($validator->fails()) {

			return Redirect::route('akdn.login')
						->withErrors($validator) 
						->withInput(Input::only('email')) 
						->with('message', trans('Please enter valid details'));
		} else {

			// create our user data for the authentication
			$userdata = array(
				'email' 	=> Input::get('email'),
				'password' 	=>Input::get('password'),
			);

			$remember = Input::has('remember') ? true : false;

			if ( Auth::akdn()->validate($userdata, $remember)) {

				$user = Akdn::select('id')->where('email', $userdata['email'])->first();
				$user_id = $user->id;

				$session = SessionModel::where('user_type', 'akdn')
									   ->where('user_id', $user_id)
									   ->where('last_activity', '>', time() - ( Config::get('session.lifetime') * 60 ) )
									   ->where('id', '<>', Session::getId())
									   ->orderBy('last_activity', 'desc')
									   ->first();

				if ($session) {
					return Redirect::route('remotelogin.get');
				}

				Auth::akdn()->loginUsingId($user_id, $remember);

				Session::forget('login_failed_count');
				Firewall::whitelist($ip);

				$location = Location::get($ip);

				if($location) {
					$loginLog = new LoginLog();
					$loginLog->user_id = $user_id;
					$loginLog->ip_address = $location->ip;
					$loginLog->country = $location->countryName;
					$loginLog->type = 'akdn';
					$loginLog->login_time = Carbon\Carbon::now();
					$loginLog->save();
				}

				return Redirect::route('akdn.home');
		
			} else {

				Session::put('login_failed_count', ++$login_failed_count);

				if($login_failed_count >= 6)
				{
					Firewall::blacklist($ip, true);
				}

				return Redirect::route('akdn.login')->with('message', trans('Invalid user login'))
													->with('message_type','danger');
			}
		}
	}

	
	 /**
	 * verified akdn user account
	 * 
	 * @method GET 
	 * @return if failed -> redirect back to akdn login with error messages 
 	 * @return if successful -> redirect to new password form with successful confirmation 
	 */
	public function loginToken($token){

		if(Akdn::where('remember_token','=',$token)->first()){
			$akdn = Akdn::where('remember_token','=',$token)->first();
			$akdn->is_verified = 1;
			$akdn->save();
			$email = $akdn->email;

			Session::put('token', $token);

			return Redirect::route('akdn.newpassword.get')->with('message',"Your AKDN Consultant account has been verified successfully!")
								   					  ->with('message_type','success');
		}else{
			return Redirect::route('akdn.login')->with('message',"Link expired ")
								   ->with('message_type','danger');

		}

	}

	 /**
	 * Akdn new password form 
	 * 
	 * @method GET 
	 * @return HTML -> Akdn new password form  
	 */
	public function getNewPassword()
	{
		return View::make('akdn.newpassword');	
	}

	/**
	 * Admin akdn validates, stores record in database
	 * 
	 * @method POST 
	 * @return if failed -> redirect back with error messages 
	 * @return if successful -> redirect back to login with successful confirmation 
	 */
	public function postNewPassword()
	{
		//$user = User::find(Auth::user()->get()->id);
		$akdn = Akdn::where('remember_token', Input::get('token'))->first();

		if( !$akdn ){
			return Redirect::back()->with('message',"Invalid AkdnUser")
								   ->with('message_type','danger');
		}

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

		Validator::extend('check_password', function($attribute, $value, $parameters) use ($akdn)
		{
			$old_password = Hash::check($value, $akdn->password);

			if($old_password == true) return true;

			return false;
		});

		$rules = array(
			'old_password' 			=> 'required|check_password',
			'password' 				=> 'required|strong_password|confirmed',
			'password_confirmation' => 'required',
		);

		$messages = array(
			'password.strong_password'    => 'Passwords must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, one digit and one special character.',
			'old_password.check_password' => 'Please enter correct system-generated password',
			'old_password.required' => 'Please enter the system generated password',
		);

		$response  = [];
		$validator = Validator::make(Input::all(), $rules, $messages);

		if($validator->passes())
		{
			$akdn->password = Hash::make(Input::get('password'));
			$akdn->password_changed = '1';
			$akdn->remember_token = NULL;
			$akdn->save();

			Session::forget('token');

			return Redirect::route('akdn.login')->with('message',"Password updated successfully.")
								   ->with('message_type','success');
		}
		else
		{

			return Redirect::back()->with('message',"Could not update password")
								   ->with('message_type','danger')
								   ->withErrors($validator);
		}
	}

	/**
	 * Akdn logout
	 * 
	 * @method GET 
	 * @return HTML -> 
	 * @return successful -> redirect back to akdn login with successful confirmation   
	 */
	public function logout(){

		$id = Auth::akdn()->id();
		$sessionId = Session::getId();

		$getUserLoginLog = LoginLog::selectRaw('MAX(id) as id')->where(['user_id' => $id,'type' => 'akdn'])->first();

		if ($getUserLoginLog) {
			$loginLog = LoginLog::find($getUserLoginLog['id']);
			$loginLog->logout_time = Carbon\Carbon::now();
			$loginLog->save();
		}

		Auth::akdn()->logout();

		$session = SessionModel::where('user_type', 'akdn')->where('id', $sessionId)->delete();

		return Redirect::route('akdn.login');
	}

	 /**
	 * Akdn locked form 
	 * 
	 * @method GET 
	 * @return HTML -> Akdn locked form 
	 */
	public function getlocked(){

        if (Auth::akdn()->check()) {

        	$id = Auth::akdn()->id();

			$getUserLoginLog = LoginLog::selectRaw('MAX(id) as id')->where(['user_id' => $id,'type' => 'akdn'])->first();

			if ($getUserLoginLog) {
				$loginLog = LoginLog::find($getUserLoginLog['id']);
				$loginLog->logout_time = Carbon\Carbon::now();
				$loginLog->save();
			}

            $user = Auth::akdn()->get();
            Auth::akdn()->logout();

            Session::put('akdn',['username' => $user['other_name'], 'email' => $user['email']]);

    	    return View::make('akdn.locked');

        } elseif (Session::has('akdn')) {

        	return View::make('akdn.locked');
        } 
        else {

            return Redirect::route('akdn.login');
        }

    }

    public function loginSession()
	{
		$user_id = Auth::akdn()->id();
		$session_id = Auth::akdn()->getSession()->getId();

		$session = SessionModel::find($session_id);

		$session->user_type = 'akdn';
		$session->user_id = $user_id;
		$session->save();

		return Redirect::route('akdn.home');
	}

	 /**
	 * Akdn change password form 
	 * 
	 * @method GET 
	 * @return HTML -> Akdn change password form 
	 */
	public function getChangePassword()
	{
		return View::make('akdn.changepassword');
	}

	/**
	 * Akdn validates, stores record in database
	 * 
	 * @method POST 
	 * @return JSON 
	 */
	public function postChangePassword()
	{
		$akdn_user = Akdn::find(Auth::akdn()->get()->id);

		Validator::extend('strong_password', function($attribute, $value, $parameters) {
			$r1 = '/[A-Z]/';
			$r2 = '/[a-z]/';
			$r3 = '/[!@#$%^&*()\-_=+{};:,<.>]/';
			$r4 = '/[0-9]/';

			if(preg_match_all($r1,$value, $o)<1) return false;

			if(preg_match_all($r2,$value, $o)<1) return false;

			if(preg_match_all($r3,$value, $o)<1) return false;

			if(preg_match_all($r4,$value, $o)<1) return false;

			if(strlen($value)<8) return false;

			return true;
		});

		Validator::extend('check_password', function($attribute, $value, $parameters) 
		{	
			$user   = Akdn::find(Auth::akdn()->get()->id);
			$old_password = Hash::check($value, $user->password);
			
			if($old_password == true) return true;

			return false;
		});

		$rules = array(
			'old_password' 			=> 'required|check_password',
			'password' 				=> 'required|strong_password|different:old_password',
			'password_confirmation' => 'required|same:password',
		);

		$messages = array(
			'old_password.check_password' => 'Please enter correct password',
			'password.strong_password'    => 'Passwords must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, one digit and one special character.',
			'password.different'		  => 'new password must be different',
		);

		$validator = Validator::make(Input::all(), $rules, $messages);

		if($validator->passes())
		{
			$akdn_user->password = Hash::make(Input::get('password'));
			$akdn_user->save();

			return Response::json(array('success' => true));
		} 
		else
		{
			$errors = $validator->errors()->toArray();
			return Response::json(array('success' => false, 'errors' => $errors));
		}

	}
}