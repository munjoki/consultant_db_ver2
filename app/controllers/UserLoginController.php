<?php
/*----------------------------------------------------------------------------------------------------------------------------------------
	AKDN Consultant Database Version 1.0
	this controller file handles consultants/user logins
------------------------------------------------------------------------------------------------------------------------------------------*/
/*----------------------------------------------------------------------------------------------------------------------------------------
	Admin Module: 
        This controller file handles the consultants/user logins

    * @version    AKDN Consultant Database Version 1.0
------------------------------------------------------------------------------------------------------------------------------------------*/ 
class UserLoginController extends BaseController {

	/**
	 * User Login form 
	 * 
	 * @method GET 
	 * @return HTML -> User Login form
	 */
	public function login(){

		return View::make('user.login');
	}

	/**
	 * User validates and user login
	 * 
	 * @method POST 
	 * @return if failed -> redirect back with error messages 
	 * @return if successful -> redirect back with successful confirmation 
	 */
	public function postLogin()
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

		$validator = Validator::make(Input::all(), $rules, $messages);

		$login_failed_count = (Session::has('login_failed_count')) ? Session::get('login_failed_count') : 0;
		$ip = Request::getClientIp(true);

		if ($validator->fails()) {

			return Redirect::back()->withErrors($validator) 
								   ->withInput(Input::only('email')) 
								   ->with('message', trans('Invalid user login'))
								   ->with('message_type', 'danger');
		} else {
			// create our user data for the authentication

			$userdata = array(
				'email' 	=> Input::get('email'),
				'password' 	=>Input::get('password'),
				'delete_request' => 0 || null

			);

			$remember = Input::has('remember') ? true : false;

			if ( Auth::user()->validate($userdata,$remember) ) {

				$user = User::select('id')->where('email', $userdata['email'])->first();
				$user_id = $user->id;

				$session = SessionModel::where('user_type', 'user')
									   ->where('user_id', $user_id)
									   ->where('last_activity', '>', time() - ( Config::get('session.lifetime') * 60 ) )
									   ->where('id', '<>', Session::getId())
									   ->orderBy('last_activity', 'desc')
									   ->first();

				if ($session) {
					return Redirect::route('remotelogin.get');
				}

				Auth::user()->loginUsingId($user_id, $remember);

				Session::forget('login_failed_count');
				Firewall::whitelist($ip);

				$location = Location::get($ip);

				if($location) {
					$loginLog =  new LoginLog();
					$loginLog->user_id = $user_id;
					$loginLog->ip_address = $location->ip;
					$loginLog->country = $location->countryName;
					$loginLog->type = 'user';
					$loginLog->login_time = Carbon\Carbon::now();
					$loginLog->save();
				}

				return Redirect::route('user.session');

			}else{

				Session::put('login_failed_count', ++$login_failed_count);

				if($login_failed_count >= 6)
				{
					Firewall::blacklist($ip, true);
				}

				return Redirect::back()->with('message', trans('Invalid user login'))
										->with('message_type', 'danger');
			}
		}
	}

	/**
	 * User logout
	 * 
	 * @method POST 
	 * @return successful -> redirect back with successful confirmation 
	 */
	public function logout(){

		$id = Auth::user()->id();
		$sessionId = Session::getId();

		$getUserLoginLog = LoginLog::selectRaw('MAX(id) as id')->where(['user_id' => $id,'type' => 'user'])->first();

		if ($getUserLoginLog) {
			$loginLog = LoginLog::find($getUserLoginLog['id']);
			$loginLog->logout_time = Carbon\Carbon::now();
			$loginLog->save();
		}

		Auth::user()->logout();

		$session = SessionModel::where('user_type', 'user')->where('id', $sessionId)->delete();

		return Redirect::route('user.login.get')
					   ->with('message', 'You have successfully logged out')
					   ->with('message_type','success')
					   ->with('message_title','Successful!');

	}

	public function store()
	{
		//
	}

	 /**
	 * User or consultant Show form =>show all profile of consultant
	 * 
	 * @method GET 
	 * @return HTML -> consultant profile Page  
	 */
	public function showProfile()
	{

		$user = User::with('consultant','consultantNationality')->find(Auth::user()->get()->id);
		$nationalities = $user->nationalities();
		$specialization = $user->specialization();
		$workedcountries = $user->workedcountries();
		$skills = $user->skills();
		$languages = $user->languages();	
		$agencies = $user->agencies();

		return View::make('user.profile.show', compact('user', 'nationalities','specialization','workedcountries','skills','languages','agencies'));
	}

	 /**
	 * consultant Edit form 
	 * 
	 * @method GET 
	 * @return HTML -> Akdn Edit Form  
	 */
	public function editProfile()
	{

		$user = User::with('consultant','consultantLanguage','consultantNationality')->find(Auth::user()->get()->id);

		// to populate dropdowns
		$city           = City::lists('city_des','id');
		$country        = Country::orderBy('country_des','asc')->lists('country_des','id');
		$language       = Language::lists('lang_acr','id');
		$skills         = Skill::lists('skills_des','id');
		$specialization = Specialization::lists('spec_des','id');
		$country_worked = $country;
		$agencies 		= Agency::orderBy('acronym','asc')->lists('acronym','id');

		// existing nationalities
		$consultant_nationalities = ($user->consultantNationality) ? array_fetch($user->consultantNationality->toArray(), 'country_id') : array();
		
		// existing skills
		$consultant_skills = ($user->consultantSkill) ? array_fetch($user->consultantSkill->toArray(), 'skill_id') : array();

		// existing specialization
		$consultant_specialization = ($user->consultantSpecialization) ? array_fetch($user->consultantSpecialization->toArray(), 'specialization_id') : array();

		// existing workedcountries
		$consultant_workedcountries = ($user->consultantWorkedCountry) ? array_fetch($user->consultantWorkedCountry->toArray(), 'country_id') : array();

		// existing selected languages and their level
		$consultant_languages = $user->consultantLanguage->toArray();

		// existing workedcountries
		$consultant_agencies = ($user->consultantAgencies) ? array_fetch($user->consultantAgencies->toArray(), 'agency_id') : array();

		$load_consultant_languages = array();
		if($consultant_languages){
			foreach ($consultant_languages as $value) {
				// echo "<pre>";
				// print_r($value);
				// exit();
				$lang_data = array();
				$lang_data['language'] = $value['language_id'];
				$lang_data['speaking_level'] = $value['speaking_level'];
				$lang_data['reading_level'] = $value['reading_level'];
				$lang_data['writing_level'] = $value['writing_level'];
				$lang_data['understanding_level'] = $value['understanding_level'];
				$load_consultant_languages[] = $lang_data;
			}	
		}
		
		return View::make('user.profile.edit',compact(
			'user','city','country','language', 'skills','specialization','country_worked','agencies',
			'load_consultant_languages','consultant_nationalities','consultant_skills',
			'consultant_specialization','consultant_workedcountries','consultant_agencies'
		));
	}

	/**
	 * Consultant validates, update record in database
	 * 
	 * @method POST 
	 * @return if failed -> redirect back to edit form with error messages 
	 * @return if successful -> redirect back to edit form with successful confirmation 
	 */
	public function updateProfile(){

		$user_id = Auth::user()->get()->id;
		$user = User::with('consultant')->find($user_id);
		
		$consultant = Consultant::find(Auth::user()->get()->id);
		$website_url = $consultant['website_url'];

		$user_name = $consultant['other_names'];
		$user_surname = $consultant['surname'];
	
		$post_data = Input::all();

		$nationalities = Input::get('nationality');
		
		$skills = Input::get('skill', array());
		$specializations = Input::get('specialization',array());
		$worked_countries = Input::get('country_worked',array());
		$consultant_agencies = Input::get('consultant_agencies',array());
		$languages = $post_data['languages']['languages'];

		$rules = Consultant::editRules();

		if ($consultant->resume == null) {
			if (!Input::hasFile('resume')) {
				$rules['linkedin_url'] = 'url|required_without_all:resume,website_url';
				$rules['website_url'] = 'url|required_without_all:resume,linkedin_url';
			}
			$rules['resume'] = 'mimes:doc,docx,txt,pdf|max:1024|extension:txt,doc,docx,pdf|required_without_all:linkedin_url,website_url';
		}

		$validation = Validator::make($post_data, $rules,Consultant::$messages);

		if ($validation->passes()){

			$old_resume = $consultant->resume;

			$consultant->fill(Input::except('resume','terms_conditions','email'));
			
			if(Input::hasFile('resume')){

	        	$destinationPath = public_path().'/upload/resume/';

	        	if($old_resume != ""){
	        		$resume_to_delete = $destinationPath . $old_resume;
	        		@unlink($resume_to_delete);
	        	}

	            $file = Input::file('resume');
	            
	            $extension = $file->getClientOriginalExtension();
	            $user_fullname = $user_name." ".$user_surname;
	           	$user_fullname = $user_name." ".$user_surname;
				$file_name = Str::slug($user_fullname,'_');
	            $doc_name = $file_name.'.'.$extension;

	            $file->move($destinationPath,$doc_name);
	           
	            $consultant->resume = $doc_name;
			}
			$consultant->save();

			if($languages)
			{

				ConsultantLanguage::where('consultant_id', $user_id)->delete();
				foreach ($languages as $key => $language) 
				{
					if($language['language'] != ""){

						$consultant_language = new ConsultantLanguage();
						$consultant_language->consultant_id = $user->id;
						$consultant_language->language_id = $language['language'];
						// $consultant_language->language_level = $language['lang_level'];
						$consultant_language->speaking_level = $language['speaking_level'];
						$consultant_language->reading_level = $language['reading_level'];
						$consultant_language->writing_level = $language['writing_level'];
						$consultant_language->understanding_level = $language['understanding_level'];
						$consultant_language->save();
					}
				}
			}

			$nationality_list = array();
			
			if( count($nationalities) > 0 ){

				foreach ($nationalities as $nationality_id) {
					$nationality['country_id'] = $nationality_id;
					array_push($nationality_list, $nationality);
				}

				ConsultantNationality::where('consultant_id', $user_id)->delete();
				$user->consultantNationality()->createMany($nationality_list);
	
			}
			
			$skill_list = array();
			
			if( count($skills) > 0 ){

				foreach ($skills as $skill_id) {
					$skill['skill_id'] = $skill_id;
					array_push($skill_list, $skill);
				}

				ConsultantSkill::where('consultant_id', $user_id)->delete();
				$user->consultantSkill()->createMany($skill_list);
			}
			

			$specialization_list = array();
			
			if( count($specializations) > 0 ){

				foreach ($specializations as $specialization_id) {
					$specialization['specialization_id'] = $specialization_id;
					array_push($specialization_list, $specialization);
				}

				ConsultantSpecialization::where('consultant_id', $user_id)->delete();
				$user->consultantSpecialization()->createMany($specialization_list);
			}
			

			$worked_country_list = array();
			
			if( count($worked_countries) > 0 ){

				foreach ($worked_countries as $worked_country_id) {
					$worked_country['country_id'] = $worked_country_id;
					array_push($worked_country_list, $worked_country);
				}
				ConsultantWorkedCountry::where('consultant_id', $user_id)->delete();
				$user->consultantWorkedCountry()->createMany($worked_country_list);
			}

			$consultant_agency_list = array();
			
			if( count($consultant_agencies) > 0 ){

				foreach ($consultant_agencies as $consultant_agency_id) {
					$consultant_agency['agency_id'] = $consultant_agency_id;
					array_push($consultant_agency_list, $consultant_agency);
				}
				ConsultantAgency::where('consultant_id', $user_id)->delete();
				$user->consultantAgencies()->createMany($consultant_agency_list);
			}

			return Redirect::route('user.profile')->with('message', 'You have successfully updated your profile')
								   ->with('message_type', 'success');
		 }else{

				$errors = $validation->errors()->toArray();
				// echo "<pre>";
				// print_r($errors);exit;
				return Redirect::back()
				->withInput(Input::all())
				->withErrors($validation)
				->with('message', 'Some required field(s) have been left blank. Please correct the error(s)!')
				->with('message_type', 'danger');
		}
	}
	
	public function edit($id)
	{
		//
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}
	
	 /**
	 * verified consultant
	 * 
	 * @method GET 
	 * @param  string  $token => token
	 * @return HTML   
	 */
	public function loginToken($token){

		$user = User::where('remember_token','=',$token)->first();
		$user->is_verified = 1;
		$user->save();
		$email = $user->email;

		Session::put('token', $token);

		return Redirect::route('user.newpassword.get')->with('message',"Your AKDN Consultant account has been verified successfully!")
								   					  ->with('message_type','success');
	}


	 /**
	 * Consultant new password form 
	 * 
	 * @method GET 
	 * @return HTML -> Consultant new password form  
	 */
	public function getNewPassword()
	{

		return View::make('user.newpassword');	
	}

	/**
	 * Consultant validates, stores record in database and sends email
	 * 
	 * @method POST 
	 * @return if failed -> redirect back with error messages 
	 * @return if successful -> redirect back with successful confirmation 
	 */
	public function postNewPassword()
	{
		//$user = User::find(Auth::user()->get()->id);
		$user = User::where('remember_token', Input::get('token'))->first();

		if( !$user ){
			return Redirect::back()->with('message',"Invalid User")
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

		Validator::extend('check_password', function($attribute, $value, $parameters) use ($user)
		{
			$old_password = Hash::check($value, $user->password);

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
			$user->password = Hash::make(Input::get('password'));
			$user->password_changed = '1';
			$user->remember_token = NULL;
			$user->save();

			Session::forget('token');

			return Redirect::route('user.login.get')->with('message',"Password updated successfully.")
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
	 * consultant changepassword form 
	 * 
	 * @method GET 
	 * @return HTML -> consultant changepassword form 
	 */
	public function getChangePassword()
	{
		return View::make('user.password.edit');
	}

	/**
	 * consultant validates, stores record in database
	 * 
	 * @method POST 
	 * @return JSON
	 */
	public function postChangePassword()
	{
		if( Request::ajax() ) {
			$user = User::find(Auth::user()->get()->id);

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
				$user   = User::find(Auth::user()->get()->id);
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
				$user->password = Hash::make(Input::get('password'));
				$user->save();

				return Response::json(array('success' => true));
			} 

			$errors = $validator->errors()->toArray();
			return Response::json(array('success' => false, 'errors' => $errors));
		}
	}

	 /**
	 * Consultant delete resume
	 * 
	 * @method POST 
	 * @return JSON
	 */
	public function deleteResume()
	{
		$consultant = Consultant::find(Auth::user()->get()->id);

		$old_resume = $consultant->resume;		
		$destinationPath = public_path().'/upload/resume/';

    	if($old_resume != ""){
    		$consultant->resume = null;
    		$consultant->save();
    		$resume_to_delete = $destinationPath . $old_resume;
    		@unlink($resume_to_delete);
    	}
    	return Response::json(array('msg' => 'Resume deleted Successful','success'=>true), 200);
	}

	 /**
	 * Consultant delete account
	 * 
	 * @method POST 
	 * @return JSON
	 */
	public function postDeleteAccount(){

		$consultant = Consultant::find(Auth::user()->get()->id);
		$title = $consultant->title;
		$name = $consultant->surname;

		$user = Auth::user()->get();
		$user->delete_request = 1;
		$user->save();

		Mail::send('emails.user.deleteaccount', compact('title', 'name'), function($message) {
			$message->to( SUPER_ADMIN_EMAIL, "AKDN Super Admin")
					->subject("AKDN's Consultant Database: Delete Profile Request");
		});

		return Response::json(array('success' => true), 200);
	}

	 /**
	 * Consultant locked form
	 * 
	 * @method GET 
	 * @return HTML -> Consultant locked form
	 */
	public function getlocked(){

        if (Auth::user()->check()) {

        	$id = Auth::user()->id();

			$getUserLoginLog = LoginLog::selectRaw('MAX(id) as id')->where(['user_id' => $id,'type' => 'user'])->first();

			if ($getUserLoginLog) {
				$loginLog = LoginLog::find($getUserLoginLog['id']);
				$loginLog->logout_time = Carbon\Carbon::now();
				$loginLog->save();
			}

            $user = Auth::user()->get();

            $consultant = Consultant::select('other_names')->where('id',Auth::user()->get()->id)->first();
            Session::put('user',['username' => $consultant['other_names'], 'email' => $user['email']]);

            Auth::user()->logout();

	    	return View::make('user.locked');

        } elseif(Session::has('user')){

        	return View::make('user.locked');
        } else {

        	return Redirect::route('user.login.get');
        }

    }

    public function loginSession()
	{
		$user_id = Auth::user()->id();
		$session_id = Auth::user()->getSession()->getId();

		$session = SessionModel::find($session_id);

		$session->user_type = 'user';
		$session->user_id = $user_id;
		$session->save();

		return Redirect::route('user.profile');
	}
}